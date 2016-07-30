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
use CoreBundle\Model\EmpAccQuery;
use CoreBundle\Model\EmpContact;
use CoreBundle\Model\EmpContactQuery;
use CoreBundle\Model\EmpProfile;
use CoreBundle\Model\EmpProfilePeer;
use CoreBundle\Model\EmpProfileQuery;
use CoreBundle\Model\ListDept;
use CoreBundle\Model\ListDeptQuery;
use CoreBundle\Model\ListPos;
use CoreBundle\Model\ListPosQuery;

abstract class BaseEmpProfile extends BaseObject implements Persistent
{
    /**
     * Peer class name
     */
    const PEER = 'CoreBundle\\Model\\EmpProfilePeer';

    /**
     * The Peer class.
     * Instance provides a convenient way of calling static methods on a class
     * that calling code may not be able to identify.
     * @var        EmpProfilePeer
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
     * The value for the emp_acc_acc_id field.
     * @var        int
     */
    protected $emp_acc_acc_id;

    /**
     * The value for the fname field.
     * @var        string
     */
    protected $fname;

    /**
     * The value for the lname field.
     * @var        string
     */
    protected $lname;

    /**
     * The value for the mname field.
     * @var        string
     */
    protected $mname;

    /**
     * The value for the bday field.
     * @var        string
     */
    protected $bday;

    /**
     * The value for the address field.
     * @var        string
     */
    protected $address;

    /**
     * The value for the gender field.
     * @var        string
     */
    protected $gender;

    /**
     * The value for the img_path field.
     * @var        string
     */
    protected $img_path;

    /**
     * The value for the date_joined field.
     * @var        string
     */
    protected $date_joined;

    /**
     * The value for the emp_num field.
     * @var        string
     */
    protected $emp_num;

    /**
     * The value for the list_dept_id field.
     * @var        int
     */
    protected $list_dept_id;

    /**
     * The value for the list_pos_id field.
     * @var        int
     */
    protected $list_pos_id;

    /**
     * The value for the status field.
     * @var        string
     */
    protected $status;

    /**
     * The value for the profile_status field.
     * @var        int
     */
    protected $profile_status;

    /**
     * @var        EmpAcc
     */
    protected $aEmpAcc;

    /**
     * @var        ListDept
     */
    protected $aListDept;

    /**
     * @var        ListPos
     */
    protected $aListPos;

    /**
     * @var        PropelObjectCollection|EmpContact[] Collection to store aggregation of EmpContact objects.
     */
    protected $collEmpContacts;
    protected $collEmpContactsPartial;

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
    protected $empContactsScheduledForDeletion = null;

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
     * Get the [emp_acc_acc_id] column value.
     *
     * @return int
     */
    public function getEmpAccAccId()
    {

        return $this->emp_acc_acc_id;
    }

    /**
     * Get the [fname] column value.
     *
     * @return string
     */
    public function getFname()
    {

        return $this->fname;
    }

    /**
     * Get the [lname] column value.
     *
     * @return string
     */
    public function getLname()
    {

        return $this->lname;
    }

    /**
     * Get the [mname] column value.
     *
     * @return string
     */
    public function getMname()
    {

        return $this->mname;
    }

    /**
     * Get the [optionally formatted] temporal [bday] column value.
     *
     *
     * @param string $format The date/time format string (either date()-style or strftime()-style).
     *				 If format is null, then the raw DateTime object will be returned.
     * @return mixed Formatted date/time value as string or DateTime object (if format is null), null if column is null, and 0 if column value is 0000-00-00 00:00:00
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getBday($format = null)
    {
        if ($this->bday === null) {
            return null;
        }

        if ($this->bday === '0000-00-00 00:00:00') {
            // while technically this is not a default value of null,
            // this seems to be closest in meaning.
            return null;
        }

        try {
            $dt = new DateTime($this->bday);
        } catch (Exception $x) {
            throw new PropelException("Internally stored date/time/timestamp value could not be converted to DateTime: " . var_export($this->bday, true), $x);
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
     * Get the [address] column value.
     *
     * @return string
     */
    public function getAddress()
    {

        return $this->address;
    }

    /**
     * Get the [gender] column value.
     *
     * @return string
     */
    public function getGender()
    {

        return $this->gender;
    }

    /**
     * Get the [img_path] column value.
     *
     * @return string
     */
    public function getImgPath()
    {

        return $this->img_path;
    }

    /**
     * Get the [optionally formatted] temporal [date_joined] column value.
     *
     *
     * @param string $format The date/time format string (either date()-style or strftime()-style).
     *				 If format is null, then the raw DateTime object will be returned.
     * @return mixed Formatted date/time value as string or DateTime object (if format is null), null if column is null, and 0 if column value is 0000-00-00 00:00:00
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getDateJoined($format = null)
    {
        if ($this->date_joined === null) {
            return null;
        }

        if ($this->date_joined === '0000-00-00 00:00:00') {
            // while technically this is not a default value of null,
            // this seems to be closest in meaning.
            return null;
        }

        try {
            $dt = new DateTime($this->date_joined);
        } catch (Exception $x) {
            throw new PropelException("Internally stored date/time/timestamp value could not be converted to DateTime: " . var_export($this->date_joined, true), $x);
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
     * Get the [emp_num] column value.
     *
     * @return string
     */
    public function getEmployeeNumber()
    {

        return $this->emp_num;
    }

