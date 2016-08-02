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
use CoreBundle\Model\EmpLeave;
use CoreBundle\Model\EmpLeaveQuery;
use CoreBundle\Model\EmpProfile;
use CoreBundle\Model\EmpProfileQuery;
use CoreBundle\Model\EmpTime;
use CoreBundle\Model\EmpTimeQuery;

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
     * @var        string
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
     * The value for the key field.
     * @var        string
     */
    protected $key;

    /**
     * @var        PropelObjectCollection|EmpLeave[] Collection to store aggregation of EmpLeave objects.
     */
    protected $collEmpLeavesRelatedByEmpAccId;
    protected $collEmpLeavesRelatedByEmpAccIdPartial;

    /**
     * @var        PropelObjectCollection|EmpLeave[] Collection to store aggregation of EmpLeave objects.
     */
    protected $collEmpLeavesRelatedByAdminId;
    protected $collEmpLeavesRelatedByAdminIdPartial;

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
    protected $empLeavesRelatedByEmpAccIdScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $empLeavesRelatedByAdminIdScheduledForDeletion = null;

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
     * @return string
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
     * Get the [key] column value.
     * 
     * @return string
     */
    public function getKey()
    {

        return $this->key;
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
     * @param  string $v new value
     * @return EmpAcc The current object (for fluent API support)
     */
    public function setStatus($v)
    {
        if ($v !== null) {
            $v = (string) $v;
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
            $this->status = ($row[$startcol + 5] !== null) ? (string) $row[$startcol + 5] : null;
            $this->email = ($row[$startcol + 6] !== null) ? (string) $row[$startcol + 6] : null;
            $this->role = ($row[$startcol + 7] !== null) ? (string) $row[$startcol + 7] : null;
            $this->key = ($row[$startcol + 8] !== null) ? (string) $row[$startcol + 8] : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }
            $this->postHydrate($row, $startcol, $rehydrate);

            return $startcol + 9; // 9 = EmpAccPeer::NUM_HYDRATE_COLUMNS.

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

            $this->collEmpLeavesRelatedByEmpAccId = null;

            $this->collEmpLeavesRelatedByAdminId = null;

            $this->collEmpProfiles = null;

            $this->collEmpTimes = null;

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

            if ($this->empLeavesRelatedByEmpAccIdScheduledForDeletion !== null) {
                if (!$this->empLeavesRelatedByEmpAccIdScheduledForDeletion->isEmpty()) {
                    EmpLeaveQuery::create()
                        ->filterByPrimaryKeys($this->empLeavesRelatedByEmpAccIdScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->empLeavesRelatedByEmpAccIdScheduledForDeletion = null;
                }
            }

            if ($this->collEmpLeavesRelatedByEmpAccId !== null) {
                foreach ($this->collEmpLeavesRelatedByEmpAccId as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->empLeavesRelatedByAdminIdScheduledForDeletion !== null) {
                if (!$this->empLeavesRelatedByAdminIdScheduledForDeletion->isEmpty()) {
                    EmpLeaveQuery::create()
                        ->filterByPrimaryKeys($this->empLeavesRelatedByAdminIdScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->empLeavesRelatedByAdminIdScheduledForDeletion = null;
                }
            }

            if ($this->collEmpLeavesRelatedByAdminId !== null) {
                foreach ($this->collEmpLeavesRelatedByAdminId as $referrerFK) {
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
        if ($this->isColumnModified(EmpAccPeer::KEY)) {
            $modifiedColumns[':p' . $index++]  = '`key`';
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
                        $stmt->bindValue($identifier, $this->status, PDO::PARAM_STR);
                        break;
                    case '`email`':						
                        $stmt->bindValue($identifier, $this->email, PDO::PARAM_STR);
                        break;
                    case '`role`':						
                        $stmt->bindValue($identifier, $this->role, PDO::PARAM_STR);
                        break;
                    case '`key`':						
                        $stmt->bindValue($identifier, $this->key, PDO::PARAM_STR);
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


                if ($this->collEmpLeavesRelatedByEmpAccId !== null) {
                    foreach ($this->collEmpLeavesRelatedByEmpAccId as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collEmpLeavesRelatedByAdminId !== null) {
                    foreach ($this->collEmpLeavesRelatedByAdminId as $referrerFK) {
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
                return $this->getKey();
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
            $keys[8] => $this->getKey(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }
        
        if ($includeForeignObjects) {
            if (null !== $this->collEmpLeavesRelatedByEmpAccId) {
                $result['EmpLeavesRelatedByEmpAccId'] = $this->collEmpLeavesRelatedByEmpAccId->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collEmpLeavesRelatedByAdminId) {
                $result['EmpLeavesRelatedByAdminId'] = $this->collEmpLeavesRelatedByAdminId->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collEmpProfiles) {
                $result['EmpProfiles'] = $this->collEmpProfiles->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collEmpTimes) {
                $result['EmpTimes'] = $this->collEmpTimes->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
                $this->setKey($value);
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
        if (array_key_exists($keys[8], $arr)) $this->setKey($arr[$keys[8]]);
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
        if ($this->isColumnModified(EmpAccPeer::KEY)) $criteria->add(EmpAccPeer::KEY, $this->key);

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
        $copyObj->setKey($this->getKey());

        if ($deepCopy && !$this->startCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);
            // store object hash to prevent cycle
            $this->startCopy = true;

            foreach ($this->getEmpLeavesRelatedByEmpAccId() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addEmpLeaveRelatedByEmpAccId($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getEmpLeavesRelatedByAdminId() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addEmpLeaveRelatedByAdminId($relObj->copy($deepCopy));
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
        if ('EmpLeaveRelatedByEmpAccId' == $relationName) {
            $this->initEmpLeavesRelatedByEmpAccId();
        }
        if ('EmpLeaveRelatedByAdminId' == $relationName) {
            $this->initEmpLeavesRelatedByAdminId();
        }
        if ('EmpProfile' == $relationName) {
            $this->initEmpProfiles();
        }
        if ('EmpTime' == $relationName) {
            $this->initEmpTimes();
        }
    }

    /**
     * Clears out the collEmpLeavesRelatedByEmpAccId collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return EmpAcc The current object (for fluent API support)
     * @see        addEmpLeavesRelatedByEmpAccId()
     */
    public function clearEmpLeavesRelatedByEmpAccId()
    {
        $this->collEmpLeavesRelatedByEmpAccId = null; // important to set this to null since that means it is uninitialized
        $this->collEmpLeavesRelatedByEmpAccIdPartial = null;

        return $this;
    }

    /**
     * reset is the collEmpLeavesRelatedByEmpAccId collection loaded partially
     *
     * @return void
     */
    public function resetPartialEmpLeavesRelatedByEmpAccId($v = true)
    {
        $this->collEmpLeavesRelatedByEmpAccIdPartial = $v;
    }

    /**
     * Initializes the collEmpLeavesRelatedByEmpAccId collection.
     *
     * By default this just sets the collEmpLeavesRelatedByEmpAccId collection to an empty array (like clearcollEmpLeavesRelatedByEmpAccId());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initEmpLeavesRelatedByEmpAccId($overrideExisting = true)
    {
        if (null !== $this->collEmpLeavesRelatedByEmpAccId && !$overrideExisting) {
            return;
        }
        $this->collEmpLeavesRelatedByEmpAccId = new PropelObjectCollection();
        $this->collEmpLeavesRelatedByEmpAccId->setModel('EmpLeave');
    }

    /**
     * Gets an array of EmpLeave objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this EmpAcc is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|EmpLeave[] List of EmpLeave objects
     * @throws PropelException
     */
    public function getEmpLeavesRelatedByEmpAccId($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collEmpLeavesRelatedByEmpAccIdPartial && !$this->isNew();
        if (null === $this->collEmpLeavesRelatedByEmpAccId || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collEmpLeavesRelatedByEmpAccId) {
                // return empty collection
                $this->initEmpLeavesRelatedByEmpAccId();
            } else {
                $collEmpLeavesRelatedByEmpAccId = EmpLeaveQuery::create(null, $criteria)
                    ->filterByEmpAccRelatedByEmpAccId($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collEmpLeavesRelatedByEmpAccIdPartial && count($collEmpLeavesRelatedByEmpAccId)) {
                      $this->initEmpLeavesRelatedByEmpAccId(false);

                      foreach ($collEmpLeavesRelatedByEmpAccId as $obj) {
                        if (false == $this->collEmpLeavesRelatedByEmpAccId->contains($obj)) {
                          $this->collEmpLeavesRelatedByEmpAccId->append($obj);
                        }
                      }

                      $this->collEmpLeavesRelatedByEmpAccIdPartial = true;
                    }

                    $collEmpLeavesRelatedByEmpAccId->getInternalIterator()->rewind();

                    return $collEmpLeavesRelatedByEmpAccId;
                }

                if ($partial && $this->collEmpLeavesRelatedByEmpAccId) {
                    foreach ($this->collEmpLeavesRelatedByEmpAccId as $obj) {
                        if ($obj->isNew()) {
                            $collEmpLeavesRelatedByEmpAccId[] = $obj;
                        }
                    }
                }

                $this->collEmpLeavesRelatedByEmpAccId = $collEmpLeavesRelatedByEmpAccId;
                $this->collEmpLeavesRelatedByEmpAccIdPartial = false;
            }
        }

        return $this->collEmpLeavesRelatedByEmpAccId;
    }

    /**
     * Sets a collection of EmpLeaveRelatedByEmpAccId objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $empLeavesRelatedByEmpAccId A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return EmpAcc The current object (for fluent API support)
     */
    public function setEmpLeavesRelatedByEmpAccId(PropelCollection $empLeavesRelatedByEmpAccId, PropelPDO $con = null)
    {
        $empLeavesRelatedByEmpAccIdToDelete = $this->getEmpLeavesRelatedByEmpAccId(new Criteria(), $con)->diff($empLeavesRelatedByEmpAccId);


        $this->empLeavesRelatedByEmpAccIdScheduledForDeletion = $empLeavesRelatedByEmpAccIdToDelete;

        foreach ($empLeavesRelatedByEmpAccIdToDelete as $empLeaveRelatedByEmpAccIdRemoved) {
            $empLeaveRelatedByEmpAccIdRemoved->setEmpAccRelatedByEmpAccId(null);
        }

        $this->collEmpLeavesRelatedByEmpAccId = null;
        foreach ($empLeavesRelatedByEmpAccId as $empLeaveRelatedByEmpAccId) {
            $this->addEmpLeaveRelatedByEmpAccId($empLeaveRelatedByEmpAccId);
        }

        $this->collEmpLeavesRelatedByEmpAccId = $empLeavesRelatedByEmpAccId;
        $this->collEmpLeavesRelatedByEmpAccIdPartial = false;

        return $this;
    }

    /**
     * Returns the number of related EmpLeave objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related EmpLeave objects.
     * @throws PropelException
     */
    public function countEmpLeavesRelatedByEmpAccId(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collEmpLeavesRelatedByEmpAccIdPartial && !$this->isNew();
        if (null === $this->collEmpLeavesRelatedByEmpAccId || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collEmpLeavesRelatedByEmpAccId) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getEmpLeavesRelatedByEmpAccId());
            }
            $query = EmpLeaveQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByEmpAccRelatedByEmpAccId($this)
                ->count($con);
        }

        return count($this->collEmpLeavesRelatedByEmpAccId);
    }

    /**
     * Method called to associate a EmpLeave object to this object
     * through the EmpLeave foreign key attribute.
     *
     * @param    EmpLeave $l EmpLeave
     * @return EmpAcc The current object (for fluent API support)
     */
    public function addEmpLeaveRelatedByEmpAccId(EmpLeave $l)
    {
        if ($this->collEmpLeavesRelatedByEmpAccId === null) {
            $this->initEmpLeavesRelatedByEmpAccId();
            $this->collEmpLeavesRelatedByEmpAccIdPartial = true;
        }

        if (!in_array($l, $this->collEmpLeavesRelatedByEmpAccId->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddEmpLeaveRelatedByEmpAccId($l);

            if ($this->empLeavesRelatedByEmpAccIdScheduledForDeletion and $this->empLeavesRelatedByEmpAccIdScheduledForDeletion->contains($l)) {
                $this->empLeavesRelatedByEmpAccIdScheduledForDeletion->remove($this->empLeavesRelatedByEmpAccIdScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	EmpLeaveRelatedByEmpAccId $empLeaveRelatedByEmpAccId The empLeaveRelatedByEmpAccId object to add.
     */
    protected function doAddEmpLeaveRelatedByEmpAccId($empLeaveRelatedByEmpAccId)
    {
        $this->collEmpLeavesRelatedByEmpAccId[]= $empLeaveRelatedByEmpAccId;
        $empLeaveRelatedByEmpAccId->setEmpAccRelatedByEmpAccId($this);
    }

    /**
     * @param	EmpLeaveRelatedByEmpAccId $empLeaveRelatedByEmpAccId The empLeaveRelatedByEmpAccId object to remove.
     * @return EmpAcc The current object (for fluent API support)
     */
    public function removeEmpLeaveRelatedByEmpAccId($empLeaveRelatedByEmpAccId)
    {
        if ($this->getEmpLeavesRelatedByEmpAccId()->contains($empLeaveRelatedByEmpAccId)) {
            $this->collEmpLeavesRelatedByEmpAccId->remove($this->collEmpLeavesRelatedByEmpAccId->search($empLeaveRelatedByEmpAccId));
            if (null === $this->empLeavesRelatedByEmpAccIdScheduledForDeletion) {
                $this->empLeavesRelatedByEmpAccIdScheduledForDeletion = clone $this->collEmpLeavesRelatedByEmpAccId;
                $this->empLeavesRelatedByEmpAccIdScheduledForDeletion->clear();
            }
            $this->empLeavesRelatedByEmpAccIdScheduledForDeletion[]= clone $empLeaveRelatedByEmpAccId;
            $empLeaveRelatedByEmpAccId->setEmpAccRelatedByEmpAccId(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this EmpAcc is new, it will return
     * an empty collection; or if this EmpAcc has previously
     * been saved, it will retrieve related EmpLeavesRelatedByEmpAccId from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in EmpAcc.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|EmpLeave[] List of EmpLeave objects
     */
    public function getEmpLeavesRelatedByEmpAccIdJoinListLeaveType($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = EmpLeaveQuery::create(null, $criteria);
        $query->joinWith('ListLeaveType', $join_behavior);

        return $this->getEmpLeavesRelatedByEmpAccId($query, $con);
    }

    /**
     * Clears out the collEmpLeavesRelatedByAdminId collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return EmpAcc The current object (for fluent API support)
     * @see        addEmpLeavesRelatedByAdminId()
     */
    public function clearEmpLeavesRelatedByAdminId()
    {
        $this->collEmpLeavesRelatedByAdminId = null; // important to set this to null since that means it is uninitialized
        $this->collEmpLeavesRelatedByAdminIdPartial = null;

        return $this;
    }

    /**
     * reset is the collEmpLeavesRelatedByAdminId collection loaded partially
     *
     * @return void
     */
    public function resetPartialEmpLeavesRelatedByAdminId($v = true)
    {
        $this->collEmpLeavesRelatedByAdminIdPartial = $v;
    }

    /**
     * Initializes the collEmpLeavesRelatedByAdminId collection.
     *
     * By default this just sets the collEmpLeavesRelatedByAdminId collection to an empty array (like clearcollEmpLeavesRelatedByAdminId());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initEmpLeavesRelatedByAdminId($overrideExisting = true)
    {
        if (null !== $this->collEmpLeavesRelatedByAdminId && !$overrideExisting) {
            return;
        }
        $this->collEmpLeavesRelatedByAdminId = new PropelObjectCollection();
        $this->collEmpLeavesRelatedByAdminId->setModel('EmpLeave');
    }

    /**
     * Gets an array of EmpLeave objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this EmpAcc is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|EmpLeave[] List of EmpLeave objects
     * @throws PropelException
     */
    public function getEmpLeavesRelatedByAdminId($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collEmpLeavesRelatedByAdminIdPartial && !$this->isNew();
        if (null === $this->collEmpLeavesRelatedByAdminId || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collEmpLeavesRelatedByAdminId) {
                // return empty collection
                $this->initEmpLeavesRelatedByAdminId();
            } else {
                $collEmpLeavesRelatedByAdminId = EmpLeaveQuery::create(null, $criteria)
                    ->filterByEmpAccRelatedByAdminId($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collEmpLeavesRelatedByAdminIdPartial && count($collEmpLeavesRelatedByAdminId)) {
                      $this->initEmpLeavesRelatedByAdminId(false);

                      foreach ($collEmpLeavesRelatedByAdminId as $obj) {
                        if (false == $this->collEmpLeavesRelatedByAdminId->contains($obj)) {
                          $this->collEmpLeavesRelatedByAdminId->append($obj);
                        }
                      }

                      $this->collEmpLeavesRelatedByAdminIdPartial = true;
                    }

                    $collEmpLeavesRelatedByAdminId->getInternalIterator()->rewind();

                    return $collEmpLeavesRelatedByAdminId;
                }

                if ($partial && $this->collEmpLeavesRelatedByAdminId) {
                    foreach ($this->collEmpLeavesRelatedByAdminId as $obj) {
                        if ($obj->isNew()) {
                            $collEmpLeavesRelatedByAdminId[] = $obj;
                        }
                    }
                }

                $this->collEmpLeavesRelatedByAdminId = $collEmpLeavesRelatedByAdminId;
                $this->collEmpLeavesRelatedByAdminIdPartial = false;
            }
        }

        return $this->collEmpLeavesRelatedByAdminId;
    }

    /**
     * Sets a collection of EmpLeaveRelatedByAdminId objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $empLeavesRelatedByAdminId A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return EmpAcc The current object (for fluent API support)
     */
    public function setEmpLeavesRelatedByAdminId(PropelCollection $empLeavesRelatedByAdminId, PropelPDO $con = null)
    {
        $empLeavesRelatedByAdminIdToDelete = $this->getEmpLeavesRelatedByAdminId(new Criteria(), $con)->diff($empLeavesRelatedByAdminId);


        $this->empLeavesRelatedByAdminIdScheduledForDeletion = $empLeavesRelatedByAdminIdToDelete;

        foreach ($empLeavesRelatedByAdminIdToDelete as $empLeaveRelatedByAdminIdRemoved) {
            $empLeaveRelatedByAdminIdRemoved->setEmpAccRelatedByAdminId(null);
        }

        $this->collEmpLeavesRelatedByAdminId = null;
        foreach ($empLeavesRelatedByAdminId as $empLeaveRelatedByAdminId) {
            $this->addEmpLeaveRelatedByAdminId($empLeaveRelatedByAdminId);
        }

        $this->collEmpLeavesRelatedByAdminId = $empLeavesRelatedByAdminId;
        $this->collEmpLeavesRelatedByAdminIdPartial = false;

        return $this;
    }

    /**
     * Returns the number of related EmpLeave objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related EmpLeave objects.
     * @throws PropelException
     */
    public function countEmpLeavesRelatedByAdminId(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collEmpLeavesRelatedByAdminIdPartial && !$this->isNew();
        if (null === $this->collEmpLeavesRelatedByAdminId || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collEmpLeavesRelatedByAdminId) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getEmpLeavesRelatedByAdminId());
            }
            $query = EmpLeaveQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByEmpAccRelatedByAdminId($this)
                ->count($con);
        }

        return count($this->collEmpLeavesRelatedByAdminId);
    }

    /**
     * Method called to associate a EmpLeave object to this object
     * through the EmpLeave foreign key attribute.
     *
     * @param    EmpLeave $l EmpLeave
     * @return EmpAcc The current object (for fluent API support)
     */
    public function addEmpLeaveRelatedByAdminId(EmpLeave $l)
    {
        if ($this->collEmpLeavesRelatedByAdminId === null) {
            $this->initEmpLeavesRelatedByAdminId();
            $this->collEmpLeavesRelatedByAdminIdPartial = true;
        }

        if (!in_array($l, $this->collEmpLeavesRelatedByAdminId->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddEmpLeaveRelatedByAdminId($l);

            if ($this->empLeavesRelatedByAdminIdScheduledForDeletion and $this->empLeavesRelatedByAdminIdScheduledForDeletion->contains($l)) {
                $this->empLeavesRelatedByAdminIdScheduledForDeletion->remove($this->empLeavesRelatedByAdminIdScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	EmpLeaveRelatedByAdminId $empLeaveRelatedByAdminId The empLeaveRelatedByAdminId object to add.
     */
    protected function doAddEmpLeaveRelatedByAdminId($empLeaveRelatedByAdminId)
    {
        $this->collEmpLeavesRelatedByAdminId[]= $empLeaveRelatedByAdminId;
        $empLeaveRelatedByAdminId->setEmpAccRelatedByAdminId($this);
    }

    /**
     * @param	EmpLeaveRelatedByAdminId $empLeaveRelatedByAdminId The empLeaveRelatedByAdminId object to remove.
     * @return EmpAcc The current object (for fluent API support)
     */
    public function removeEmpLeaveRelatedByAdminId($empLeaveRelatedByAdminId)
    {
        if ($this->getEmpLeavesRelatedByAdminId()->contains($empLeaveRelatedByAdminId)) {
            $this->collEmpLeavesRelatedByAdminId->remove($this->collEmpLeavesRelatedByAdminId->search($empLeaveRelatedByAdminId));
            if (null === $this->empLeavesRelatedByAdminIdScheduledForDeletion) {
                $this->empLeavesRelatedByAdminIdScheduledForDeletion = clone $this->collEmpLeavesRelatedByAdminId;
                $this->empLeavesRelatedByAdminIdScheduledForDeletion->clear();
            }
            $this->empLeavesRelatedByAdminIdScheduledForDeletion[]= clone $empLeaveRelatedByAdminId;
            $empLeaveRelatedByAdminId->setEmpAccRelatedByAdminId(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this EmpAcc is new, it will return
     * an empty collection; or if this EmpAcc has previously
     * been saved, it will retrieve related EmpLeavesRelatedByAdminId from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in EmpAcc.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|EmpLeave[] List of EmpLeave objects
     */
    public function getEmpLeavesRelatedByAdminIdJoinListLeaveType($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = EmpLeaveQuery::create(null, $criteria);
        $query->joinWith('ListLeaveType', $join_behavior);

        return $this->getEmpLeavesRelatedByAdminId($query, $con);
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
        $this->key = null;
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
            if ($this->collEmpLeavesRelatedByEmpAccId) {
                foreach ($this->collEmpLeavesRelatedByEmpAccId as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collEmpLeavesRelatedByAdminId) {
                foreach ($this->collEmpLeavesRelatedByAdminId as $o) {
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

            $this->alreadyInClearAllReferencesDeep = false;
        } // if ($deep)

        if ($this->collEmpLeavesRelatedByEmpAccId instanceof PropelCollection) {
            $this->collEmpLeavesRelatedByEmpAccId->clearIterator();
        }
        $this->collEmpLeavesRelatedByEmpAccId = null;
        if ($this->collEmpLeavesRelatedByAdminId instanceof PropelCollection) {
            $this->collEmpLeavesRelatedByAdminId->clearIterator();
        }
        $this->collEmpLeavesRelatedByAdminId = null;
        if ($this->collEmpProfiles instanceof PropelCollection) {
            $this->collEmpProfiles->clearIterator();
        }
        $this->collEmpProfiles = null;
        if ($this->collEmpTimes instanceof PropelCollection) {
            $this->collEmpTimes->clearIterator();
        }
        $this->collEmpTimes = null;
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
