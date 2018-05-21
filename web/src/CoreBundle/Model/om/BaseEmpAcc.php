<?php

namespace CoreBundle\Model\om;

use \BaseObject;
use \BasePeer;
use \Criteria;
use \DateTime;
use \Exception;
use \PDO;
use \Persistent;
use \Propel;
use \PropelCollection;
use \PropelDateTime;
use \PropelException;
use \PropelObjectCollection;
use \PropelPDO;
use CoreBundle\Model\EmpAcc;
use CoreBundle\Model\EmpAccPeer;
use CoreBundle\Model\EmpAccQuery;
use CoreBundle\Model\EmpCapabilities;
use CoreBundle\Model\EmpCapabilitiesQuery;
use CoreBundle\Model\EmpProfile;
use CoreBundle\Model\EmpProfileQuery;
use CoreBundle\Model\EmpRequest;
use CoreBundle\Model\EmpRequestQuery;
use CoreBundle\Model\EmpTime;
use CoreBundle\Model\EmpTimeQuery;
use CoreBundle\Model\EventNotes;
use CoreBundle\Model\EventNotesQuery;
use CoreBundle\Model\EventTaggedPersons;
use CoreBundle\Model\EventTaggedPersonsQuery;
use CoreBundle\Model\ListEvents;
use CoreBundle\Model\ListEventsQuery;

abstract class BaseEmpAcc extends BaseObject implements Persistent
{
    /**
     * Peer class name
     */
    const PEER = 'CoreBundle\\Model\\EmpAccPeer';

    /**
     * The Peer class.
     * Instance provides a convenient way of calling static methods on a class
     * that calling code may not be able to identify.
     * @var        EmpAccPeer
     */
    protected static $peer;

    /**
     * The flag var to prevent infinite loop in deep copy
     * @var       boolean
     */
    protected $startCopy = false;

    /**
     * The value for the id field.
     * @var        int
     */
    protected $id;

    /**
     * The value for the username field.
     * @var        string
     */
    protected $username;

    /**
     * The value for the password field.
     * @var        string
     */
    protected $password;

    /**
     * The value for the timestamp field.
     * @var        string
     */
    protected $timestamp;

    /**
     * The value for the ip_add field.
     * @var        string
     */
    protected $ip_add;

    /**
     * The value for the status field.
     * @var        int
     */
    protected $status;

    /**
     * The value for the email field.
     * @var        string
     */
    protected $email;

    /**
     * The value for the role field.
     * @var        string
     */
    protected $role;

    /**
     * The value for the team_role field.
     * @var        string
     */
    protected $team_role;

    /**
     * The value for the key field.
     * @var        string
     */
    protected $key;

    /**
     * The value for the created_by field.
     * @var        int
     */
    protected $created_by;

    /**
     * The value for the last_updated_by field.
     * @var        int
     */
    protected $last_updated_by;

    /**
     * @var        PropelObjectCollection|EmpRequest[] Collection to store aggregation of EmpRequest objects.
     */
    protected $collEmpRequestsRelatedByEmpAccId;
    protected $collEmpRequestsRelatedByEmpAccIdPartial;

    /**
     * @var        PropelObjectCollection|EmpRequest[] Collection to store aggregation of EmpRequest objects.
     */
    protected $collEmpRequestsRelatedByAdminId;
    protected $collEmpRequestsRelatedByAdminIdPartial;

    /**
     * @var        PropelObjectCollection|EmpProfile[] Collection to store aggregation of EmpProfile objects.
     */
    protected $collEmpProfiles;
    protected $collEmpProfilesPartial;

    /**
     * @var        PropelObjectCollection|EmpTime[] Collection to store aggregation of EmpTime objects.
     */
    protected $collEmpTimes;
    protected $collEmpTimesPartial;

    /**
     * @var        PropelObjectCollection|EmpCapabilities[] Collection to store aggregation of EmpCapabilities objects.
     */
    protected $collEmpCapabilitiess;
    protected $collEmpCapabilitiessPartial;

    /**
     * @var        PropelObjectCollection|ListEvents[] Collection to store aggregation of ListEvents objects.
     */
    protected $collListEventss;
    protected $collListEventssPartial;

    /**
     * @var        PropelObjectCollection|EventNotes[] Collection to store aggregation of EventNotes objects.
     */
    protected $collEventNotess;
    protected $collEventNotessPartial;

    /**
     * @var        PropelObjectCollection|EventTaggedPersons[] Collection to store aggregation of EventTaggedPersons objects.
     */
    protected $collEventTaggedPersonss;
    protected $collEventTaggedPersonssPartial;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     * @var        boolean
     */
    protected $alreadyInSave = false;

    /**
     * Flag to prevent endless validation loop, if this object is referenced
     * by another object which falls in this transaction.
     * @var        boolean
     */
    protected $alreadyInValidation = false;

    /**
     * Flag to prevent endless clearAllReferences($deep=true) loop, if this object is referenced
     * @var        boolean
     */
    protected $alreadyInClearAllReferencesDeep = false;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $empRequestsRelatedByEmpAccIdScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $empRequestsRelatedByAdminIdScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $empProfilesScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $empTimesScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $empCapabilitiessScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $listEventssScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $eventNotessScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $eventTaggedPersonssScheduledForDeletion = null;

    /**
     * Get the [id] column value.
     *
     * @return int
     */
    public function getId()
    {

        return $this->id;
    }

    /**
     * Get the [username] column value.
     *
     * @return string
     */
    public function getUsername()
    {

        return $this->username;
    }

    /**
     * Get the [password] column value.
     *
     * @return string
     */
    public function getPassword()
    {

        return $this->password;
    }

    /**
     * Get the [optionally formatted] temporal [timestamp] column value.
     *
     *
     * @param string $format The date/time format string (either date()-style or strftime()-style).
     *				 If format is null, then the raw DateTime object will be returned.
     * @return mixed Formatted date/time value as string or DateTime object (if format is null), null if column is null, and 0 if column value is 0000-00-00 00:00:00
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getTimestamp($format = null)
    {
        if ($this->timestamp === null) {
            return null;
        }

        if ($this->timestamp === '0000-00-00 00:00:00') {
            // while technically this is not a default value of null,
            // this seems to be closest in meaning.
            return null;
        }

        try {
            $dt = new DateTime($this->timestamp);
        } catch (Exception $x) {
            throw new PropelException("Internally stored date/time/timestamp value could not be converted to DateTime: " . var_export($this->timestamp, true), $x);
        }

        if ($format === null) {
            // Because propel.useDateTimeClass is true, we return a DateTime object.
            return $dt;
        }

        if (strpos($format, '%') !== false) {
            return strftime($format, $dt->format('U'));
        }

        return $dt->format($format);

    }

    /**
     * Get the [ip_add] column value.
     *
     * @return string
     */
    public function getIpAdd()
    {

        return $this->ip_add;
    }

    /**
     * Get the [status] column value.
     *
     * @return int
     */
    public function getStatus()
    {

        return $this->status;
    }

    /**
     * Get the [email] column value.
     *
     * @return string
     */
    public function getEmail()
    {

        return $this->email;
    }

    /**
     * Get the [role] column value.
     *
     * @return string
     */
    public function getRole()
    {

        return $this->role;
    }

    /**
     * Get the [team_role] column value.
     *
     * @return string
     */
    public function getTeamRole()
    {

        return $this->team_role;
    }

    /**
     * Get the [key] column value.
     *
     * @return string
     */
    public function getKey()
    {

        return $this->key;
    }

    /**
     * Get the [created_by] column value.
     *
     * @return int
     */
    public function getCreatedBy()
    {

        return $this->created_by;
    }

    /**
     * Get the [last_updated_by] column value.
     *
     * @return int
     */
    public function getLastUpdatedBy()
    {

        return $this->last_updated_by;
    }

    /**
     * Set the value of [id] column.
     *
     * @param  int $v new value
     * @return EmpAcc The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[] = EmpAccPeer::ID;
        }


        return $this;
    } // setId()

    /**
     * Set the value of [username] column.
     *
     * @param  string $v new value
     * @return EmpAcc The current object (for fluent API support)
     */
    public function setUsername($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->username !== $v) {
            $this->username = $v;
            $this->modifiedColumns[] = EmpAccPeer::USERNAME;
        }