    /**
     * Get the [list_dept_id] column value.
     *
     * @return int
     */
    public function getListDeptDeptId()
    {

        return $this->list_dept_id;
    }

    /**
     * Get the [list_pos_id] column value.
     *
     * @return int
     */
    public function getListPosPosId()
    {

        return $this->list_pos_id;
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
     * Get the [profile_status] column value.
     *
     * @return int
     */
    public function getProfileStatus()
    {

        return $this->profile_status;
    }

    /**
     * Set the value of [id] column.
     *
     * @param  int $v new value
     * @return EmpProfile The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[] = EmpProfilePeer::ID;
        }


        return $this;
    } // setId()

    /**
     * Set the value of [emp_acc_acc_id] column.
     *
     * @param  int $v new value
     * @return EmpProfile The current object (for fluent API support)
     */
    public function setEmpAccAccId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->emp_acc_acc_id !== $v) {
            $this->emp_acc_acc_id = $v;
            $this->modifiedColumns[] = EmpProfilePeer::EMP_ACC_ACC_ID;
        }

        if ($this->aEmpAcc !== null && $this->aEmpAcc->getId() !== $v) {
            $this->aEmpAcc = null;
        }


        return $this;
    } // setEmpAccAccId()

    /**
     * Set the value of [fname] column.
     *
     * @param  string $v new value
     * @return EmpProfile The current object (for fluent API support)
     */
    public function setFname($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->fname !== $v) {
            $this->fname = $v;
            $this->modifiedColumns[] = EmpProfilePeer::FNAME;
        }


        return $this;
    } // setFname()

    /**
     * Set the value of [lname] column.
     *
     * @param  string $v new value
     * @return EmpProfile The current object (for fluent API support)
     */
    public function setLname($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->lname !== $v) {
            $this->lname = $v;
            $this->modifiedColumns[] = EmpProfilePeer::LNAME;
        }


        return $this;
    } // setLname()

    /**
     * Set the value of [mname] column.
     *
     * @param  string $v new value
     * @return EmpProfile The current object (for fluent API support)
     */
    public function setMname($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->mname !== $v) {
            $this->mname = $v;
            $this->modifiedColumns[] = EmpProfilePeer::MNAME;
        }


        return $this;
    } // setMname()

    /**
     * Sets the value of [bday] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return EmpProfile The current object (for fluent API support)
     */
    public function setBday($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->bday !== null || $dt !== null) {
            $currentDateAsString = ($this->bday !== null && $tmpDt = new DateTime($this->bday)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->bday = $newDateAsString;
                $this->modifiedColumns[] = EmpProfilePeer::BDAY;
            }
        } // if either are not null


        return $this;
    } // setBday()

    /**
     * Set the value of [address] column.
     *
     * @param  string $v new value
     * @return EmpProfile The current object (for fluent API support)
     */
    public function setAddress($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->address !== $v) {
            $this->address = $v;
            $this->modifiedColumns[] = EmpProfilePeer::ADDRESS;
        }


        return $this;
    } // setAddress()

    /**
     * Set the value of [gender] column.
     *
     * @param  string $v new value
     * @return EmpProfile The current object (for fluent API support)
     */
    public function setGender($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->gender !== $v) {
            $this->gender = $v;
            $this->modifiedColumns[] = EmpProfilePeer::GENDER;
        }


        return $this;
    } // setGender()

    /**
     * Set the value of [img_path] column.
     *
     * @param  string $v new value
     * @return EmpProfile The current object (for fluent API support)
     */
    public function setImgPath($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->img_path !== $v) {
            $this->img_path = $v;
            $this->modifiedColumns[] = EmpProfilePeer::IMG_PATH;
        }


        return $this;
    } // setImgPath()

    /**
     * Sets the value of [date_joined] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return EmpProfile The current object (for fluent API support)
     */
    public function setDateJoined($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->date_joined !== null || $dt !== null) {
            $currentDateAsString = ($this->date_joined !== null && $tmpDt = new DateTime($this->date_joined)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->date_joined = $newDateAsString;
                $this->modifiedColumns[] = EmpProfilePeer::DATE_JOINED;
            }
        } // if either are not null


        return $this;
    } // setDateJoined()

    /**
     * Set the value of [emp_num] column.
     *
     * @param  string $v new value
     * @return EmpProfile The current object (for fluent API support)
     */
    public function setEmployeeNumber($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->emp_num !== $v) {
            $this->emp_num = $v;
            $this->modifiedColumns[] = EmpProfilePeer::EMP_NUM;
        }


        return $this;
    } // setEmployeeNumber()

    /**
     * Set the value of [list_dept_id] column.
     *
     * @param  int $v new value
     * @return EmpProfile The current object (for fluent API support)
     */
    public function setListDeptDeptId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->list_dept_id !== $v) {
            $this->list_dept_id = $v;
            $this->modifiedColumns[] = EmpProfilePeer::LIST_DEPT_ID;
        }

        if ($this->aListDept !== null && $this->aListDept->getId() !== $v) {
            $this->aListDept = null;
        }


        return $this;
    } // setListDeptDeptId()

    /**
     * Set the value of [list_pos_id] column.
     *
     * @param  int $v new value
     * @return EmpProfile The current object (for fluent API support)
     */
    public function setListPosPosId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->list_pos_id !== $v) {
            $this->list_pos_id = $v;
            $this->modifiedColumns[] = EmpProfilePeer::LIST_POS_ID;
        }

        if ($this->aListPos !== null && $this->aListPos->getId() !== $v) {
            $this->aListPos = null;
        }


        return $this;
    } // setListPosPosId()

    /**
     * Set the value of [status] column.
     *
     * @param  string $v new value
     * @return EmpProfile The current object (for fluent API support)
     */
    public function setStatus($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->status !== $v) {
            $this->status = $v;
            $this->modifiedColumns[] = EmpProfilePeer::STATUS;
        }


        return $this;
    } // setStatus()

    /**
     * Set the value of [profile_status] column.
     *
     * @param  int $v new value
     * @return EmpProfile The current object (for fluent API support)
     */
    public function setProfileStatus($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->profile_status !== $v) {
            $this->profile_status = $v;
            $this->modifiedColumns[] = EmpProfilePeer::PROFILE_STATUS;
        }


        return $this;
    } // setProfileStatus()

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
            $this->emp_acc_acc_id = ($row[$startcol + 1] !== null) ? (int) $row[$startcol + 1] : null;
            $this->fname = ($row[$startcol + 2] !== null) ? (string) $row[$startcol + 2] : null;
            $this->lname = ($row[$startcol + 3] !== null) ? (string) $row[$startcol + 3] : null;
            $this->mname = ($row[$startcol + 4] !== null) ? (string) $row[$startcol + 4] : null;
            $this->bday = ($row[$startcol + 5] !== null) ? (string) $row[$startcol + 5] : null;
            $this->address = ($row[$startcol + 6] !== null) ? (string) $row[$startcol + 6] : null;
            $this->gender = ($row[$startcol + 7] !== null) ? (string) $row[$startcol + 7] : null;
            $this->img_path = ($row[$startcol + 8] !== null) ? (string) $row[$startcol + 8] : null;
            $this->date_joined = ($row[$startcol + 9] !== null) ? (string) $row[$startcol + 9] : null;
            $this->emp_num = ($row[$startcol + 10] !== null) ? (string) $row[$startcol + 10] : null;
            $this->list_dept_id = ($row[$startcol + 11] !== null) ? (int) $row[$startcol + 11] : null;
            $this->list_pos_id = ($row[$startcol + 12] !== null) ? (int) $row[$startcol + 12] : null;
            $this->status = ($row[$startcol + 13] !== null) ? (string) $row[$startcol + 13] : null;
            $this->profile_status = ($row[$startcol + 14] !== null) ? (int) $row[$startcol + 14] : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }
            $this->postHydrate($row, $startcol, $rehydrate);

            return $startcol + 15; // 15 = EmpProfilePeer::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException("Error populating EmpProfile object", $e);
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

        if ($this->aEmpAcc !== null && $this->emp_acc_acc_id !== $this->aEmpAcc->getId()) {
            $this->aEmpAcc = null;
        }
        if ($this->aListDept !== null && $this->list_dept_id !== $this->aListDept->getId()) {
            $this->aListDept = null;
        }
        if ($this->aListPos !== null && $this->list_pos_id !== $this->aListPos->getId()) {
            $this->aListPos = null;
        }
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
            $con = Propel::getConnection(EmpProfilePeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $stmt = EmpProfilePeer::doSelectStmt($this->buildPkeyCriteria(), $con);
        $row = $stmt->fetch(PDO::FETCH_NUM);
        $stmt->closeCursor();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aEmpAcc = null;
            $this->aListDept = null;
            $this->aListPos = null;
            $this->collEmpContacts = null;

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
            $con = Propel::getConnection(EmpProfilePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        try {
            $deleteQuery = EmpProfileQuery::create()
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
            $con = Propel::getConnection(EmpProfilePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
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
                EmpProfilePeer::addInstanceToPool($this);
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

            // We call the save method on the following object(s) if they
            // were passed to this object by their corresponding set
            // method.  This object relates to these object(s) by a
            // foreign key reference.

            if ($this->aEmpAcc !== null) {
                if ($this->aEmpAcc->isModified() || $this->aEmpAcc->isNew()) {
                    $affectedRows += $this->aEmpAcc->save($con);
                }
                $this->setEmpAcc($this->aEmpAcc);
            }

            if ($this->aListDept !== null) {
                if ($this->aListDept->isModified() || $this->aListDept->isNew()) {
                    $affectedRows += $this->aListDept->save($con);
                }
                $this->setListDept($this->aListDept);
            }

            if ($this->aListPos !== null) {
                if ($this->aListPos->isModified() || $this->aListPos->isNew()) {
                    $affectedRows += $this->aListPos->save($con);
                }
                $this->setListPos($this->aListPos);
            }

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

            if ($this->empContactsScheduledForDeletion !== null) {
                if (!$this->empContactsScheduledForDeletion->isEmpty()) {
                    EmpContactQuery::create()
                        ->filterByPrimaryKeys($this->empContactsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->empContactsScheduledForDeletion = null;
                }
            }

            if ($this->collEmpContacts !== null) {
                foreach ($this->collEmpContacts as $referrerFK) {
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

        $this->modifiedColumns[] = EmpProfilePeer::ID;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . EmpProfilePeer::ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(EmpProfilePeer::ID)) {
            $modifiedColumns[':p' . $index++]  = '`id`';
        }
        if ($this->isColumnModified(EmpProfilePeer::EMP_ACC_ACC_ID)) {
            $modifiedColumns[':p' . $index++]  = '`emp_acc_acc_id`';
        }
        if ($this->isColumnModified(EmpProfilePeer::FNAME)) {
            $modifiedColumns[':p' . $index++]  = '`fname`';
        }
        if ($this->isColumnModified(EmpProfilePeer::LNAME)) {
            $modifiedColumns[':p' . $index++]  = '`lname`';
        }
        if ($this->isColumnModified(EmpProfilePeer::MNAME)) {
            $modifiedColumns[':p' . $index++]  = '`mname`';
        }
        if ($this->isColumnModified(EmpProfilePeer::BDAY)) {
            $modifiedColumns[':p' . $index++]  = '`bday`';
        }
        if ($this->isColumnModified(EmpProfilePeer::ADDRESS)) {
            $modifiedColumns[':p' . $index++]  = '`address`';
        }
        if ($this->isColumnModified(EmpProfilePeer::GENDER)) {
            $modifiedColumns[':p' . $index++]  = '`gender`';
        }
        if ($this->isColumnModified(EmpProfilePeer::IMG_PATH)) {
            $modifiedColumns[':p' . $index++]  = '`img_path`';
        }
        if ($this->isColumnModified(EmpProfilePeer::DATE_JOINED)) {
            $modifiedColumns[':p' . $index++]  = '`date_joined`';
        }
        if ($this->isColumnModified(EmpProfilePeer::EMP_NUM)) {
            $modifiedColumns[':p' . $index++]  = '`emp_num`';
        }
        if ($this->isColumnModified(EmpProfilePeer::LIST_DEPT_ID)) {
            $modifiedColumns[':p' . $index++]  = '`list_dept_id`';
        }
        if ($this->isColumnModified(EmpProfilePeer::LIST_POS_ID)) {
            $modifiedColumns[':p' . $index++]  = '`list_pos_id`';
        }
        if ($this->isColumnModified(EmpProfilePeer::STATUS)) {
            $modifiedColumns[':p' . $index++]  = '`status`';
        }
        if ($this->isColumnModified(EmpProfilePeer::PROFILE_STATUS)) {
            $modifiedColumns[':p' . $index++]  = '`profile_status`';
        }

        $sql = sprintf(
            'INSERT INTO `emp_profile` (%s) VALUES (%s)',
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
                    case '`emp_acc_acc_id`':
                        $stmt->bindValue($identifier, $this->emp_acc_acc_id, PDO::PARAM_INT);
                        break;
                    case '`fname`':
                        $stmt->bindValue($identifier, $this->fname, PDO::PARAM_STR);
                        break;
                    case '`lname`':
                        $stmt->bindValue($identifier, $this->lname, PDO::PARAM_STR);
                        break;
                    case '`mname`':
                        $stmt->bindValue($identifier, $this->mname, PDO::PARAM_STR);
                        break;
                    case '`bday`':
                        $stmt->bindValue($identifier, $this->bday, PDO::PARAM_STR);
                        break;
                    case '`address`':
                        $stmt->bindValue($identifier, $this->address, PDO::PARAM_STR);
                        break;
                    case '`gender`':
                        $stmt->bindValue($identifier, $this->gender, PDO::PARAM_STR);
                        break;
                    case '`img_path`':
                        $stmt->bindValue($identifier, $this->img_path, PDO::PARAM_STR);
                        break;
                    case '`date_joined`':
                        $stmt->bindValue($identifier, $this->date_joined, PDO::PARAM_STR);
                        break;
                    case '`emp_num`':
                        $stmt->bindValue($identifier, $this->emp_num, PDO::PARAM_STR);
                        break;
                    case '`list_dept_id`':
                        $stmt->bindValue($identifier, $this->list_dept_id, PDO::PARAM_INT);
                        break;
                    case '`list_pos_id`':
                        $stmt->bindValue($identifier, $this->list_pos_id, PDO::PARAM_INT);
                        break;
                    case '`status`':
                        $stmt->bindValue($identifier, $this->status, PDO::PARAM_STR);
                        break;
                    case '`profile_status`':
                        $stmt->bindValue($identifier, $this->profile_status, PDO::PARAM_INT);
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


            // We call the validate method on the following object(s) if they
            // were passed to this object by their corresponding set
            // method.  This object relates to these object(s) by a
            // foreign key reference.

            if ($this->aEmpAcc !== null) {
                if (!$this->aEmpAcc->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aEmpAcc->getValidationFailures());
                }
            }

            if ($this->aListDept !== null) {
                if (!$this->aListDept->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aListDept->getValidationFailures());
                }
            }

            if ($this->aListPos !== null) {
                if (!$this->aListPos->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aListPos->getValidationFailures());
                }
            }


            if (($retval = EmpProfilePeer::doValidate($this, $columns)) !== true) {
                $failureMap = array_merge($failureMap, $retval);
            }


                if ($this->collEmpContacts !== null) {
                    foreach ($this->collEmpContacts as $referrerFK) {
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
        $pos = EmpProfilePeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
                return $this->getEmpAccAccId();
                break;
            case 2:
                return $this->getFname();
                break;
            case 3:
                return $this->getLname();
                break;
            case 4:
                return $this->getMname();
                break;
            case 5:
                return $this->getBday();
                break;
            case 6:
                return $this->getAddress();
                break;
            case 7:
                return $this->getGender();
                break;
            case 8:
                return $this->getImgPath();
                break;
            case 9:
                return $this->getDateJoined();
                break;
            case 10:
                return $this->getEmployeeNumber();
                break;
            case 11:
                return $this->getListDeptDeptId();
                break;
            case 12:
                return $this->getListPosPosId();
                break;
            case 13:
                return $this->getStatus();
                break;
            case 14:
                return $this->getProfileStatus();
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
        if (isset($alreadyDumpedObjects['EmpProfile'][$this->getPrimaryKey()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['EmpProfile'][$this->getPrimaryKey()] = true;
        $keys = EmpProfilePeer::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getEmpAccAccId(),
            $keys[2] => $this->getFname(),
            $keys[3] => $this->getLname(),
            $keys[4] => $this->getMname(),
            $keys[5] => $this->getBday(),
            $keys[6] => $this->getAddress(),
            $keys[7] => $this->getGender(),
            $keys[8] => $this->getImgPath(),
            $keys[9] => $this->getDateJoined(),
            $keys[10] => $this->getEmployeeNumber(),
            $keys[11] => $this->getListDeptDeptId(),
            $keys[12] => $this->getListPosPosId(),
            $keys[13] => $this->getStatus(),
            $keys[14] => $this->getProfileStatus(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->aEmpAcc) {
                $result['EmpAcc'] = $this->aEmpAcc->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aListDept) {
                $result['ListDept'] = $this->aListDept->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aListPos) {
                $result['ListPos'] = $this->aListPos->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->collEmpContacts) {
                $result['EmpContacts'] = $this->collEmpContacts->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
        $pos = EmpProfilePeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);

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
                $this->setEmpAccAccId($value);
                break;
            case 2:
                $this->setFname($value);
                break;
            case 3:
                $this->setLname($value);
                break;
            case 4:
                $this->setMname($value);
                break;
            case 5:
                $this->setBday($value);
                break;
            case 6:
                $this->setAddress($value);
                break;
            case 7:
                $this->setGender($value);
                break;
            case 8:
                $this->setImgPath($value);
                break;
            case 9:
                $this->setDateJoined($value);
                break;
            case 10:
                $this->setEmployeeNumber($value);
                break;
            case 11:
                $this->setListDeptDeptId($value);
                break;
            case 12:
                $this->setListPosPosId($value);
                break;
            case 13:
                $this->setStatus($value);
                break;
            case 14:
                $this->setProfileStatus($value);
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
        $keys = EmpProfilePeer::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
        if (array_key_exists($keys[1], $arr)) $this->setEmpAccAccId($arr[$keys[1]]);
        if (array_key_exists($keys[2], $arr)) $this->setFname($arr[$keys[2]]);
        if (array_key_exists($keys[3], $arr)) $this->setLname($arr[$keys[3]]);
        if (array_key_exists($keys[4], $arr)) $this->setMname($arr[$keys[4]]);
        if (array_key_exists($keys[5], $arr)) $this->setBday($arr[$keys[5]]);
        if (array_key_exists($keys[6], $arr)) $this->setAddress($arr[$keys[6]]);
        if (array_key_exists($keys[7], $arr)) $this->setGender($arr[$keys[7]]);
        if (array_key_exists($keys[8], $arr)) $this->setImgPath($arr[$keys[8]]);
        if (array_key_exists($keys[9], $arr)) $this->setDateJoined($arr[$keys[9]]);
        if (array_key_exists($keys[10], $arr)) $this->setEmployeeNumber($arr[$keys[10]]);
        if (array_key_exists($keys[11], $arr)) $this->setListDeptDeptId($arr[$keys[11]]);
        if (array_key_exists($keys[12], $arr)) $this->setListPosPosId($arr[$keys[12]]);
        if (array_key_exists($keys[13], $arr)) $this->setStatus($arr[$keys[13]]);
        if (array_key_exists($keys[14], $arr)) $this->setProfileStatus($arr[$keys[14]]);
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(EmpProfilePeer::DATABASE_NAME);

        if ($this->isColumnModified(EmpProfilePeer::ID)) $criteria->add(EmpProfilePeer::ID, $this->id);
        if ($this->isColumnModified(EmpProfilePeer::EMP_ACC_ACC_ID)) $criteria->add(EmpProfilePeer::EMP_ACC_ACC_ID, $this->emp_acc_acc_id);
        if ($this->isColumnModified(EmpProfilePeer::FNAME)) $criteria->add(EmpProfilePeer::FNAME, $this->fname);
        if ($this->isColumnModified(EmpProfilePeer::LNAME)) $criteria->add(EmpProfilePeer::LNAME, $this->lname);
        if ($this->isColumnModified(EmpProfilePeer::MNAME)) $criteria->add(EmpProfilePeer::MNAME, $this->mname);
        if ($this->isColumnModified(EmpProfilePeer::BDAY)) $criteria->add(EmpProfilePeer::BDAY, $this->bday);
        if ($this->isColumnModified(EmpProfilePeer::ADDRESS)) $criteria->add(EmpProfilePeer::ADDRESS, $this->address);
        if ($this->isColumnModified(EmpProfilePeer::GENDER)) $criteria->add(EmpProfilePeer::GENDER, $this->gender);
        if ($this->isColumnModified(EmpProfilePeer::IMG_PATH)) $criteria->add(EmpProfilePeer::IMG_PATH, $this->img_path);
        if ($this->isColumnModified(EmpProfilePeer::DATE_JOINED)) $criteria->add(EmpProfilePeer::DATE_JOINED, $this->date_joined);
        if ($this->isColumnModified(EmpProfilePeer::EMP_NUM)) $criteria->add(EmpProfilePeer::EMP_NUM, $this->emp_num);
        if ($this->isColumnModified(EmpProfilePeer::LIST_DEPT_ID)) $criteria->add(EmpProfilePeer::LIST_DEPT_ID, $this->list_dept_id);
        if ($this->isColumnModified(EmpProfilePeer::LIST_POS_ID)) $criteria->add(EmpProfilePeer::LIST_POS_ID, $this->list_pos_id);
        if ($this->isColumnModified(EmpProfilePeer::STATUS)) $criteria->add(EmpProfilePeer::STATUS, $this->status);
        if ($this->isColumnModified(EmpProfilePeer::PROFILE_STATUS)) $criteria->add(EmpProfilePeer::PROFILE_STATUS, $this->profile_status);

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
        $criteria = new Criteria(EmpProfilePeer::DATABASE_NAME);
        $criteria->add(EmpProfilePeer::ID, $this->id);

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
     * @param object $copyObj An object of EmpProfile (or compatible) type.
     * @param boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setEmpAccAccId($this->getEmpAccAccId());
        $copyObj->setFname($this->getFname());
        $copyObj->setLname($this->getLname());
        $copyObj->setMname($this->getMname());
        $copyObj->setBday($this->getBday());
        $copyObj->setAddress($this->getAddress());
        $copyObj->setGender($this->getGender());
        $copyObj->setImgPath($this->getImgPath());
        $copyObj->setDateJoined($this->getDateJoined());
        $copyObj->setEmployeeNumber($this->getEmployeeNumber());
        $copyObj->setListDeptDeptId($this->getListDeptDeptId());
        $copyObj->setListPosPosId($this->getListPosPosId());
        $copyObj->setStatus($this->getStatus());
        $copyObj->setProfileStatus($this->getProfileStatus());

        if ($deepCopy && !$this->startCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);
            // store object hash to prevent cycle
            $this->startCopy = true;

            foreach ($this->getEmpContacts() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addEmpContact($relObj->copy($deepCopy));
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
     * @return EmpProfile Clone of current object.
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
     * @return EmpProfilePeer
     */
    public function getPeer()
    {
        if (self::$peer === null) {
            self::$peer = new EmpProfilePeer();
        }

        return self::$peer;
    }

    /**
     * Declares an association between this object and a EmpAcc object.
     *
     * @param                  EmpAcc $v
     * @return EmpProfile The current object (for fluent API support)
     * @throws PropelException
     */
    public function setEmpAcc(EmpAcc $v = null)
    {
        if ($v === null) {
            $this->setEmpAccAccId(NULL);
        } else {
            $this->setEmpAccAccId($v->getId());
        }

        $this->aEmpAcc = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the EmpAcc object, it will not be re-added.
        if ($v !== null) {
            $v->addEmpProfile($this);
        }


        return $this;
    }


    /**
     * Get the associated EmpAcc object
     *
     * @param PropelPDO $con Optional Connection object.
     * @param $doQuery Executes a query to get the object if required
     * @return EmpAcc The associated EmpAcc object.
     * @throws PropelException
     */
    public function getEmpAcc(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aEmpAcc === null && ($this->emp_acc_acc_id !== null) && $doQuery) {
            $this->aEmpAcc = EmpAccQuery::create()->findPk($this->emp_acc_acc_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aEmpAcc->addEmpProfiles($this);
             */
        }

        return $this->aEmpAcc;
    }

    /**
     * Declares an association between this object and a ListDept object.
     *
     * @param                  ListDept $v
     * @return EmpProfile The current object (for fluent API support)
     * @throws PropelException
     */
    public function setListDept(ListDept $v = null)
    {
        if ($v === null) {
            $this->setListDeptDeptId(NULL);
        } else {
            $this->setListDeptDeptId($v->getId());
        }

        $this->aListDept = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ListDept object, it will not be re-added.
        if ($v !== null) {
            $v->addEmpProfile($this);
        }


        return $this;
    }


    /**
     * Get the associated ListDept object
     *
     * @param PropelPDO $con Optional Connection object.
     * @param $doQuery Executes a query to get the object if required
     * @return ListDept The associated ListDept object.
     * @throws PropelException
     */
    public function getListDept(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aListDept === null && ($this->list_dept_id !== null) && $doQuery) {
            $this->aListDept = ListDeptQuery::create()->findPk($this->list_dept_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aListDept->addEmpProfiles($this);
             */
        }

        return $this->aListDept;
    }

    /**
     * Declares an association between this object and a ListPos object.
     *
     * @param                  ListPos $v
     * @return EmpProfile The current object (for fluent API support)
     * @throws PropelException
     */
    public function setListPos(ListPos $v = null)
    {
        if ($v === null) {
            $this->setListPosPosId(NULL);
        } else {
            $this->setListPosPosId($v->getId());
        }

        $this->aListPos = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ListPos object, it will not be re-added.
        if ($v !== null) {
            $v->addEmpProfile($this);
        }


        return $this;
    }


    /**
     * Get the associated ListPos object
     *
     * @param PropelPDO $con Optional Connection object.
     * @param $doQuery Executes a query to get the object if required
     * @return ListPos The associated ListPos object.
     * @throws PropelException
     */
    public function getListPos(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aListPos === null && ($this->list_pos_id !== null) && $doQuery) {
            $this->aListPos = ListPosQuery::create()->findPk($this->list_pos_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aListPos->addEmpProfiles($this);
             */
        }

        return $this->aListPos;
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
        if ('EmpContact' == $relationName) {
            $this->initEmpContacts();
        }
    }

    /**
     * Clears out the collEmpContacts collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return EmpProfile The current object (for fluent API support)
     * @see        addEmpContacts()
     */
    public function clearEmpContacts()
    {
        $this->collEmpContacts = null; // important to set this to null since that means it is uninitialized
        $this->collEmpContactsPartial = null;

        return $this;
    }

    /**
     * reset is the collEmpContacts collection loaded partially
     *
     * @return void
     */
    public function resetPartialEmpContacts($v = true)
    {
        $this->collEmpContactsPartial = $v;
    }

    /**
     * Initializes the collEmpContacts collection.
     *
     * By default this just sets the collEmpContacts collection to an empty array (like clearcollEmpContacts());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initEmpContacts($overrideExisting = true)
    {
        if (null !== $this->collEmpContacts && !$overrideExisting) {
            return;
        }
        $this->collEmpContacts = new PropelObjectCollection();
        $this->collEmpContacts->setModel('EmpContact');
    }

    /**
     * Gets an array of EmpContact objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this EmpProfile is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|EmpContact[] List of EmpContact objects
     * @throws PropelException
     */
    public function getEmpContacts($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collEmpContactsPartial && !$this->isNew();
        if (null === $this->collEmpContacts || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collEmpContacts) {
                // return empty collection
                $this->initEmpContacts();
            } else {
                $collEmpContacts = EmpContactQuery::create(null, $criteria)
                    ->filterByEmpProfile($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collEmpContactsPartial && count($collEmpContacts)) {
                      $this->initEmpContacts(false);

                      foreach ($collEmpContacts as $obj) {
                        if (false == $this->collEmpContacts->contains($obj)) {
                          $this->collEmpContacts->append($obj);
                        }
                      }

                      $this->collEmpContactsPartial = true;
                    }

                    $collEmpContacts->getInternalIterator()->rewind();

                    return $collEmpContacts;
                }

                if ($partial && $this->collEmpContacts) {
                    foreach ($this->collEmpContacts as $obj) {
                        if ($obj->isNew()) {
                            $collEmpContacts[] = $obj;
                        }
                    }
                }

                $this->collEmpContacts = $collEmpContacts;
                $this->collEmpContactsPartial = false;
            }
        }

        return $this->collEmpContacts;
    }

    /**
     * Sets a collection of EmpContact objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $empContacts A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return EmpProfile The current object (for fluent API support)
     */
    public function setEmpContacts(PropelCollection $empContacts, PropelPDO $con = null)
    {
        $empContactsToDelete = $this->getEmpContacts(new Criteria(), $con)->diff($empContacts);


        $this->empContactsScheduledForDeletion = $empContactsToDelete;

        foreach ($empContactsToDelete as $empContactRemoved) {
            $empContactRemoved->setEmpProfile(null);
        }

        $this->collEmpContacts = null;
        foreach ($empContacts as $empContact) {
            $this->addEmpContact($empContact);
        }

        $this->collEmpContacts = $empContacts;
        $this->collEmpContactsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related EmpContact objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related EmpContact objects.
     * @throws PropelException
     */
    public function countEmpContacts(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collEmpContactsPartial && !$this->isNew();
        if (null === $this->collEmpContacts || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collEmpContacts) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getEmpContacts());
            }
            $query = EmpContactQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByEmpProfile($this)
                ->count($con);
        }

        return count($this->collEmpContacts);
    }

    /**
     * Method called to associate a EmpContact object to this object
     * through the EmpContact foreign key attribute.
     *
     * @param    EmpContact $l EmpContact
     * @return EmpProfile The current object (for fluent API support)
     */
    public function addEmpContact(EmpContact $l)
    {
        if ($this->collEmpContacts === null) {
            $this->initEmpContacts();
            $this->collEmpContactsPartial = true;
        }

        if (!in_array($l, $this->collEmpContacts->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddEmpContact($l);

            if ($this->empContactsScheduledForDeletion and $this->empContactsScheduledForDeletion->contains($l)) {
                $this->empContactsScheduledForDeletion->remove($this->empContactsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	EmpContact $empContact The empContact object to add.
     */
    protected function doAddEmpContact($empContact)
    {
        $this->collEmpContacts[]= $empContact;
        $empContact->setEmpProfile($this);
    }

    /**
     * @param	EmpContact $empContact The empContact object to remove.
     * @return EmpProfile The current object (for fluent API support)
     */
    public function removeEmpContact($empContact)
    {
        if ($this->getEmpContacts()->contains($empContact)) {
            $this->collEmpContacts->remove($this->collEmpContacts->search($empContact));
            if (null === $this->empContactsScheduledForDeletion) {
                $this->empContactsScheduledForDeletion = clone $this->collEmpContacts;
                $this->empContactsScheduledForDeletion->clear();
            }
            $this->empContactsScheduledForDeletion[]= clone $empContact;
            $empContact->setEmpProfile(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this EmpProfile is new, it will return
     * an empty collection; or if this EmpProfile has previously
     * been saved, it will retrieve related EmpContacts from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in EmpProfile.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|EmpContact[] List of EmpContact objects
     */
    public function getEmpContactsJoinListContTypes($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = EmpContactQuery::create(null, $criteria);
        $query->joinWith('ListContTypes', $join_behavior);

        return $this->getEmpContacts($query, $con);
    }

    /**
     * Clears the current object and sets all attributes to their default values
     */
    public function clear()
    {
        $this->id = null;
        $this->emp_acc_acc_id = null;
        $this->fname = null;
        $this->lname = null;
        $this->mname = null;
        $this->bday = null;
        $this->address = null;
        $this->gender = null;
        $this->img_path = null;
        $this->date_joined = null;
        $this->emp_num = null;
        $this->list_dept_id = null;
        $this->list_pos_id = null;
        $this->status = null;
        $this->profile_status = null;
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
            if ($this->collEmpContacts) {
                foreach ($this->collEmpContacts as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->aEmpAcc instanceof Persistent) {
              $this->aEmpAcc->clearAllReferences($deep);
            }
            if ($this->aListDept instanceof Persistent) {
              $this->aListDept->clearAllReferences($deep);
            }
            if ($this->aListPos instanceof Persistent) {
              $this->aListPos->clearAllReferences($deep);
            }

            $this->alreadyInClearAllReferencesDeep = false;
        } // if ($deep)

        if ($this->collEmpContacts instanceof PropelCollection) {
            $this->collEmpContacts->clearIterator();
        }
        $this->collEmpContacts = null;
        $this->aEmpAcc = null;
        $this->aListDept = null;
        $this->aListPos = null;
    }

    /**
     * return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(EmpProfilePeer::DEFAULT_STRING_FORMAT);
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