        return $this;
    } // setUsername()

    /**
     * Set the value of [password] column.
     *
     * @param  string $v new value
     * @return EmpAcc The current object (for fluent API support)
     */
    public function setPassword($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->password !== $v) {
            $this->password = $v;
            $this->modifiedColumns[] = EmpAccPeer::PASSWORD;
        }


        return $this;
    } // setPassword()

    /**
     * Sets the value of [timestamp] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return EmpAcc The current object (for fluent API support)
     */
    public function setTimestamp($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->timestamp !== null || $dt !== null) {
            $currentDateAsString = ($this->timestamp !== null && $tmpDt = new DateTime($this->timestamp)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->timestamp = $newDateAsString;
                $this->modifiedColumns[] = EmpAccPeer::TIMESTAMP;
            }
        } // if either are not null


        return $this;
    } // setTimestamp()

    /**
     * Set the value of [ip_add] column.
     *
     * @param  string $v new value
     * @return EmpAcc The current object (for fluent API support)
     */
    public function setIpAdd($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->ip_add !== $v) {
            $this->ip_add = $v;
            $this->modifiedColumns[] = EmpAccPeer::IP_ADD;
        }


        return $this;
    } // setIpAdd()

    /**
     * Set the value of [status] column.
     *
     * @param  int $v new value
     * @return EmpAcc The current object (for fluent API support)
     */
    public function setStatus($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->status !== $v) {
            $this->status = $v;
            $this->modifiedColumns[] = EmpAccPeer::STATUS;
        }


        return $this;
    } // setStatus()

    /**
     * Set the value of [email] column.
     *
     * @param  string $v new value
     * @return EmpAcc The current object (for fluent API support)
     */
    public function setEmail($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->email !== $v) {
            $this->email = $v;
            $this->modifiedColumns[] = EmpAccPeer::EMAIL;
        }


        return $this;
    } // setEmail()

    /**
     * Set the value of [role] column.
     *
     * @param  string $v new value
     * @return EmpAcc The current object (for fluent API support)
     */
    public function setRole($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->role !== $v) {
            $this->role = $v;
            $this->modifiedColumns[] = EmpAccPeer::ROLE;
        }


        return $this;
    } // setRole()

    /**
     * Set the value of [team_role] column.
     *
     * @param  string $v new value
     * @return EmpAcc The current object (for fluent API support)
     */
    public function setTeamRole($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->team_role !== $v) {
            $this->team_role = $v;
            $this->modifiedColumns[] = EmpAccPeer::TEAM_ROLE;
        }


        return $this;
    } // setTeamRole()

    /**
     * Set the value of [key] column.
     *
     * @param  string $v new value
     * @return EmpAcc The current object (for fluent API support)
     */
    public function setKey($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->key !== $v) {
            $this->key = $v;
            $this->modifiedColumns[] = EmpAccPeer::KEY;
        }


        return $this;
    } // setKey()

    /**
     * Set the value of [created_by] column.
     *
     * @param  int $v new value
     * @return EmpAcc The current object (for fluent API support)
     */
    public function setCreatedBy($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->created_by !== $v) {
            $this->created_by = $v;
            $this->modifiedColumns[] = EmpAccPeer::CREATED_BY;
        }


        return $this;
    } // setCreatedBy()

    /**
     * Set the value of [last_updated_by] column.
     *
     * @param  int $v new value
     * @return EmpAcc The current object (for fluent API support)
     */
    public function setLastUpdatedBy($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->last_updated_by !== $v) {
            $this->last_updated_by = $v;
            $this->modifiedColumns[] = EmpAccPeer::LAST_UPDATED_BY;
        }


        return $this;
    } // setLastUpdatedBy()

    /**
     * Indicates whether the columns in this object are only set to default values.
     *
     * This method can be used in conjunction with isModified() to indicate whether an object is both
     * modified _and_ has some values set which are non-default.
     *
     * @return boolean Whether the columns in this object are only been set with default values.
     */
    public function hasOnlyDefaultValues()
    {
        // otherwise, everything was equal, so return true
        return true;
    } // hasOnlyDefaultValues()

    /**
     * Hydrates (populates) the object variables with values from the database resultset.
     *
     * An offset (0-based "start column") is specified so that objects can be hydrated
     * with a subset of the columns in the resultset rows.  This is needed, for example,
     * for results of JOIN queries where the resultset row includes columns from two or
     * more tables.
     *
     * @param array $row The row returned by PDOStatement->fetch(PDO::FETCH_NUM)
     * @param int $startcol 0-based offset column which indicates which resultset column to start with.
     * @param boolean $rehydrate Whether this object is being re-hydrated from the database.
     * @return int             next starting column
     * @throws PropelException - Any caught Exception will be rewrapped as a PropelException.
     */
    public function hydrate($row, $startcol = 0, $rehydrate = false)
    {
        try {

            $this->id = ($row[$startcol + 0] !== null) ? (int) $row[$startcol + 0] : null;
            $this->username = ($row[$startcol + 1] !== null) ? (string) $row[$startcol + 1] : null;
            $this->password = ($row[$startcol + 2] !== null) ? (string) $row[$startcol + 2] : null;
            $this->timestamp = ($row[$startcol + 3] !== null) ? (string) $row[$startcol + 3] : null;
            $this->ip_add = ($row[$startcol + 4] !== null) ? (string) $row[$startcol + 4] : null;
            $this->status = ($row[$startcol + 5] !== null) ? (int) $row[$startcol + 5] : null;
            $this->email = ($row[$startcol + 6] !== null) ? (string) $row[$startcol + 6] : null;
            $this->role = ($row[$startcol + 7] !== null) ? (string) $row[$startcol + 7] : null;
            $this->team_role = ($row[$startcol + 8] !== null) ? (string) $row[$startcol + 8] : null;
            $this->key = ($row[$startcol + 9] !== null) ? (string) $row[$startcol + 9] : null;
            $this->created_by = ($row[$startcol + 10] !== null) ? (int) $row[$startcol + 10] : null;
            $this->last_updated_by = ($row[$startcol + 11] !== null) ? (int) $row[$startcol + 11] : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }
            $this->postHydrate($row, $startcol, $rehydrate);

            return $startcol + 12; // 12 = EmpAccPeer::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException("Error populating EmpAcc object", $e);
        }
    }

    /**
     * Checks and repairs the internal consistency of the object.
     *
     * This method is executed after an already-instantiated object is re-hydrated
     * from the database.  It exists to check any foreign keys to make sure that
     * the objects related to the current object are correct based on foreign key.
     *
     * You can override this method in the stub class, but you should always invoke
     * the base method from the overridden method (i.e. parent::ensureConsistency()),
     * in case your model changes.
     *
     * @throws PropelException
     */
    public function ensureConsistency()
    {

    } // ensureConsistency

    /**
     * Reloads this object from datastore based on primary key and (optionally) resets all associated objects.
     *
     * This will only work if the object has been saved and has a valid primary key set.
     *
     * @param boolean $deep (optional) Whether to also de-associated any related objects.
     * @param PropelPDO $con (optional) The PropelPDO connection to use.
     * @return void
     * @throws PropelException - if this object is deleted, unsaved or doesn't have pk match in db
     */
    public function reload($deep = false, PropelPDO $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("Cannot reload a deleted object.");
        }

        if ($this->isNew()) {
            throw new PropelException("Cannot reload an unsaved object.");
        }

        if ($con === null) {
            $con = Propel::getConnection(EmpAccPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $stmt = EmpAccPeer::doSelectStmt($this->buildPkeyCriteria(), $con);
        $row = $stmt->fetch(PDO::FETCH_NUM);
        $stmt->closeCursor();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->collEmpRequestsRelatedByEmpAccId = null;

            $this->collEmpRequestsRelatedByAdminId = null;

            $this->collEmpProfiles = null;

            $this->collEmpTimes = null;

            $this->collEmpCapabilitiess = null;

            $this->collListEventss = null;

            $this->collEventNotess = null;

            $this->collEventTaggedPersonss = null;

        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param PropelPDO $con
     * @return void
     * @throws PropelException
     * @throws Exception
     * @see        BaseObject::setDeleted()
     * @see        BaseObject::isDeleted()
     */
    public function delete(PropelPDO $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getConnection(EmpAccPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        try {
            $deleteQuery = EmpAccQuery::create()
                ->filterByPrimaryKey($this->getPrimaryKey());
            $ret = $this->preDelete($con);
            if ($ret) {
                $deleteQuery->delete($con);
                $this->postDelete($con);
                $con->commit();
                $this->setDeleted(true);
            } else {
                $con->commit();
            }
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Persists this object to the database.
     *
     * If the object is new, it inserts it; otherwise an update is performed.
     * All modified related objects will also be persisted in the doSave()
     * method.  This method wraps all precipitate database operations in a
     * single transaction.
     *
     * @param PropelPDO $con
     * @return int             The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws PropelException
     * @throws Exception
     * @see        doSave()
     */
    public function save(PropelPDO $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("You cannot save an object that has been deleted.");
        }

        if ($con === null) {
            $con = Propel::getConnection(EmpAccPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        $isInsert = $this->isNew();
        try {
            $ret = $this->preSave($con);
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
            } else {
                $ret = $ret && $this->preUpdate($con);
            }
            if ($ret) {
                $affectedRows = $this->doSave($con);
                if ($isInsert) {
                    $this->postInsert($con);
                } else {
                    $this->postUpdate($con);
                }
                $this->postSave($con);
                EmpAccPeer::addInstanceToPool($this);
            } else {
                $affectedRows = 0;
            }
            $con->commit();

            return $affectedRows;
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Performs the work of inserting or updating the row in the database.
     *
     * If the object is new, it inserts it; otherwise an update is performed.
     * All related objects are also updated in this method.
     *
     * @param PropelPDO $con
     * @return int             The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws PropelException
     * @see        save()
     */
    protected function doSave(PropelPDO $con)
    {
        $affectedRows = 0; // initialize var to track total num of affected rows
        if (!$this->alreadyInSave) {
            $this->alreadyInSave = true;

            if ($this->isNew() || $this->isModified()) {
                // persist changes
                if ($this->isNew()) {
                    $this->doInsert($con);
                } else {
                    $this->doUpdate($con);
                }
                $affectedRows += 1;
                $this->resetModified();
            }

            if ($this->empRequestsRelatedByEmpAccIdScheduledForDeletion !== null) {
                if (!$this->empRequestsRelatedByEmpAccIdScheduledForDeletion->isEmpty()) {
                    foreach ($this->empRequestsRelatedByEmpAccIdScheduledForDeletion as $empRequestRelatedByEmpAccId) {
                        // need to save related object because we set the relation to null
                        $empRequestRelatedByEmpAccId->save($con);
                    }
                    $this->empRequestsRelatedByEmpAccIdScheduledForDeletion = null;
                }
            }

            if ($this->collEmpRequestsRelatedByEmpAccId !== null) {
                foreach ($this->collEmpRequestsRelatedByEmpAccId as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->empRequestsRelatedByAdminIdScheduledForDeletion !== null) {
                if (!$this->empRequestsRelatedByAdminIdScheduledForDeletion->isEmpty()) {
                    foreach ($this->empRequestsRelatedByAdminIdScheduledForDeletion as $empRequestRelatedByAdminId) {
                        // need to save related object because we set the relation to null
                        $empRequestRelatedByAdminId->save($con);
                    }
                    $this->empRequestsRelatedByAdminIdScheduledForDeletion = null;
                }
            }

            if ($this->collEmpRequestsRelatedByAdminId !== null) {
                foreach ($this->collEmpRequestsRelatedByAdminId as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->empProfilesScheduledForDeletion !== null) {
                if (!$this->empProfilesScheduledForDeletion->isEmpty()) {
                    EmpProfileQuery::create()
                        ->filterByPrimaryKeys($this->empProfilesScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->empProfilesScheduledForDeletion = null;
                }
            }

            if ($this->collEmpProfiles !== null) {
                foreach ($this->collEmpProfiles as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->empTimesScheduledForDeletion !== null) {
                if (!$this->empTimesScheduledForDeletion->isEmpty()) {
                    EmpTimeQuery::create()
                        ->filterByPrimaryKeys($this->empTimesScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->empTimesScheduledForDeletion = null;
                }
            }

            if ($this->collEmpTimes !== null) {
                foreach ($this->collEmpTimes as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->empCapabilitiessScheduledForDeletion !== null) {
                if (!$this->empCapabilitiessScheduledForDeletion->isEmpty()) {
                    foreach ($this->empCapabilitiessScheduledForDeletion as $empCapabilities) {
                        // need to save related object because we set the relation to null
                        $empCapabilities->save($con);
                    }
                    $this->empCapabilitiessScheduledForDeletion = null;
                }
            }

            if ($this->collEmpCapabilitiess !== null) {
                foreach ($this->collEmpCapabilitiess as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->listEventssScheduledForDeletion !== null) {
                if (!$this->listEventssScheduledForDeletion->isEmpty()) {
                    ListEventsQuery::create()
                        ->filterByPrimaryKeys($this->listEventssScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->listEventssScheduledForDeletion = null;
                }
            }

            if ($this->collListEventss !== null) {
                foreach ($this->collListEventss as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->eventNotessScheduledForDeletion !== null) {
                if (!$this->eventNotessScheduledForDeletion->isEmpty()) {
                    EventNotesQuery::create()
                        ->filterByPrimaryKeys($this->eventNotessScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->eventNotessScheduledForDeletion = null;
                }
            }

            if ($this->collEventNotess !== null) {
                foreach ($this->collEventNotess as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->eventTaggedPersonssScheduledForDeletion !== null) {
                if (!$this->eventTaggedPersonssScheduledForDeletion->isEmpty()) {
                    EventTaggedPersonsQuery::create()
                        ->filterByPrimaryKeys($this->eventTaggedPersonssScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->eventTaggedPersonssScheduledForDeletion = null;
                }
            }

            if ($this->collEventTaggedPersonss !== null) {
                foreach ($this->collEventTaggedPersonss as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            $this->alreadyInSave = false;

        }

        return $affectedRows;
    } // doSave()

    /**
     * Insert the row in the database.
     *
     * @param PropelPDO $con
     *
     * @throws PropelException
     * @see        doSave()
     */
    protected function doInsert(PropelPDO $con)
    {
        $modifiedColumns = array();
        $index = 0;

        $this->modifiedColumns[] = EmpAccPeer::ID;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . EmpAccPeer::ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(EmpAccPeer::ID)) {
            $modifiedColumns[':p' . $index++]  = '`id`';
        }
        if ($this->isColumnModified(EmpAccPeer::USERNAME)) {
            $modifiedColumns[':p' . $index++]  = '`username`';
        }
        if ($this->isColumnModified(EmpAccPeer::PASSWORD)) {
            $modifiedColumns[':p' . $index++]  = '`password`';
        }
        if ($this->isColumnModified(EmpAccPeer::TIMESTAMP)) {
            $modifiedColumns[':p' . $index++]  = '`timestamp`';
        }
        if ($this->isColumnModified(EmpAccPeer::IP_ADD)) {
            $modifiedColumns[':p' . $index++]  = '`ip_add`';
        }
        if ($this->isColumnModified(EmpAccPeer::STATUS)) {
            $modifiedColumns[':p' . $index++]  = '`status`';
        }
        if ($this->isColumnModified(EmpAccPeer::EMAIL)) {
            $modifiedColumns[':p' . $index++]  = '`email`';
        }
        if ($this->isColumnModified(EmpAccPeer::ROLE)) {
            $modifiedColumns[':p' . $index++]  = '`role`';
        }
        if ($this->isColumnModified(EmpAccPeer::TEAM_ROLE)) {
            $modifiedColumns[':p' . $index++]  = '`team_role`';
        }
        if ($this->isColumnModified(EmpAccPeer::KEY)) {
            $modifiedColumns[':p' . $index++]  = '`key`';
        }
        if ($this->isColumnModified(EmpAccPeer::CREATED_BY)) {
            $modifiedColumns[':p' . $index++]  = '`created_by`';
        }
        if ($this->isColumnModified(EmpAccPeer::LAST_UPDATED_BY)) {
            $modifiedColumns[':p' . $index++]  = '`last_updated_by`';
        }

        $sql = sprintf(
            'INSERT INTO `emp_acc` (%s) VALUES (%s)',
            implode(', ', $modifiedColumns),
            implode(', ', array_keys($modifiedColumns))
        );

        try {
            $stmt = $con->prepare($sql);
            foreach ($modifiedColumns as $identifier => $columnName) {
                switch ($columnName) {
                    case '`id`':
                        $stmt->bindValue($identifier, $this->id, PDO::PARAM_INT);
                        break;
                    case '`username`':
                        $stmt->bindValue($identifier, $this->username, PDO::PARAM_STR);
                        break;
                    case '`password`':
                        $stmt->bindValue($identifier, $this->password, PDO::PARAM_STR);
                        break;
                    case '`timestamp`':
                        $stmt->bindValue($identifier, $this->timestamp, PDO::PARAM_STR);
                        break;
                    case '`ip_add`':
                        $stmt->bindValue($identifier, $this->ip_add, PDO::PARAM_STR);
                        break;
                    case '`status`':
                        $stmt->bindValue($identifier, $this->status, PDO::PARAM_INT);
                        break;
                    case '`email`':
                        $stmt->bindValue($identifier, $this->email, PDO::PARAM_STR);
                        break;
                    case '`role`':
                        $stmt->bindValue($identifier, $this->role, PDO::PARAM_STR);
                        break;
                    case '`team_role`':
                        $stmt->bindValue($identifier, $this->team_role, PDO::PARAM_STR);
                        break;
                    case '`key`':
                        $stmt->bindValue($identifier, $this->key, PDO::PARAM_STR);
                        break;
                    case '`created_by`':
                        $stmt->bindValue($identifier, $this->created_by, PDO::PARAM_INT);
                        break;
                    case '`last_updated_by`':
                        $stmt->bindValue($identifier, $this->last_updated_by, PDO::PARAM_INT);
                        break;
                }
            }
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute INSERT statement [%s]', $sql), $e);
        }

        try {
            $pk = $con->lastInsertId();
        } catch (Exception $e) {
            throw new PropelException('Unable to get autoincrement id.', $e);
        }
        $this->setId($pk);

        $this->setNew(false);
    }

    /**
     * Update the row in the database.
     *
     * @param PropelPDO $con
     *
     * @see        doSave()
     */
    protected function doUpdate(PropelPDO $con)
    {
        $selectCriteria = $this->buildPkeyCriteria();
        $valuesCriteria = $this->buildCriteria();
        BasePeer::doUpdate($selectCriteria, $valuesCriteria, $con);
    }

    /**
     * Array of ValidationFailed objects.
     * @var        array ValidationFailed[]
     */
    protected $validationFailures = array();

    /**
     * Gets any ValidationFailed objects that resulted from last call to validate().
     *
     *
     * @return array ValidationFailed[]
     * @see        validate()
     */
    public function getValidationFailures()
    {
        return $this->validationFailures;
    }

    /**
     * Validates the objects modified field values and all objects related to this table.
     *
     * If $columns is either a column name or an array of column names
     * only those columns are validated.
     *
     * @param mixed $columns Column name or an array of column names.
     * @return boolean Whether all columns pass validation.
     * @see        doValidate()
     * @see        getValidationFailures()
     */
    public function validate($columns = null)
    {
        $res = $this->doValidate($columns);
        if ($res === true) {
            $this->validationFailures = array();

            return true;
        }

        $this->validationFailures = $res;

        return false;
    }

    /**
     * This function performs the validation work for complex object models.
     *
     * In addition to checking the current object, all related objects will
     * also be validated.  If all pass then <code>true</code> is returned; otherwise
     * an aggregated array of ValidationFailed objects will be returned.
     *
     * @param array $columns Array of column names to validate.
     * @return mixed <code>true</code> if all validations pass; array of <code>ValidationFailed</code> objects otherwise.
     */
    protected function doValidate($columns = null)
    {
        if (!$this->alreadyInValidation) {
            $this->alreadyInValidation = true;
            $retval = null;

            $failureMap = array();


            if (($retval = EmpAccPeer::doValidate($this, $columns)) !== true) {
                $failureMap = array_merge($failureMap, $retval);
            }


                if ($this->collEmpRequestsRelatedByEmpAccId !== null) {
                    foreach ($this->collEmpRequestsRelatedByEmpAccId as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collEmpRequestsRelatedByAdminId !== null) {
                    foreach ($this->collEmpRequestsRelatedByAdminId as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collEmpProfiles !== null) {
                    foreach ($this->collEmpProfiles as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collEmpTimes !== null) {
                    foreach ($this->collEmpTimes as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collEmpCapabilitiess !== null) {
                    foreach ($this->collEmpCapabilitiess as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collListEventss !== null) {
                    foreach ($this->collListEventss as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collEventNotess !== null) {
                    foreach ($this->collEventNotess as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collEventTaggedPersonss !== null) {
                    foreach ($this->collEventTaggedPersonss as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }


            $this->alreadyInValidation = false;
        }

        return (!empty($failureMap) ? $failureMap : true);
    }

    /**
     * Retrieves a field from the object by name passed in as a string.
     *
     * @param string $name name
     * @param string $type The type of fieldname the $name is of:
     *               one of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME
     *               BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM.
     *               Defaults to BasePeer::TYPE_PHPNAME
     * @return mixed Value of field.
     */
    public function getByName($name, $type = BasePeer::TYPE_PHPNAME)
    {
        $pos = EmpAccPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
        $field = $this->getByPosition($pos);

        return $field;
    }

    /**
     * Retrieves a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param int $pos position in xml schema
     * @return mixed Value of field at $pos
     */
    public function getByPosition($pos)
    {
        switch ($pos) {
            case 0:
                return $this->getId();
                break;
            case 1:
                return $this->getUsername();
                break;
            case 2:
                return $this->getPassword();
                break;
            case 3:
                return $this->getTimestamp();
                break;
            case 4:
                return $this->getIpAdd();
                break;
            case 5:
                return $this->getStatus();
                break;
            case 6:
                return $this->getEmail();
                break;
            case 7:
                return $this->getRole();
                break;
            case 8:
                return $this->getTeamRole();
                break;
            case 9:
                return $this->getKey();
                break;
            case 10:
                return $this->getCreatedBy();
                break;
            case 11:
                return $this->getLastUpdatedBy();
                break;
            default:
                return null;
                break;
        } // switch()
    }

    /**
     * Exports the object as an array.
     *
     * You can specify the key type of the array by passing one of the class
     * type constants.
     *
     * @param     string  $keyType (optional) One of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME,
     *                    BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM.
     *                    Defaults to BasePeer::TYPE_PHPNAME.
     * @param     boolean $includeLazyLoadColumns (optional) Whether to include lazy loaded columns. Defaults to true.
     * @param     array $alreadyDumpedObjects List of objects to skip to avoid recursion
     * @param     boolean $includeForeignObjects (optional) Whether to include hydrated related objects. Default to FALSE.
     *
     * @return array an associative array containing the field names (as keys) and field values
     */
    public function toArray($keyType = BasePeer::TYPE_PHPNAME, $includeLazyLoadColumns = true, $alreadyDumpedObjects = array(), $includeForeignObjects = false)
    {
        if (isset($alreadyDumpedObjects['EmpAcc'][$this->getPrimaryKey()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['EmpAcc'][$this->getPrimaryKey()] = true;
        $keys = EmpAccPeer::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getUsername(),
            $keys[2] => $this->getPassword(),
            $keys[3] => $this->getTimestamp(),
            $keys[4] => $this->getIpAdd(),
            $keys[5] => $this->getStatus(),
            $keys[6] => $this->getEmail(),
            $keys[7] => $this->getRole(),
            $keys[8] => $this->getTeamRole(),
            $keys[9] => $this->getKey(),
            $keys[10] => $this->getCreatedBy(),
            $keys[11] => $this->getLastUpdatedBy(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->collEmpRequestsRelatedByEmpAccId) {
                $result['EmpRequestsRelatedByEmpAccId'] = $this->collEmpRequestsRelatedByEmpAccId->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collEmpRequestsRelatedByAdminId) {
                $result['EmpRequestsRelatedByAdminId'] = $this->collEmpRequestsRelatedByAdminId->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collEmpProfiles) {
                $result['EmpProfiles'] = $this->collEmpProfiles->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collEmpTimes) {
                $result['EmpTimes'] = $this->collEmpTimes->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collEmpCapabilitiess) {
                $result['EmpCapabilitiess'] = $this->collEmpCapabilitiess->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collListEventss) {
                $result['ListEventss'] = $this->collListEventss->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collEventNotess) {
                $result['EventNotess'] = $this->collEventNotess->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collEventTaggedPersonss) {
                $result['EventTaggedPersonss'] = $this->collEventTaggedPersonss->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
        }

        return $result;
    }

    /**
     * Sets a field from the object by name passed in as a string.
     *
     * @param string $name peer name
     * @param mixed $value field value
     * @param string $type The type of fieldname the $name is of:
     *                     one of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME
     *                     BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM.
     *                     Defaults to BasePeer::TYPE_PHPNAME
     * @return void
     */
    public function setByName($name, $value, $type = BasePeer::TYPE_PHPNAME)
    {
        $pos = EmpAccPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);

        $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param int $pos position in xml schema
     * @param mixed $value field value
     * @return void
     */
    public function setByPosition($pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setId($value);
                break;
            case 1:
                $this->setUsername($value);
                break;
            case 2:
                $this->setPassword($value);
                break;
            case 3:
                $this->setTimestamp($value);
                break;
            case 4:
                $this->setIpAdd($value);
                break;
            case 5:
                $this->setStatus($value);
                break;
            case 6:
                $this->setEmail($value);
                break;
            case 7:
                $this->setRole($value);
                break;
            case 8:
                $this->setTeamRole($value);
                break;
            case 9:
                $this->setKey($value);
                break;
            case 10:
                $this->setCreatedBy($value);
                break;
            case 11:
                $this->setLastUpdatedBy($value);
                break;
        } // switch()
    }

    /**
     * Populates the object using an array.
     *
     * This is particularly useful when populating an object from one of the
     * request arrays (e.g. $_POST).  This method goes through the column
     * names, checking to see whether a matching key exists in populated
     * array. If so the setByName() method is called for that column.
     *
     * You can specify the key type of the array by additionally passing one
     * of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME,
     * BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM.
     * The default key type is the column's BasePeer::TYPE_PHPNAME
     *
     * @param array  $arr     An array to populate the object from.
     * @param string $keyType The type of keys the array uses.
     * @return void
     */
    public function fromArray($arr, $keyType = BasePeer::TYPE_PHPNAME)
    {
        $keys = EmpAccPeer::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
        if (array_key_exists($keys[1], $arr)) $this->setUsername($arr[$keys[1]]);
        if (array_key_exists($keys[2], $arr)) $this->setPassword($arr[$keys[2]]);
        if (array_key_exists($keys[3], $arr)) $this->setTimestamp($arr[$keys[3]]);
        if (array_key_exists($keys[4], $arr)) $this->setIpAdd($arr[$keys[4]]);
        if (array_key_exists($keys[5], $arr)) $this->setStatus($arr[$keys[5]]);
        if (array_key_exists($keys[6], $arr)) $this->setEmail($arr[$keys[6]]);
        if (array_key_exists($keys[7], $arr)) $this->setRole($arr[$keys[7]]);
        if (array_key_exists($keys[8], $arr)) $this->setTeamRole($arr[$keys[8]]);
        if (array_key_exists($keys[9], $arr)) $this->setKey($arr[$keys[9]]);
        if (array_key_exists($keys[10], $arr)) $this->setCreatedBy($arr[$keys[10]]);
        if (array_key_exists($keys[11], $arr)) $this->setLastUpdatedBy($arr[$keys[11]]);
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(EmpAccPeer::DATABASE_NAME);

        if ($this->isColumnModified(EmpAccPeer::ID)) $criteria->add(EmpAccPeer::ID, $this->id);
        if ($this->isColumnModified(EmpAccPeer::USERNAME)) $criteria->add(EmpAccPeer::USERNAME, $this->username);
        if ($this->isColumnModified(EmpAccPeer::PASSWORD)) $criteria->add(EmpAccPeer::PASSWORD, $this->password);
        if ($this->isColumnModified(EmpAccPeer::TIMESTAMP)) $criteria->add(EmpAccPeer::TIMESTAMP, $this->timestamp);
        if ($this->isColumnModified(EmpAccPeer::IP_ADD)) $criteria->add(EmpAccPeer::IP_ADD, $this->ip_add);
        if ($this->isColumnModified(EmpAccPeer::STATUS)) $criteria->add(EmpAccPeer::STATUS, $this->status);
        if ($this->isColumnModified(EmpAccPeer::EMAIL)) $criteria->add(EmpAccPeer::EMAIL, $this->email);
        if ($this->isColumnModified(EmpAccPeer::ROLE)) $criteria->add(EmpAccPeer::ROLE, $this->role);
        if ($this->isColumnModified(EmpAccPeer::TEAM_ROLE)) $criteria->add(EmpAccPeer::TEAM_ROLE, $this->team_role);
        if ($this->isColumnModified(EmpAccPeer::KEY)) $criteria->add(EmpAccPeer::KEY, $this->key);
        if ($this->isColumnModified(EmpAccPeer::CREATED_BY)) $criteria->add(EmpAccPeer::CREATED_BY, $this->created_by);
        if ($this->isColumnModified(EmpAccPeer::LAST_UPDATED_BY)) $criteria->add(EmpAccPeer::LAST_UPDATED_BY, $this->last_updated_by);

        return $criteria;
    }

    /**
     * Builds a Criteria object containing the primary key for this object.
     *
     * Unlike buildCriteria() this method includes the primary key values regardless
     * of whether or not they have been modified.
     *
     * @return Criteria The Criteria object containing value(s) for primary key(s).
     */
    public function buildPkeyCriteria()
    {
        $criteria = new Criteria(EmpAccPeer::DATABASE_NAME);
        $criteria->add(EmpAccPeer::ID, $this->id);

        return $criteria;
    }

    /**
     * Returns the primary key for this object (row).
     * @return int
     */
    public function getPrimaryKey()
    {
        return $this->getId();
    }

    /**
     * Generic method to set the primary key (id column).
     *
     * @param  int $key Primary key.
     * @return void
     */
    public function setPrimaryKey($key)
    {
        $this->setId($key);
    }

    /**
     * Returns true if the primary key for this object is null.
     * @return boolean
     */
    public function isPrimaryKeyNull()
    {

        return null === $this->getId();
    }

    /**
     * Sets contents of passed object to values from current object.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param object $copyObj An object of EmpAcc (or compatible) type.
     * @param boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setUsername($this->getUsername());
        $copyObj->setPassword($this->getPassword());
        $copyObj->setTimestamp($this->getTimestamp());
        $copyObj->setIpAdd($this->getIpAdd());
        $copyObj->setStatus($this->getStatus());
        $copyObj->setEmail($this->getEmail());
        $copyObj->setRole($this->getRole());
        $copyObj->setTeamRole($this->getTeamRole());
        $copyObj->setKey($this->getKey());
        $copyObj->setCreatedBy($this->getCreatedBy());
        $copyObj->setLastUpdatedBy($this->getLastUpdatedBy());

        if ($deepCopy && !$this->startCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);
            // store object hash to prevent cycle
            $this->startCopy = true;

            foreach ($this->getEmpRequestsRelatedByEmpAccId() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addEmpRequestRelatedByEmpAccId($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getEmpRequestsRelatedByAdminId() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addEmpRequestRelatedByAdminId($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getEmpProfiles() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addEmpProfile($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getEmpTimes() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addEmpTime($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getEmpCapabilitiess() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addEmpCapabilities($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getListEventss() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addListEvents($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getEventNotess() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addEventNotes($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getEventTaggedPersonss() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addEventTaggedPersons($relObj->copy($deepCopy));
                }
            }

            //unflag object copy
            $this->startCopy = false;
        } // if ($deepCopy)

        if ($makeNew) {
            $copyObj->setNew(true);
            $copyObj->setId(NULL); // this is a auto-increment column, so set to default value
        }
    }

    /**
     * Makes a copy of this object that will be inserted as a new row in table when saved.
     * It creates a new object filling in the simple attributes, but skipping any primary
     * keys that are defined for the table.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @return EmpAcc Clone of current object.
     * @throws PropelException
     */
    public function copy($deepCopy = false)
    {
        // we use get_class(), because this might be a subclass
        $clazz = get_class($this);
        $copyObj = new $clazz();
        $this->copyInto($copyObj, $deepCopy);

        return $copyObj;
    }

    /**
     * Returns a peer instance associated with this om.
     *
     * Since Peer classes are not to have any instance attributes, this method returns the
     * same instance for all member of this class. The method could therefore
     * be static, but this would prevent one from overriding the behavior.
     *
     * @return EmpAccPeer
     */
    public function getPeer()
    {
        if (self::$peer === null) {
            self::$peer = new EmpAccPeer();
        }

        return self::$peer;
    }


    /**
     * Initializes a collection based on the name of a relation.
     * Avoids crafting an 'init[$relationName]s' method name
     * that wouldn't work when StandardEnglishPluralizer is used.
     *
     * @param string $relationName The name of the relation to initialize
     * @return void
     */
    public function initRelation($relationName)
    {
        if ('EmpRequestRelatedByEmpAccId' == $relationName) {
            $this->initEmpRequestsRelatedByEmpAccId();
        }
        if ('EmpRequestRelatedByAdminId' == $relationName) {
            $this->initEmpRequestsRelatedByAdminId();
        }
        if ('EmpProfile' == $relationName) {
            $this->initEmpProfiles();
        }
        if ('EmpTime' == $relationName) {
            $this->initEmpTimes();
        }
        if ('EmpCapabilities' == $relationName) {
            $this->initEmpCapabilitiess();
        }
        if ('ListEvents' == $relationName) {
            $this->initListEventss();
        }
        if ('EventNotes' == $relationName) {
            $this->initEventNotess();
        }
        if ('EventTaggedPersons' == $relationName) {
            $this->initEventTaggedPersonss();
        }
    }

    /**
     * Clears out the collEmpRequestsRelatedByEmpAccId collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return EmpAcc The current object (for fluent API support)
     * @see        addEmpRequestsRelatedByEmpAccId()
     */
    public function clearEmpRequestsRelatedByEmpAccId()
    {
        $this->collEmpRequestsRelatedByEmpAccId = null; // important to set this to null since that means it is uninitialized
        $this->collEmpRequestsRelatedByEmpAccIdPartial = null;

        return $this;
    }

    /**
     * reset is the collEmpRequestsRelatedByEmpAccId collection loaded partially
     *
     * @return void
     */
    public function resetPartialEmpRequestsRelatedByEmpAccId($v = true)
    {
        $this->collEmpRequestsRelatedByEmpAccIdPartial = $v;
    }

    /**
     * Initializes the collEmpRequestsRelatedByEmpAccId collection.
     *
     * By default this just sets the collEmpRequestsRelatedByEmpAccId collection to an empty array (like clearcollEmpRequestsRelatedByEmpAccId());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initEmpRequestsRelatedByEmpAccId($overrideExisting = true)
    {
        if (null !== $this->collEmpRequestsRelatedByEmpAccId && !$overrideExisting) {
            return;
        }
        $this->collEmpRequestsRelatedByEmpAccId = new PropelObjectCollection();
        $this->collEmpRequestsRelatedByEmpAccId->setModel('EmpRequest');
    }

    /**
     * Gets an array of EmpRequest objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this EmpAcc is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|EmpRequest[] List of EmpRequest objects
     * @throws PropelException
     */
    public function getEmpRequestsRelatedByEmpAccId($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collEmpRequestsRelatedByEmpAccIdPartial && !$this->isNew();
        if (null === $this->collEmpRequestsRelatedByEmpAccId || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collEmpRequestsRelatedByEmpAccId) {
                // return empty collection
                $this->initEmpRequestsRelatedByEmpAccId();
            } else {
                $collEmpRequestsRelatedByEmpAccId = EmpRequestQuery::create(null, $criteria)
                    ->filterByEmpAccRelatedByEmpAccId($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collEmpRequestsRelatedByEmpAccIdPartial && count($collEmpRequestsRelatedByEmpAccId)) {
                      $this->initEmpRequestsRelatedByEmpAccId(false);

                      foreach ($collEmpRequestsRelatedByEmpAccId as $obj) {
                        if (false == $this->collEmpRequestsRelatedByEmpAccId->contains($obj)) {
                          $this->collEmpRequestsRelatedByEmpAccId->append($obj);
                        }
                      }

                      $this->collEmpRequestsRelatedByEmpAccIdPartial = true;
                    }

                    $collEmpRequestsRelatedByEmpAccId->getInternalIterator()->rewind();

                    return $collEmpRequestsRelatedByEmpAccId;
                }

                if ($partial && $this->collEmpRequestsRelatedByEmpAccId) {
                    foreach ($this->collEmpRequestsRelatedByEmpAccId as $obj) {
                        if ($obj->isNew()) {
                            $collEmpRequestsRelatedByEmpAccId[] = $obj;
                        }
                    }
                }

                $this->collEmpRequestsRelatedByEmpAccId = $collEmpRequestsRelatedByEmpAccId;
                $this->collEmpRequestsRelatedByEmpAccIdPartial = false;
            }
        }

        return $this->collEmpRequestsRelatedByEmpAccId;
    }

    /**
     * Sets a collection of EmpRequestRelatedByEmpAccId objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $empRequestsRelatedByEmpAccId A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return EmpAcc The current object (for fluent API support)
     */
    public function setEmpRequestsRelatedByEmpAccId(PropelCollection $empRequestsRelatedByEmpAccId, PropelPDO $con = null)
    {
        $empRequestsRelatedByEmpAccIdToDelete = $this->getEmpRequestsRelatedByEmpAccId(new Criteria(), $con)->diff($empRequestsRelatedByEmpAccId);


        $this->empRequestsRelatedByEmpAccIdScheduledForDeletion = $empRequestsRelatedByEmpAccIdToDelete;

        foreach ($empRequestsRelatedByEmpAccIdToDelete as $empRequestRelatedByEmpAccIdRemoved) {
            $empRequestRelatedByEmpAccIdRemoved->setEmpAccRelatedByEmpAccId(null);
        }

        $this->collEmpRequestsRelatedByEmpAccId = null;
        foreach ($empRequestsRelatedByEmpAccId as $empRequestRelatedByEmpAccId) {
            $this->addEmpRequestRelatedByEmpAccId($empRequestRelatedByEmpAccId);
        }

        $this->collEmpRequestsRelatedByEmpAccId = $empRequestsRelatedByEmpAccId;
        $this->collEmpRequestsRelatedByEmpAccIdPartial = false;

        return $this;
    }

    /**
     * Returns the number of related EmpRequest objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related EmpRequest objects.
     * @throws PropelException
     */
    public function countEmpRequestsRelatedByEmpAccId(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collEmpRequestsRelatedByEmpAccIdPartial && !$this->isNew();
        if (null === $this->collEmpRequestsRelatedByEmpAccId || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collEmpRequestsRelatedByEmpAccId) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getEmpRequestsRelatedByEmpAccId());
            }
            $query = EmpRequestQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByEmpAccRelatedByEmpAccId($this)
                ->count($con);
        }

        return count($this->collEmpRequestsRelatedByEmpAccId);
    }

    /**
     * Method called to associate a EmpRequest object to this object
     * through the EmpRequest foreign key attribute.
     *
     * @param    EmpRequest $l EmpRequest
     * @return EmpAcc The current object (for fluent API support)
     */
    public function addEmpRequestRelatedByEmpAccId(EmpRequest $l)
    {
        if ($this->collEmpRequestsRelatedByEmpAccId === null) {
            $this->initEmpRequestsRelatedByEmpAccId();
            $this->collEmpRequestsRelatedByEmpAccIdPartial = true;
        }

        if (!in_array($l, $this->collEmpRequestsRelatedByEmpAccId->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddEmpRequestRelatedByEmpAccId($l);

            if ($this->empRequestsRelatedByEmpAccIdScheduledForDeletion and $this->empRequestsRelatedByEmpAccIdScheduledForDeletion->contains($l)) {
                $this->empRequestsRelatedByEmpAccIdScheduledForDeletion->remove($this->empRequestsRelatedByEmpAccIdScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	EmpRequestRelatedByEmpAccId $empRequestRelatedByEmpAccId The empRequestRelatedByEmpAccId object to add.
     */
    protected function doAddEmpRequestRelatedByEmpAccId($empRequestRelatedByEmpAccId)
    {
        $this->collEmpRequestsRelatedByEmpAccId[]= $empRequestRelatedByEmpAccId;
        $empRequestRelatedByEmpAccId->setEmpAccRelatedByEmpAccId($this);
    }

    /**
     * @param	EmpRequestRelatedByEmpAccId $empRequestRelatedByEmpAccId The empRequestRelatedByEmpAccId object to remove.
     * @return EmpAcc The current object (for fluent API support)
     */
    public function removeEmpRequestRelatedByEmpAccId($empRequestRelatedByEmpAccId)
    {
        if ($this->getEmpRequestsRelatedByEmpAccId()->contains($empRequestRelatedByEmpAccId)) {
            $this->collEmpRequestsRelatedByEmpAccId->remove($this->collEmpRequestsRelatedByEmpAccId->search($empRequestRelatedByEmpAccId));
            if (null === $this->empRequestsRelatedByEmpAccIdScheduledForDeletion) {
                $this->empRequestsRelatedByEmpAccIdScheduledForDeletion = clone $this->collEmpRequestsRelatedByEmpAccId;
                $this->empRequestsRelatedByEmpAccIdScheduledForDeletion->clear();
            }
            $this->empRequestsRelatedByEmpAccIdScheduledForDeletion[]= $empRequestRelatedByEmpAccId;
            $empRequestRelatedByEmpAccId->setEmpAccRelatedByEmpAccId(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this EmpAcc is new, it will return
     * an empty collection; or if this EmpAcc has previously
     * been saved, it will retrieve related EmpRequestsRelatedByEmpAccId from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in EmpAcc.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|EmpRequest[] List of EmpRequest objects
     */
    public function getEmpRequestsRelatedByEmpAccIdJoinListRequestType($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = EmpRequestQuery::create(null, $criteria);
        $query->joinWith('ListRequestType', $join_behavior);

        return $this->getEmpRequestsRelatedByEmpAccId($query, $con);
    }

    /**
     * Clears out the collEmpRequestsRelatedByAdminId collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return EmpAcc The current object (for fluent API support)
     * @see        addEmpRequestsRelatedByAdminId()
     */
    public function clearEmpRequestsRelatedByAdminId()
    {
        $this->collEmpRequestsRelatedByAdminId = null; // important to set this to null since that means it is uninitialized
        $this->collEmpRequestsRelatedByAdminIdPartial = null;

        return $this;
    }

    /**
     * reset is the collEmpRequestsRelatedByAdminId collection loaded partially
     *
     * @return void
     */
    public function resetPartialEmpRequestsRelatedByAdminId($v = true)
    {
        $this->collEmpRequestsRelatedByAdminIdPartial = $v;
    }

    /**
     * Initializes the collEmpRequestsRelatedByAdminId collection.
     *
     * By default this just sets the collEmpRequestsRelatedByAdminId collection to an empty array (like clearcollEmpRequestsRelatedByAdminId());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initEmpRequestsRelatedByAdminId($overrideExisting = true)
    {
        if (null !== $this->collEmpRequestsRelatedByAdminId && !$overrideExisting) {
            return;
        }
        $this->collEmpRequestsRelatedByAdminId = new PropelObjectCollection();
        $this->collEmpRequestsRelatedByAdminId->setModel('EmpRequest');
    }

    /**
     * Gets an array of EmpRequest objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this EmpAcc is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|EmpRequest[] List of EmpRequest objects
     * @throws PropelException
     */
    public function getEmpRequestsRelatedByAdminId($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collEmpRequestsRelatedByAdminIdPartial && !$this->isNew();
        if (null === $this->collEmpRequestsRelatedByAdminId || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collEmpRequestsRelatedByAdminId) {
                // return empty collection
                $this->initEmpRequestsRelatedByAdminId();
            } else {
                $collEmpRequestsRelatedByAdminId = EmpRequestQuery::create(null, $criteria)
                    ->filterByEmpAccRelatedByAdminId($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collEmpRequestsRelatedByAdminIdPartial && count($collEmpRequestsRelatedByAdminId)) {
                      $this->initEmpRequestsRelatedByAdminId(false);

                      foreach ($collEmpRequestsRelatedByAdminId as $obj) {
                        if (false == $this->collEmpRequestsRelatedByAdminId->contains($obj)) {
                          $this->collEmpRequestsRelatedByAdminId->append($obj);
                        }
                      }

                      $this->collEmpRequestsRelatedByAdminIdPartial = true;
                    }

                    $collEmpRequestsRelatedByAdminId->getInternalIterator()->rewind();

                    return $collEmpRequestsRelatedByAdminId;
                }

                if ($partial && $this->collEmpRequestsRelatedByAdminId) {
                    foreach ($this->collEmpRequestsRelatedByAdminId as $obj) {
                        if ($obj->isNew()) {
                            $collEmpRequestsRelatedByAdminId[] = $obj;
                        }
                    }
                }

                $this->collEmpRequestsRelatedByAdminId = $collEmpRequestsRelatedByAdminId;
                $this->collEmpRequestsRelatedByAdminIdPartial = false;
            }
        }

        return $this->collEmpRequestsRelatedByAdminId;
    }

    /**
     * Sets a collection of EmpRequestRelatedByAdminId objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $empRequestsRelatedByAdminId A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return EmpAcc The current object (for fluent API support)
     */
    public function setEmpRequestsRelatedByAdminId(PropelCollection $empRequestsRelatedByAdminId, PropelPDO $con = null)
    {
        $empRequestsRelatedByAdminIdToDelete = $this->getEmpRequestsRelatedByAdminId(new Criteria(), $con)->diff($empRequestsRelatedByAdminId);


        $this->empRequestsRelatedByAdminIdScheduledForDeletion = $empRequestsRelatedByAdminIdToDelete;

        foreach ($empRequestsRelatedByAdminIdToDelete as $empRequestRelatedByAdminIdRemoved) {
            $empRequestRelatedByAdminIdRemoved->setEmpAccRelatedByAdminId(null);
        }

        $this->collEmpRequestsRelatedByAdminId = null;
        foreach ($empRequestsRelatedByAdminId as $empRequestRelatedByAdminId) {
            $this->addEmpRequestRelatedByAdminId($empRequestRelatedByAdminId);
        }

        $this->collEmpRequestsRelatedByAdminId = $empRequestsRelatedByAdminId;
        $this->collEmpRequestsRelatedByAdminIdPartial = false;

        return $this;
    }

    /**
     * Returns the number of related EmpRequest objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related EmpRequest objects.
     * @throws PropelException
     */
    public function countEmpRequestsRelatedByAdminId(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collEmpRequestsRelatedByAdminIdPartial && !$this->isNew();
        if (null === $this->collEmpRequestsRelatedByAdminId || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collEmpRequestsRelatedByAdminId) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getEmpRequestsRelatedByAdminId());
            }
            $query = EmpRequestQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByEmpAccRelatedByAdminId($this)
                ->count($con);
        }

        return count($this->collEmpRequestsRelatedByAdminId);
    }

    /**
     * Method called to associate a EmpRequest object to this object
     * through the EmpRequest foreign key attribute.
     *
     * @param    EmpRequest $l EmpRequest
     * @return EmpAcc The current object (for fluent API support)
     */
    public function addEmpRequestRelatedByAdminId(EmpRequest $l)
    {
        if ($this->collEmpRequestsRelatedByAdminId === null) {
            $this->initEmpRequestsRelatedByAdminId();
            $this->collEmpRequestsRelatedByAdminIdPartial = true;
        }

        if (!in_array($l, $this->collEmpRequestsRelatedByAdminId->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddEmpRequestRelatedByAdminId($l);

            if ($this->empRequestsRelatedByAdminIdScheduledForDeletion and $this->empRequestsRelatedByAdminIdScheduledForDeletion->contains($l)) {
                $this->empRequestsRelatedByAdminIdScheduledForDeletion->remove($this->empRequestsRelatedByAdminIdScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	EmpRequestRelatedByAdminId $empRequestRelatedByAdminId The empRequestRelatedByAdminId object to add.
     */
    protected function doAddEmpRequestRelatedByAdminId($empRequestRelatedByAdminId)
    {
        $this->collEmpRequestsRelatedByAdminId[]= $empRequestRelatedByAdminId;
        $empRequestRelatedByAdminId->setEmpAccRelatedByAdminId($this);
    }

    /**
     * @param	EmpRequestRelatedByAdminId $empRequestRelatedByAdminId The empRequestRelatedByAdminId object to remove.
     * @return EmpAcc The current object (for fluent API support)
     */
    public function removeEmpRequestRelatedByAdminId($empRequestRelatedByAdminId)
    {
        if ($this->getEmpRequestsRelatedByAdminId()->contains($empRequestRelatedByAdminId)) {
            $this->collEmpRequestsRelatedByAdminId->remove($this->collEmpRequestsRelatedByAdminId->search($empRequestRelatedByAdminId));
            if (null === $this->empRequestsRelatedByAdminIdScheduledForDeletion) {
                $this->empRequestsRelatedByAdminIdScheduledForDeletion = clone $this->collEmpRequestsRelatedByAdminId;
                $this->empRequestsRelatedByAdminIdScheduledForDeletion->clear();
            }
            $this->empRequestsRelatedByAdminIdScheduledForDeletion[]= $empRequestRelatedByAdminId;
            $empRequestRelatedByAdminId->setEmpAccRelatedByAdminId(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this EmpAcc is new, it will return
     * an empty collection; or if this EmpAcc has previously
     * been saved, it will retrieve related EmpRequestsRelatedByAdminId from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in EmpAcc.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|EmpRequest[] List of EmpRequest objects
     */
    public function getEmpRequestsRelatedByAdminIdJoinListRequestType($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = EmpRequestQuery::create(null, $criteria);
        $query->joinWith('ListRequestType', $join_behavior);

        return $this->getEmpRequestsRelatedByAdminId($query, $con);
    }

    /**
     * Clears out the collEmpProfiles collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return EmpAcc The current object (for fluent API support)
     * @see        addEmpProfiles()
     */
    public function clearEmpProfiles()
    {
        $this->collEmpProfiles = null; // important to set this to null since that means it is uninitialized
        $this->collEmpProfilesPartial = null;

        return $this;
    }

    /**
     * reset is the collEmpProfiles collection loaded partially
     *
     * @return void
     */
    public function resetPartialEmpProfiles($v = true)
    {
        $this->collEmpProfilesPartial = $v;
    }

    /**
     * Initializes the collEmpProfiles collection.
     *
     * By default this just sets the collEmpProfiles collection to an empty array (like clearcollEmpProfiles());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initEmpProfiles($overrideExisting = true)
    {
        if (null !== $this->collEmpProfiles && !$overrideExisting) {
            return;
        }
        $this->collEmpProfiles = new PropelObjectCollection();
        $this->collEmpProfiles->setModel('EmpProfile');
    }

    /**
     * Gets an array of EmpProfile objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this EmpAcc is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|EmpProfile[] List of EmpProfile objects
     * @throws PropelException
     */
    public function getEmpProfiles($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collEmpProfilesPartial && !$this->isNew();
        if (null === $this->collEmpProfiles || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collEmpProfiles) {
                // return empty collection
                $this->initEmpProfiles();
            } else {
                $collEmpProfiles = EmpProfileQuery::create(null, $criteria)
                    ->filterByEmpAcc($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collEmpProfilesPartial && count($collEmpProfiles)) {
                      $this->initEmpProfiles(false);

                      foreach ($collEmpProfiles as $obj) {
                        if (false == $this->collEmpProfiles->contains($obj)) {
                          $this->collEmpProfiles->append($obj);
                        }
                      }

                      $this->collEmpProfilesPartial = true;
                    }

                    $collEmpProfiles->getInternalIterator()->rewind();

                    return $collEmpProfiles;
                }

                if ($partial && $this->collEmpProfiles) {
                    foreach ($this->collEmpProfiles as $obj) {
                        if ($obj->isNew()) {
                            $collEmpProfiles[] = $obj;
                        }
                    }
                }

                $this->collEmpProfiles = $collEmpProfiles;
                $this->collEmpProfilesPartial = false;
            }
        }

        return $this->collEmpProfiles;
    }

    /**
     * Sets a collection of EmpProfile objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $empProfiles A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return EmpAcc The current object (for fluent API support)
     */
    public function setEmpProfiles(PropelCollection $empProfiles, PropelPDO $con = null)
    {
        $empProfilesToDelete = $this->getEmpProfiles(new Criteria(), $con)->diff($empProfiles);


        $this->empProfilesScheduledForDeletion = $empProfilesToDelete;

        foreach ($empProfilesToDelete as $empProfileRemoved) {
            $empProfileRemoved->setEmpAcc(null);
        }

        $this->collEmpProfiles = null;
        foreach ($empProfiles as $empProfile) {
            $this->addEmpProfile($empProfile);
        }

        $this->collEmpProfiles = $empProfiles;
        $this->collEmpProfilesPartial = false;

        return $this;
    }

    /**
     * Returns the number of related EmpProfile objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related EmpProfile objects.
     * @throws PropelException
     */
    public function countEmpProfiles(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collEmpProfilesPartial && !$this->isNew();
        if (null === $this->collEmpProfiles || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collEmpProfiles) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getEmpProfiles());
            }
            $query = EmpProfileQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByEmpAcc($this)
                ->count($con);
        }

        return count($this->collEmpProfiles);
    }

    /**
     * Method called to associate a EmpProfile object to this object
     * through the EmpProfile foreign key attribute.
     *
     * @param    EmpProfile $l EmpProfile
     * @return EmpAcc The current object (for fluent API support)
     */
    public function addEmpProfile(EmpProfile $l)
    {
        if ($this->collEmpProfiles === null) {
            $this->initEmpProfiles();
            $this->collEmpProfilesPartial = true;
        }

        if (!in_array($l, $this->collEmpProfiles->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddEmpProfile($l);

            if ($this->empProfilesScheduledForDeletion and $this->empProfilesScheduledForDeletion->contains($l)) {
                $this->empProfilesScheduledForDeletion->remove($this->empProfilesScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	EmpProfile $empProfile The empProfile object to add.
     */
    protected function doAddEmpProfile($empProfile)
    {
        $this->collEmpProfiles[]= $empProfile;
        $empProfile->setEmpAcc($this);
    }

    /**
     * @param	EmpProfile $empProfile The empProfile object to remove.
     * @return EmpAcc The current object (for fluent API support)
     */
    public function removeEmpProfile($empProfile)
    {
        if ($this->getEmpProfiles()->contains($empProfile)) {
            $this->collEmpProfiles->remove($this->collEmpProfiles->search($empProfile));
            if (null === $this->empProfilesScheduledForDeletion) {
                $this->empProfilesScheduledForDeletion = clone $this->collEmpProfiles;
                $this->empProfilesScheduledForDeletion->clear();
            }
            $this->empProfilesScheduledForDeletion[]= clone $empProfile;
            $empProfile->setEmpAcc(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this EmpAcc is new, it will return
     * an empty collection; or if this EmpAcc has previously
     * been saved, it will retrieve related EmpProfiles from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in EmpAcc.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|EmpProfile[] List of EmpProfile objects
     */
    public function getEmpProfilesJoinListDept($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = EmpProfileQuery::create(null, $criteria);
        $query->joinWith('ListDept', $join_behavior);

        return $this->getEmpProfiles($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this EmpAcc is new, it will return
     * an empty collection; or if this EmpAcc has previously
     * been saved, it will retrieve related EmpProfiles from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in EmpAcc.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|EmpProfile[] List of EmpProfile objects
     */
    public function getEmpProfilesJoinListPos($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = EmpProfileQuery::create(null, $criteria);
        $query->joinWith('ListPos', $join_behavior);

        return $this->getEmpProfiles($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this EmpAcc is new, it will return
     * an empty collection; or if this EmpAcc has previously
     * been saved, it will retrieve related EmpProfiles from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in EmpAcc.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|EmpProfile[] List of EmpProfile objects
     */
    public function getEmpProfilesJoinEmpStatusType($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = EmpProfileQuery::create(null, $criteria);
        $query->joinWith('EmpStatusType', $join_behavior);

        return $this->getEmpProfiles($query, $con);
    }

    /**
     * Clears out the collEmpTimes collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return EmpAcc The current object (for fluent API support)
     * @see        addEmpTimes()
     */
    public function clearEmpTimes()
    {
        $this->collEmpTimes = null; // important to set this to null since that means it is uninitialized
        $this->collEmpTimesPartial = null;

        return $this;
    }

    /**
     * reset is the collEmpTimes collection loaded partially
     *
     * @return void
     */
    public function resetPartialEmpTimes($v = true)
    {
        $this->collEmpTimesPartial = $v;
    }

    /**
     * Initializes the collEmpTimes collection.
     *
     * By default this just sets the collEmpTimes collection to an empty array (like clearcollEmpTimes());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initEmpTimes($overrideExisting = true)
    {
        if (null !== $this->collEmpTimes && !$overrideExisting) {
            return;
        }
        $this->collEmpTimes = new PropelObjectCollection();
        $this->collEmpTimes->setModel('EmpTime');
    }

    /**
     * Gets an array of EmpTime objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this EmpAcc is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|EmpTime[] List of EmpTime objects
     * @throws PropelException
     */
    public function getEmpTimes($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collEmpTimesPartial && !$this->isNew();
        if (null === $this->collEmpTimes || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collEmpTimes) {
                // return empty collection
                $this->initEmpTimes();
            } else {
                $collEmpTimes = EmpTimeQuery::create(null, $criteria)
                    ->filterByEmpAcc($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collEmpTimesPartial && count($collEmpTimes)) {
                      $this->initEmpTimes(false);

                      foreach ($collEmpTimes as $obj) {
                        if (false == $this->collEmpTimes->contains($obj)) {
                          $this->collEmpTimes->append($obj);
                        }
                      }

                      $this->collEmpTimesPartial = true;
                    }

                    $collEmpTimes->getInternalIterator()->rewind();

                    return $collEmpTimes;
                }

                if ($partial && $this->collEmpTimes) {
                    foreach ($this->collEmpTimes as $obj) {
                        if ($obj->isNew()) {
                            $collEmpTimes[] = $obj;
                        }
                    }
                }

                $this->collEmpTimes = $collEmpTimes;
                $this->collEmpTimesPartial = false;
            }
        }

        return $this->collEmpTimes;
    }

    /**
     * Sets a collection of EmpTime objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $empTimes A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return EmpAcc The current object (for fluent API support)
     */
    public function setEmpTimes(PropelCollection $empTimes, PropelPDO $con = null)
    {
        $empTimesToDelete = $this->getEmpTimes(new Criteria(), $con)->diff($empTimes);


        $this->empTimesScheduledForDeletion = $empTimesToDelete;

        foreach ($empTimesToDelete as $empTimeRemoved) {
            $empTimeRemoved->setEmpAcc(null);
        }

        $this->collEmpTimes = null;
        foreach ($empTimes as $empTime) {
            $this->addEmpTime($empTime);
        }

        $this->collEmpTimes = $empTimes;
        $this->collEmpTimesPartial = false;

        return $this;
    }

    /**
     * Returns the number of related EmpTime objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related EmpTime objects.
     * @throws PropelException
     */
    public function countEmpTimes(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collEmpTimesPartial && !$this->isNew();
        if (null === $this->collEmpTimes || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collEmpTimes) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getEmpTimes());
            }
            $query = EmpTimeQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByEmpAcc($this)
                ->count($con);
        }

        return count($this->collEmpTimes);
    }

    /**
     * Method called to associate a EmpTime object to this object
     * through the EmpTime foreign key attribute.
     *
     * @param    EmpTime $l EmpTime
     * @return EmpAcc The current object (for fluent API support)
     */
    public function addEmpTime(EmpTime $l)
    {
        if ($this->collEmpTimes === null) {
            $this->initEmpTimes();
            $this->collEmpTimesPartial = true;
        }

        if (!in_array($l, $this->collEmpTimes->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddEmpTime($l);

            if ($this->empTimesScheduledForDeletion and $this->empTimesScheduledForDeletion->contains($l)) {
                $this->empTimesScheduledForDeletion->remove($this->empTimesScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	EmpTime $empTime The empTime object to add.
     */
    protected function doAddEmpTime($empTime)
    {
        $this->collEmpTimes[]= $empTime;
        $empTime->setEmpAcc($this);
    }

    /**
     * @param	EmpTime $empTime The empTime object to remove.
     * @return EmpAcc The current object (for fluent API support)
     */
    public function removeEmpTime($empTime)
    {
        if ($this->getEmpTimes()->contains($empTime)) {
            $this->collEmpTimes->remove($this->collEmpTimes->search($empTime));
            if (null === $this->empTimesScheduledForDeletion) {
                $this->empTimesScheduledForDeletion = clone $this->collEmpTimes;
                $this->empTimesScheduledForDeletion->clear();
            }
            $this->empTimesScheduledForDeletion[]= clone $empTime;
            $empTime->setEmpAcc(null);
        }

        return $this;
    }

    /**
     * Clears out the collEmpCapabilitiess collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return EmpAcc The current object (for fluent API support)
     * @see        addEmpCapabilitiess()
     */
    public function clearEmpCapabilitiess()
    {
        $this->collEmpCapabilitiess = null; // important to set this to null since that means it is uninitialized
        $this->collEmpCapabilitiessPartial = null;

        return $this;
    }

    /**
     * reset is the collEmpCapabilitiess collection loaded partially
     *
     * @return void
     */
    public function resetPartialEmpCapabilitiess($v = true)
    {
        $this->collEmpCapabilitiessPartial = $v;
    }

    /**
     * Initializes the collEmpCapabilitiess collection.
     *
     * By default this just sets the collEmpCapabilitiess collection to an empty array (like clearcollEmpCapabilitiess());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initEmpCapabilitiess($overrideExisting = true)
    {
        if (null !== $this->collEmpCapabilitiess && !$overrideExisting) {
            return;
        }
        $this->collEmpCapabilitiess = new PropelObjectCollection();
        $this->collEmpCapabilitiess->setModel('EmpCapabilities');
    }

    /**
     * Gets an array of EmpCapabilities objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this EmpAcc is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|EmpCapabilities[] List of EmpCapabilities objects
     * @throws PropelException
     */
    public function getEmpCapabilitiess($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collEmpCapabilitiessPartial && !$this->isNew();
        if (null === $this->collEmpCapabilitiess || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collEmpCapabilitiess) {
                // return empty collection
                $this->initEmpCapabilitiess();
            } else {
                $collEmpCapabilitiess = EmpCapabilitiesQuery::create(null, $criteria)
                    ->filterByEmpAcc($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collEmpCapabilitiessPartial && count($collEmpCapabilitiess)) {
                      $this->initEmpCapabilitiess(false);

                      foreach ($collEmpCapabilitiess as $obj) {
                        if (false == $this->collEmpCapabilitiess->contains($obj)) {
                          $this->collEmpCapabilitiess->append($obj);
                        }
                      }

                      $this->collEmpCapabilitiessPartial = true;
                    }

                    $collEmpCapabilitiess->getInternalIterator()->rewind();

                    return $collEmpCapabilitiess;
                }

                if ($partial && $this->collEmpCapabilitiess) {
                    foreach ($this->collEmpCapabilitiess as $obj) {
                        if ($obj->isNew()) {
                            $collEmpCapabilitiess[] = $obj;
                        }
                    }
                }

                $this->collEmpCapabilitiess = $collEmpCapabilitiess;
                $this->collEmpCapabilitiessPartial = false;
            }
        }

        return $this->collEmpCapabilitiess;
    }

    /**
     * Sets a collection of EmpCapabilities objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $empCapabilitiess A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return EmpAcc The current object (for fluent API support)
     */
    public function setEmpCapabilitiess(PropelCollection $empCapabilitiess, PropelPDO $con = null)
    {
        $empCapabilitiessToDelete = $this->getEmpCapabilitiess(new Criteria(), $con)->diff($empCapabilitiess);


        $this->empCapabilitiessScheduledForDeletion = $empCapabilitiessToDelete;

        foreach ($empCapabilitiessToDelete as $empCapabilitiesRemoved) {
            $empCapabilitiesRemoved->setEmpAcc(null);
        }

        $this->collEmpCapabilitiess = null;
        foreach ($empCapabilitiess as $empCapabilities) {
            $this->addEmpCapabilities($empCapabilities);
        }

        $this->collEmpCapabilitiess = $empCapabilitiess;
        $this->collEmpCapabilitiessPartial = false;

        return $this;
    }

    /**
     * Returns the number of related EmpCapabilities objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related EmpCapabilities objects.
     * @throws PropelException
     */
    public function countEmpCapabilitiess(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collEmpCapabilitiessPartial && !$this->isNew();
        if (null === $this->collEmpCapabilitiess || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collEmpCapabilitiess) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getEmpCapabilitiess());
            }
            $query = EmpCapabilitiesQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByEmpAcc($this)
                ->count($con);
        }

        return count($this->collEmpCapabilitiess);
    }

    /**
     * Method called to associate a EmpCapabilities object to this object
     * through the EmpCapabilities foreign key attribute.
     *
     * @param    EmpCapabilities $l EmpCapabilities
     * @return EmpAcc The current object (for fluent API support)
     */
    public function addEmpCapabilities(EmpCapabilities $l)
    {
        if ($this->collEmpCapabilitiess === null) {
            $this->initEmpCapabilitiess();
            $this->collEmpCapabilitiessPartial = true;
        }

        if (!in_array($l, $this->collEmpCapabilitiess->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddEmpCapabilities($l);

            if ($this->empCapabilitiessScheduledForDeletion and $this->empCapabilitiessScheduledForDeletion->contains($l)) {
                $this->empCapabilitiessScheduledForDeletion->remove($this->empCapabilitiessScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	EmpCapabilities $empCapabilities The empCapabilities object to add.
     */
    protected function doAddEmpCapabilities($empCapabilities)
    {
        $this->collEmpCapabilitiess[]= $empCapabilities;
        $empCapabilities->setEmpAcc($this);
    }

    /**
     * @param	EmpCapabilities $empCapabilities The empCapabilities object to remove.
     * @return EmpAcc The current object (for fluent API support)
     */
    public function removeEmpCapabilities($empCapabilities)
    {
        if ($this->getEmpCapabilitiess()->contains($empCapabilities)) {
            $this->collEmpCapabilitiess->remove($this->collEmpCapabilitiess->search($empCapabilities));
            if (null === $this->empCapabilitiessScheduledForDeletion) {
                $this->empCapabilitiessScheduledForDeletion = clone $this->collEmpCapabilitiess;
                $this->empCapabilitiessScheduledForDeletion->clear();
            }
            $this->empCapabilitiessScheduledForDeletion[]= $empCapabilities;
            $empCapabilities->setEmpAcc(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this EmpAcc is new, it will return
     * an empty collection; or if this EmpAcc has previously
     * been saved, it will retrieve related EmpCapabilitiess from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in EmpAcc.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|EmpCapabilities[] List of EmpCapabilities objects
     */
    public function getEmpCapabilitiessJoinCapabilitiesList($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = EmpCapabilitiesQuery::create(null, $criteria);
        $query->joinWith('CapabilitiesList', $join_behavior);

        return $this->getEmpCapabilitiess($query, $con);
    }

    /**
     * Clears out the collListEventss collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return EmpAcc The current object (for fluent API support)
     * @see        addListEventss()
     */
    public function clearListEventss()
    {
        $this->collListEventss = null; // important to set this to null since that means it is uninitialized
        $this->collListEventssPartial = null;

        return $this;
    }

    /**
     * reset is the collListEventss collection loaded partially
     *
     * @return void
     */
    public function resetPartialListEventss($v = true)
    {
        $this->collListEventssPartial = $v;
    }

    /**
     * Initializes the collListEventss collection.
     *
     * By default this just sets the collListEventss collection to an empty array (like clearcollListEventss());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initListEventss($overrideExisting = true)
    {
        if (null !== $this->collListEventss && !$overrideExisting) {
            return;
        }
        $this->collListEventss = new PropelObjectCollection();
        $this->collListEventss->setModel('ListEvents');
    }

    /**
     * Gets an array of ListEvents objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this EmpAcc is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|ListEvents[] List of ListEvents objects
     * @throws PropelException
     */
    public function getListEventss($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collListEventssPartial && !$this->isNew();
        if (null === $this->collListEventss || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collListEventss) {
                // return empty collection
                $this->initListEventss();
            } else {
                $collListEventss = ListEventsQuery::create(null, $criteria)
                    ->filterByEmpAcc($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collListEventssPartial && count($collListEventss)) {
                      $this->initListEventss(false);

                      foreach ($collListEventss as $obj) {
                        if (false == $this->collListEventss->contains($obj)) {
                          $this->collListEventss->append($obj);
                        }
                      }

                      $this->collListEventssPartial = true;
                    }

                    $collListEventss->getInternalIterator()->rewind();

                    return $collListEventss;
                }

                if ($partial && $this->collListEventss) {
                    foreach ($this->collListEventss as $obj) {
                        if ($obj->isNew()) {
                            $collListEventss[] = $obj;
                        }
                    }
                }

                $this->collListEventss = $collListEventss;
                $this->collListEventssPartial = false;
            }
        }

        return $this->collListEventss;
    }

    /**
     * Sets a collection of ListEvents objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $listEventss A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return EmpAcc The current object (for fluent API support)
     */
    public function setListEventss(PropelCollection $listEventss, PropelPDO $con = null)
    {
        $listEventssToDelete = $this->getListEventss(new Criteria(), $con)->diff($listEventss);


        $this->listEventssScheduledForDeletion = $listEventssToDelete;

        foreach ($listEventssToDelete as $listEventsRemoved) {
            $listEventsRemoved->setEmpAcc(null);
        }

        $this->collListEventss = null;
        foreach ($listEventss as $listEvents) {
            $this->addListEvents($listEvents);
        }

        $this->collListEventss = $listEventss;
        $this->collListEventssPartial = false;

        return $this;
    }

    /**
     * Returns the number of related ListEvents objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related ListEvents objects.
     * @throws PropelException
     */
    public function countListEventss(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collListEventssPartial && !$this->isNew();
        if (null === $this->collListEventss || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collListEventss) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getListEventss());
            }
            $query = ListEventsQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByEmpAcc($this)
                ->count($con);
        }

        return count($this->collListEventss);
    }

    /**
     * Method called to associate a ListEvents object to this object
     * through the ListEvents foreign key attribute.
     *
     * @param    ListEvents $l ListEvents
     * @return EmpAcc The current object (for fluent API support)
     */
    public function addListEvents(ListEvents $l)
    {
        if ($this->collListEventss === null) {
            $this->initListEventss();
            $this->collListEventssPartial = true;
        }

        if (!in_array($l, $this->collListEventss->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddListEvents($l);

            if ($this->listEventssScheduledForDeletion and $this->listEventssScheduledForDeletion->contains($l)) {
                $this->listEventssScheduledForDeletion->remove($this->listEventssScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	ListEvents $listEvents The listEvents object to add.
     */
    protected function doAddListEvents($listEvents)
    {
        $this->collListEventss[]= $listEvents;
        $listEvents->setEmpAcc($this);
    }

    /**
     * @param	ListEvents $listEvents The listEvents object to remove.
     * @return EmpAcc The current object (for fluent API support)
     */
    public function removeListEvents($listEvents)
    {
        if ($this->getListEventss()->contains($listEvents)) {
            $this->collListEventss->remove($this->collListEventss->search($listEvents));
            if (null === $this->listEventssScheduledForDeletion) {
                $this->listEventssScheduledForDeletion = clone $this->collListEventss;
                $this->listEventssScheduledForDeletion->clear();
            }
            $this->listEventssScheduledForDeletion[]= clone $listEvents;
            $listEvents->setEmpAcc(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this EmpAcc is new, it will return
     * an empty collection; or if this EmpAcc has previously
     * been saved, it will retrieve related ListEventss from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in EmpAcc.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|ListEvents[] List of ListEvents objects
     */
    public function getListEventssJoinListEventsType($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = ListEventsQuery::create(null, $criteria);
        $query->joinWith('ListEventsType', $join_behavior);

        return $this->getListEventss($query, $con);
    }

    /**
     * Clears out the collEventNotess collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return EmpAcc The current object (for fluent API support)
     * @see        addEventNotess()
     */
    public function clearEventNotess()
    {
        $this->collEventNotess = null; // important to set this to null since that means it is uninitialized
        $this->collEventNotessPartial = null;

        return $this;
    }

    /**
     * reset is the collEventNotess collection loaded partially
     *
     * @return void
     */
    public function resetPartialEventNotess($v = true)
    {
        $this->collEventNotessPartial = $v;
    }

    /**
     * Initializes the collEventNotess collection.
     *
     * By default this just sets the collEventNotess collection to an empty array (like clearcollEventNotess());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initEventNotess($overrideExisting = true)
    {
        if (null !== $this->collEventNotess && !$overrideExisting) {
            return;
        }
        $this->collEventNotess = new PropelObjectCollection();
        $this->collEventNotess->setModel('EventNotes');
    }

    /**
     * Gets an array of EventNotes objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this EmpAcc is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|EventNotes[] List of EventNotes objects
     * @throws PropelException
     */
    public function getEventNotess($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collEventNotessPartial && !$this->isNew();
        if (null === $this->collEventNotess || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collEventNotess) {
                // return empty collection
                $this->initEventNotess();
            } else {
                $collEventNotess = EventNotesQuery::create(null, $criteria)
                    ->filterByEmpAcc($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collEventNotessPartial && count($collEventNotess)) {
                      $this->initEventNotess(false);

                      foreach ($collEventNotess as $obj) {
                        if (false == $this->collEventNotess->contains($obj)) {
                          $this->collEventNotess->append($obj);
                        }
                      }

                      $this->collEventNotessPartial = true;
                    }

                    $collEventNotess->getInternalIterator()->rewind();

                    return $collEventNotess;
                }

                if ($partial && $this->collEventNotess) {
                    foreach ($this->collEventNotess as $obj) {
                        if ($obj->isNew()) {
                            $collEventNotess[] = $obj;
                        }
                    }
                }

                $this->collEventNotess = $collEventNotess;
                $this->collEventNotessPartial = false;
            }
        }

        return $this->collEventNotess;
    }

    /**
     * Sets a collection of EventNotes objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $eventNotess A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return EmpAcc The current object (for fluent API support)
     */
    public function setEventNotess(PropelCollection $eventNotess, PropelPDO $con = null)
    {
        $eventNotessToDelete = $this->getEventNotess(new Criteria(), $con)->diff($eventNotess);


        $this->eventNotessScheduledForDeletion = $eventNotessToDelete;

        foreach ($eventNotessToDelete as $eventNotesRemoved) {
            $eventNotesRemoved->setEmpAcc(null);
        }

        $this->collEventNotess = null;
        foreach ($eventNotess as $eventNotes) {
            $this->addEventNotes($eventNotes);
        }

        $this->collEventNotess = $eventNotess;
        $this->collEventNotessPartial = false;

        return $this;
    }

    /**
     * Returns the number of related EventNotes objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related EventNotes objects.
     * @throws PropelException
     */
    public function countEventNotess(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collEventNotessPartial && !$this->isNew();
        if (null === $this->collEventNotess || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collEventNotess) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getEventNotess());
            }
            $query = EventNotesQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByEmpAcc($this)
                ->count($con);
        }

        return count($this->collEventNotess);
    }

    /**
     * Method called to associate a EventNotes object to this object
     * through the EventNotes foreign key attribute.
     *
     * @param    EventNotes $l EventNotes
     * @return EmpAcc The current object (for fluent API support)
     */
    public function addEventNotes(EventNotes $l)
    {
        if ($this->collEventNotess === null) {
            $this->initEventNotess();
            $this->collEventNotessPartial = true;
        }

        if (!in_array($l, $this->collEventNotess->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddEventNotes($l);

            if ($this->eventNotessScheduledForDeletion and $this->eventNotessScheduledForDeletion->contains($l)) {
                $this->eventNotessScheduledForDeletion->remove($this->eventNotessScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	EventNotes $eventNotes The eventNotes object to add.
     */
    protected function doAddEventNotes($eventNotes)
    {
        $this->collEventNotess[]= $eventNotes;
        $eventNotes->setEmpAcc($this);
    }

    /**
     * @param	EventNotes $eventNotes The eventNotes object to remove.
     * @return EmpAcc The current object (for fluent API support)
     */
    public function removeEventNotes($eventNotes)
    {
        if ($this->getEventNotess()->contains($eventNotes)) {
            $this->collEventNotess->remove($this->collEventNotess->search($eventNotes));
            if (null === $this->eventNotessScheduledForDeletion) {
                $this->eventNotessScheduledForDeletion = clone $this->collEventNotess;
                $this->eventNotessScheduledForDeletion->clear();
            }
            $this->eventNotessScheduledForDeletion[]= clone $eventNotes;
            $eventNotes->setEmpAcc(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this EmpAcc is new, it will return
     * an empty collection; or if this EmpAcc has previously
     * been saved, it will retrieve related EventNotess from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in EmpAcc.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|EventNotes[] List of EventNotes objects
     */
    public function getEventNotessJoinListEvents($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = EventNotesQuery::create(null, $criteria);
        $query->joinWith('ListEvents', $join_behavior);

        return $this->getEventNotess($query, $con);
    }

    /**
     * Clears out the collEventTaggedPersonss collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return EmpAcc The current object (for fluent API support)
     * @see        addEventTaggedPersonss()
     */
    public function clearEventTaggedPersonss()
    {
        $this->collEventTaggedPersonss = null; // important to set this to null since that means it is uninitialized
        $this->collEventTaggedPersonssPartial = null;

        return $this;
    }

    /**
     * reset is the collEventTaggedPersonss collection loaded partially
     *
     * @return void
     */
    public function resetPartialEventTaggedPersonss($v = true)
    {
        $this->collEventTaggedPersonssPartial = $v;
    }

    /**
     * Initializes the collEventTaggedPersonss collection.
     *
     * By default this just sets the collEventTaggedPersonss collection to an empty array (like clearcollEventTaggedPersonss());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initEventTaggedPersonss($overrideExisting = true)
    {
        if (null !== $this->collEventTaggedPersonss && !$overrideExisting) {
            return;
        }
        $this->collEventTaggedPersonss = new PropelObjectCollection();
        $this->collEventTaggedPersonss->setModel('EventTaggedPersons');
    }

    /**
     * Gets an array of EventTaggedPersons objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this EmpAcc is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|EventTaggedPersons[] List of EventTaggedPersons objects
     * @throws PropelException
     */
    public function getEventTaggedPersonss($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collEventTaggedPersonssPartial && !$this->isNew();
        if (null === $this->collEventTaggedPersonss || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collEventTaggedPersonss) {
                // return empty collection
                $this->initEventTaggedPersonss();
            } else {
                $collEventTaggedPersonss = EventTaggedPersonsQuery::create(null, $criteria)
                    ->filterByEmpAcc($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collEventTaggedPersonssPartial && count($collEventTaggedPersonss)) {
                      $this->initEventTaggedPersonss(false);

                      foreach ($collEventTaggedPersonss as $obj) {
                        if (false == $this->collEventTaggedPersonss->contains($obj)) {
                          $this->collEventTaggedPersonss->append($obj);
                        }
                      }

                      $this->collEventTaggedPersonssPartial = true;
                    }

                    $collEventTaggedPersonss->getInternalIterator()->rewind();

                    return $collEventTaggedPersonss;
                }

                if ($partial && $this->collEventTaggedPersonss) {
                    foreach ($this->collEventTaggedPersonss as $obj) {
                        if ($obj->isNew()) {
                            $collEventTaggedPersonss[] = $obj;
                        }
                    }
                }

                $this->collEventTaggedPersonss = $collEventTaggedPersonss;
                $this->collEventTaggedPersonssPartial = false;
            }
        }

        return $this->collEventTaggedPersonss;
    }

    /**
     * Sets a collection of EventTaggedPersons objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $eventTaggedPersonss A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return EmpAcc The current object (for fluent API support)
     */
    public function setEventTaggedPersonss(PropelCollection $eventTaggedPersonss, PropelPDO $con = null)
    {
        $eventTaggedPersonssToDelete = $this->getEventTaggedPersonss(new Criteria(), $con)->diff($eventTaggedPersonss);


        $this->eventTaggedPersonssScheduledForDeletion = $eventTaggedPersonssToDelete;

        foreach ($eventTaggedPersonssToDelete as $eventTaggedPersonsRemoved) {
            $eventTaggedPersonsRemoved->setEmpAcc(null);
        }

        $this->collEventTaggedPersonss = null;
        foreach ($eventTaggedPersonss as $eventTaggedPersons) {
            $this->addEventTaggedPersons($eventTaggedPersons);
        }

        $this->collEventTaggedPersonss = $eventTaggedPersonss;
        $this->collEventTaggedPersonssPartial = false;

        return $this;
    }

    /**
     * Returns the number of related EventTaggedPersons objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related EventTaggedPersons objects.
     * @throws PropelException
     */
    public function countEventTaggedPersonss(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collEventTaggedPersonssPartial && !$this->isNew();
        if (null === $this->collEventTaggedPersonss || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collEventTaggedPersonss) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getEventTaggedPersonss());
            }
            $query = EventTaggedPersonsQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByEmpAcc($this)
                ->count($con);
        }

        return count($this->collEventTaggedPersonss);
    }

    /**
     * Method called to associate a EventTaggedPersons object to this object
     * through the EventTaggedPersons foreign key attribute.
     *
     * @param    EventTaggedPersons $l EventTaggedPersons
     * @return EmpAcc The current object (for fluent API support)
     */
    public function addEventTaggedPersons(EventTaggedPersons $l)
    {
        if ($this->collEventTaggedPersonss === null) {
            $this->initEventTaggedPersonss();
            $this->collEventTaggedPersonssPartial = true;
        }

        if (!in_array($l, $this->collEventTaggedPersonss->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddEventTaggedPersons($l);

            if ($this->eventTaggedPersonssScheduledForDeletion and $this->eventTaggedPersonssScheduledForDeletion->contains($l)) {
                $this->eventTaggedPersonssScheduledForDeletion->remove($this->eventTaggedPersonssScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	EventTaggedPersons $eventTaggedPersons The eventTaggedPersons object to add.
     */
    protected function doAddEventTaggedPersons($eventTaggedPersons)
    {
        $this->collEventTaggedPersonss[]= $eventTaggedPersons;
        $eventTaggedPersons->setEmpAcc($this);
    }

    /**
     * @param	EventTaggedPersons $eventTaggedPersons The eventTaggedPersons object to remove.
     * @return EmpAcc The current object (for fluent API support)
     */
    public function removeEventTaggedPersons($eventTaggedPersons)
    {
        if ($this->getEventTaggedPersonss()->contains($eventTaggedPersons)) {
            $this->collEventTaggedPersonss->remove($this->collEventTaggedPersonss->search($eventTaggedPersons));
            if (null === $this->eventTaggedPersonssScheduledForDeletion) {
                $this->eventTaggedPersonssScheduledForDeletion = clone $this->collEventTaggedPersonss;
                $this->eventTaggedPersonssScheduledForDeletion->clear();
            }
            $this->eventTaggedPersonssScheduledForDeletion[]= clone $eventTaggedPersons;
            $eventTaggedPersons->setEmpAcc(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this EmpAcc is new, it will return
     * an empty collection; or if this EmpAcc has previously
     * been saved, it will retrieve related EventTaggedPersonss from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in EmpAcc.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|EventTaggedPersons[] List of EventTaggedPersons objects
     */
    public function getEventTaggedPersonssJoinListEvents($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = EventTaggedPersonsQuery::create(null, $criteria);
        $query->joinWith('ListEvents', $join_behavior);

        return $this->getEventTaggedPersonss($query, $con);
    }

    /**
     * Clears the current object and sets all attributes to their default values
     */
    public function clear()
    {
        $this->id = null;
        $this->username = null;
        $this->password = null;
        $this->timestamp = null;
        $this->ip_add = null;
        $this->status = null;
        $this->email = null;
        $this->role = null;
        $this->team_role = null;
        $this->key = null;
        $this->created_by = null;
        $this->last_updated_by = null;
        $this->alreadyInSave = false;
        $this->alreadyInValidation = false;
        $this->alreadyInClearAllReferencesDeep = false;
        $this->clearAllReferences();
        $this->resetModified();
        $this->setNew(true);
        $this->setDeleted(false);
    }

    /**
     * Resets all references to other model objects or collections of model objects.
     *
     * This method is a user-space workaround for PHP's inability to garbage collect
     * objects with circular references (even in PHP 5.3). This is currently necessary
     * when using Propel in certain daemon or large-volume/high-memory operations.
     *
     * @param boolean $deep Whether to also clear the references on all referrer objects.
     */
    public function clearAllReferences($deep = false)
    {
        if ($deep && !$this->alreadyInClearAllReferencesDeep) {
            $this->alreadyInClearAllReferencesDeep = true;
            if ($this->collEmpRequestsRelatedByEmpAccId) {
                foreach ($this->collEmpRequestsRelatedByEmpAccId as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collEmpRequestsRelatedByAdminId) {
                foreach ($this->collEmpRequestsRelatedByAdminId as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collEmpProfiles) {
                foreach ($this->collEmpProfiles as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collEmpTimes) {
                foreach ($this->collEmpTimes as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collEmpCapabilitiess) {
                foreach ($this->collEmpCapabilitiess as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collListEventss) {
                foreach ($this->collListEventss as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collEventNotess) {
                foreach ($this->collEventNotess as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collEventTaggedPersonss) {
                foreach ($this->collEventTaggedPersonss as $o) {
                    $o->clearAllReferences($deep);
                }
            }

            $this->alreadyInClearAllReferencesDeep = false;
        } // if ($deep)

        if ($this->collEmpRequestsRelatedByEmpAccId instanceof PropelCollection) {
            $this->collEmpRequestsRelatedByEmpAccId->clearIterator();
        }
        $this->collEmpRequestsRelatedByEmpAccId = null;
        if ($this->collEmpRequestsRelatedByAdminId instanceof PropelCollection) {
            $this->collEmpRequestsRelatedByAdminId->clearIterator();
        }
        $this->collEmpRequestsRelatedByAdminId = null;
        if ($this->collEmpProfiles instanceof PropelCollection) {
            $this->collEmpProfiles->clearIterator();
        }
        $this->collEmpProfiles = null;
        if ($this->collEmpTimes instanceof PropelCollection) {
            $this->collEmpTimes->clearIterator();
        }
        $this->collEmpTimes = null;
        if ($this->collEmpCapabilitiess instanceof PropelCollection) {
            $this->collEmpCapabilitiess->clearIterator();
        }
        $this->collEmpCapabilitiess = null;
        if ($this->collListEventss instanceof PropelCollection) {
            $this->collListEventss->clearIterator();
        }
        $this->collListEventss = null;
        if ($this->collEventNotess instanceof PropelCollection) {
            $this->collEventNotess->clearIterator();
        }
        $this->collEventNotess = null;
        if ($this->collEventTaggedPersonss instanceof PropelCollection) {
            $this->collEventTaggedPersonss->clearIterator();
        }
        $this->collEventTaggedPersonss = null;
    }

    /**
     * return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(EmpAccPeer::DEFAULT_STRING_FORMAT);
    }

    /**
     * return true is the object is in saving state
     *
     * @return boolean
     */
    public function isAlreadyInSave()
    {
        return $this->alreadyInSave;
    }

}
