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
use CoreBundle\Model\EventAttachment;
use CoreBundle\Model\EventAttachmentQuery;
use CoreBundle\Model\EventNotes;
use CoreBundle\Model\EventNotesQuery;
use CoreBundle\Model\EventTaggedPersons;
use CoreBundle\Model\EventTaggedPersonsQuery;
use CoreBundle\Model\ListEvents;
use CoreBundle\Model\ListEventsPeer;
use CoreBundle\Model\ListEventsQuery;
use CoreBundle\Model\ListEventsType;
use CoreBundle\Model\ListEventsTypeQuery;

abstract class BaseListEvents extends BaseObject implements Persistent
{
    /**
     * Peer class name
     */
    const PEER = 'CoreBundle\\Model\\ListEventsPeer';

    /**
     * The Peer class.
     * Instance provides a convenient way of calling static methods on a class
     * that calling code may not be able to identify.
     * @var        ListEventsPeer
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
     * The value for the created_by field.
     * @var        int
     */
    protected $created_by;

    /**
     * The value for the date_created field.
     * @var        string
     */
    protected $date_created;

    /**
     * The value for the from_date field.
     * @var        string
     */
    protected $from_date;

    /**
     * The value for the to_date field.
     * @var        string
     */
    protected $to_date;

    /**
     * The value for the event_name field.
     * @var        string
     */
    protected $event_name;

    /**
     * The value for the event_venue field.
     * @var        string
     */
    protected $event_venue;

    /**
     * The value for the event_desc field.
     * @var        string
     */
    protected $event_desc;

    /**
     * The value for the event_type field.
     * @var        int
     */
    protected $event_type;

    /**
     * The value for the status field.
     * @var        int
     */
    protected $status;

    /**
     * The value for the is_going field.
     * @var        int
     */
    protected $is_going;

    /**
     * The value for the is_going_note field.
     * @var        string
     */
    protected $is_going_note;

    /**
     * The value for the sms_response field.
     * @var        string
     */
    protected $sms_response;

    /**
     * @var        ListEventsType
     */
    protected $aListEventsType;

    /**
     * @var        EmpAcc
     */
    protected $aEmpAcc;

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
     * @var        PropelObjectCollection|EventAttachment[] Collection to store aggregation of EventAttachment objects.
     */
    protected $collEventAttachments;
    protected $collEventAttachmentsPartial;

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
    protected $eventNotessScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $eventTaggedPersonssScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $eventAttachmentsScheduledForDeletion = null;

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
     * Get the [created_by] column value.
     * 
     * @return int
     */
    public function getCreatedBy()
    {

        return $this->created_by;
    }

    /**
     * Get the [optionally formatted] temporal [date_created] column value.
     * 
     *
     * @param string $format The date/time format string (either date()-style or strftime()-style).
     *				 If format is null, then the raw DateTime object will be returned.
     * @return mixed Formatted date/time value as string or DateTime object (if format is null), null if column is null, and 0 if column value is 0000-00-00 00:00:00
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getDateCreated($format = null)
    {
        if ($this->date_created === null) {
            return null;
        }

        if ($this->date_created === '0000-00-00 00:00:00') {
            // while technically this is not a default value of null,
            // this seems to be closest in meaning.
            return null;
        }

        try {
            $dt = new DateTime($this->date_created);
        } catch (Exception $x) {
            throw new PropelException("Internally stored date/time/timestamp value could not be converted to DateTime: " . var_export($this->date_created, true), $x);
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
     * Get the [optionally formatted] temporal [from_date] column value.
     * 
     *
     * @param string $format The date/time format string (either date()-style or strftime()-style).
     *				 If format is null, then the raw DateTime object will be returned.
     * @return mixed Formatted date/time value as string or DateTime object (if format is null), null if column is null, and 0 if column value is 0000-00-00 00:00:00
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getFromDate($format = null)
    {
        if ($this->from_date === null) {
            return null;
        }

        if ($this->from_date === '0000-00-00 00:00:00') {
            // while technically this is not a default value of null,
            // this seems to be closest in meaning.
            return null;
        }

        try {
            $dt = new DateTime($this->from_date);
        } catch (Exception $x) {
            throw new PropelException("Internally stored date/time/timestamp value could not be converted to DateTime: " . var_export($this->from_date, true), $x);
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
     * Get the [optionally formatted] temporal [to_date] column value.
     * 
     *
     * @param string $format The date/time format string (either date()-style or strftime()-style).
     *				 If format is null, then the raw DateTime object will be returned.
     * @return mixed Formatted date/time value as string or DateTime object (if format is null), null if column is null, and 0 if column value is 0000-00-00 00:00:00
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getToDate($format = null)
    {
        if ($this->to_date === null) {
            return null;
        }

        if ($this->to_date === '0000-00-00 00:00:00') {
            // while technically this is not a default value of null,
            // this seems to be closest in meaning.
            return null;
        }

        try {
            $dt = new DateTime($this->to_date);
        } catch (Exception $x) {
            throw new PropelException("Internally stored date/time/timestamp value could not be converted to DateTime: " . var_export($this->to_date, true), $x);
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
     * Get the [event_name] column value.
     * 
     * @return string
     */
    public function getEventName()
    {

        return $this->event_name;
    }

    /**
     * Get the [event_venue] column value.
     * 
     * @return string
     */
    public function getEventVenue()
    {

        return $this->event_venue;
    }

    /**
     * Get the [event_desc] column value.
     * 
     * @return string
     */
    public function getEventDescription()
    {

        return $this->event_desc;
    }

    /**
     * Get the [event_type] column value.
     * 
     * @return int
     */
    public function getEventType()
    {

        return $this->event_type;
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
     * Get the [is_going] column value.
     * 
     * @return int
     */
    public function getIsGoing()
    {

        return $this->is_going;
    }

    /**
     * Get the [is_going_note] column value.
     * 
     * @return string
     */
    public function getIsGoingNote()
    {

        return $this->is_going_note;
    }

    /**
     * Get the [sms_response] column value.
     * 
     * @return string
     */
    public function getSmsResponse()
    {

        return $this->sms_response;
    }

    /**
     * Set the value of [id] column.
     * 
     * @param  int $v new value
     * @return ListEvents The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[] = ListEventsPeer::ID;
        }


        return $this;
    } // setId()

    /**
     * Set the value of [created_by] column.
     * 
     * @param  int $v new value
     * @return ListEvents The current object (for fluent API support)
     */
    public function setCreatedBy($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->created_by !== $v) {
            $this->created_by = $v;
            $this->modifiedColumns[] = ListEventsPeer::CREATED_BY;
        }

        if ($this->aEmpAcc !== null && $this->aEmpAcc->getId() !== $v) {
            $this->aEmpAcc = null;
        }


        return $this;
    } // setCreatedBy()

    /**
     * Sets the value of [date_created] column to a normalized version of the date/time value specified.
     * 
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return ListEvents The current object (for fluent API support)
     */
    public function setDateCreated($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->date_created !== null || $dt !== null) {
            $currentDateAsString = ($this->date_created !== null && $tmpDt = new DateTime($this->date_created)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->date_created = $newDateAsString;
                $this->modifiedColumns[] = ListEventsPeer::DATE_CREATED;
            }
        } // if either are not null


        return $this;
    } // setDateCreated()

    /**
     * Sets the value of [from_date] column to a normalized version of the date/time value specified.
     * 
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return ListEvents The current object (for fluent API support)
     */
    public function setFromDate($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->from_date !== null || $dt !== null) {
            $currentDateAsString = ($this->from_date !== null && $tmpDt = new DateTime($this->from_date)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->from_date = $newDateAsString;
                $this->modifiedColumns[] = ListEventsPeer::FROM_DATE;
            }
        } // if either are not null


        return $this;
    } // setFromDate()

    /**
     * Sets the value of [to_date] column to a normalized version of the date/time value specified.
     * 
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return ListEvents The current object (for fluent API support)
     */
    public function setToDate($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->to_date !== null || $dt !== null) {
            $currentDateAsString = ($this->to_date !== null && $tmpDt = new DateTime($this->to_date)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->to_date = $newDateAsString;
                $this->modifiedColumns[] = ListEventsPeer::TO_DATE;
            }
        } // if either are not null


        return $this;
    } // setToDate()

    /**
     * Set the value of [event_name] column.
     * 
     * @param  string $v new value
     * @return ListEvents The current object (for fluent API support)
     */
    public function setEventName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->event_name !== $v) {
            $this->event_name = $v;
            $this->modifiedColumns[] = ListEventsPeer::EVENT_NAME;
        }


        return $this;
    } // setEventName()

    /**
     * Set the value of [event_venue] column.
     * 
     * @param  string $v new value
     * @return ListEvents The current object (for fluent API support)
     */
    public function setEventVenue($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->event_venue !== $v) {
            $this->event_venue = $v;
            $this->modifiedColumns[] = ListEventsPeer::EVENT_VENUE;
        }


        return $this;
    } // setEventVenue()

    /**
     * Set the value of [event_desc] column.
     * 
     * @param  string $v new value
     * @return ListEvents The current object (for fluent API support)
     */
    public function setEventDescription($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->event_desc !== $v) {
            $this->event_desc = $v;
            $this->modifiedColumns[] = ListEventsPeer::EVENT_DESC;
        }


        return $this;
    } // setEventDescription()

    /**
     * Set the value of [event_type] column.
     * 
     * @param  int $v new value
     * @return ListEvents The current object (for fluent API support)
     */
    public function setEventType($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->event_type !== $v) {
            $this->event_type = $v;
            $this->modifiedColumns[] = ListEventsPeer::EVENT_TYPE;
        }

        if ($this->aListEventsType !== null && $this->aListEventsType->getId() !== $v) {
            $this->aListEventsType = null;
        }


        return $this;
    } // setEventType()

    /**
     * Set the value of [status] column.
     * 
     * @param  int $v new value
     * @return ListEvents The current object (for fluent API support)
     */
    public function setStatus($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->status !== $v) {
            $this->status = $v;
            $this->modifiedColumns[] = ListEventsPeer::STATUS;
        }


        return $this;
    } // setStatus()

    /**
     * Set the value of [is_going] column.
     * 
     * @param  int $v new value
     * @return ListEvents The current object (for fluent API support)
     */
    public function setIsGoing($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->is_going !== $v) {
            $this->is_going = $v;
            $this->modifiedColumns[] = ListEventsPeer::IS_GOING;
        }


        return $this;
    } // setIsGoing()

    /**
     * Set the value of [is_going_note] column.
     * 
     * @param  string $v new value
     * @return ListEvents The current object (for fluent API support)
     */
    public function setIsGoingNote($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->is_going_note !== $v) {
            $this->is_going_note = $v;
            $this->modifiedColumns[] = ListEventsPeer::IS_GOING_NOTE;
        }


        return $this;
    } // setIsGoingNote()

    /**
     * Set the value of [sms_response] column.
     * 
     * @param  string $v new value
     * @return ListEvents The current object (for fluent API support)
     */
    public function setSmsResponse($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->sms_response !== $v) {
            $this->sms_response = $v;
            $this->modifiedColumns[] = ListEventsPeer::SMS_RESPONSE;
        }


        return $this;
    } // setSmsResponse()

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
            $this->created_by = ($row[$startcol + 1] !== null) ? (int) $row[$startcol + 1] : null;
            $this->date_created = ($row[$startcol + 2] !== null) ? (string) $row[$startcol + 2] : null;
            $this->from_date = ($row[$startcol + 3] !== null) ? (string) $row[$startcol + 3] : null;
            $this->to_date = ($row[$startcol + 4] !== null) ? (string) $row[$startcol + 4] : null;
            $this->event_name = ($row[$startcol + 5] !== null) ? (string) $row[$startcol + 5] : null;
            $this->event_venue = ($row[$startcol + 6] !== null) ? (string) $row[$startcol + 6] : null;
            $this->event_desc = ($row[$startcol + 7] !== null) ? (string) $row[$startcol + 7] : null;
            $this->event_type = ($row[$startcol + 8] !== null) ? (int) $row[$startcol + 8] : null;
            $this->status = ($row[$startcol + 9] !== null) ? (int) $row[$startcol + 9] : null;
            $this->is_going = ($row[$startcol + 10] !== null) ? (int) $row[$startcol + 10] : null;
            $this->is_going_note = ($row[$startcol + 11] !== null) ? (string) $row[$startcol + 11] : null;
            $this->sms_response = ($row[$startcol + 12] !== null) ? (string) $row[$startcol + 12] : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }
            $this->postHydrate($row, $startcol, $rehydrate);

            return $startcol + 13; // 13 = ListEventsPeer::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException("Error populating ListEvents object", $e);
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

        if ($this->aEmpAcc !== null && $this->created_by !== $this->aEmpAcc->getId()) {
            $this->aEmpAcc = null;
        }
        if ($this->aListEventsType !== null && $this->event_type !== $this->aListEventsType->getId()) {
            $this->aListEventsType = null;
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
            $con = Propel::getConnection(ListEventsPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $stmt = ListEventsPeer::doSelectStmt($this->buildPkeyCriteria(), $con);
        $row = $stmt->fetch(PDO::FETCH_NUM);
        $stmt->closeCursor();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aListEventsType = null;
            $this->aEmpAcc = null;
            $this->collEventNotess = null;

            $this->collEventTaggedPersonss = null;

            $this->collEventAttachments = null;

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
            $con = Propel::getConnection(ListEventsPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        try {
            $deleteQuery = ListEventsQuery::create()
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
            $con = Propel::getConnection(ListEventsPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
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
                ListEventsPeer::addInstanceToPool($this);
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

            if ($this->aListEventsType !== null) {
                if ($this->aListEventsType->isModified() || $this->aListEventsType->isNew()) {
                    $affectedRows += $this->aListEventsType->save($con);
                }
                $this->setListEventsType($this->aListEventsType);
            }

            if ($this->aEmpAcc !== null) {
                if ($this->aEmpAcc->isModified() || $this->aEmpAcc->isNew()) {
                    $affectedRows += $this->aEmpAcc->save($con);
                }
                $this->setEmpAcc($this->aEmpAcc);
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

            if ($this->eventAttachmentsScheduledForDeletion !== null) {
                if (!$this->eventAttachmentsScheduledForDeletion->isEmpty()) {
                    EventAttachmentQuery::create()
                        ->filterByPrimaryKeys($this->eventAttachmentsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->eventAttachmentsScheduledForDeletion = null;
                }
            }

            if ($this->collEventAttachments !== null) {
                foreach ($this->collEventAttachments as $referrerFK) {
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

        $this->modifiedColumns[] = ListEventsPeer::ID;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . ListEventsPeer::ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(ListEventsPeer::ID)) {
            $modifiedColumns[':p' . $index++]  = '`id`';
        }
        if ($this->isColumnModified(ListEventsPeer::CREATED_BY)) {
            $modifiedColumns[':p' . $index++]  = '`created_by`';
        }
        if ($this->isColumnModified(ListEventsPeer::DATE_CREATED)) {
            $modifiedColumns[':p' . $index++]  = '`date_created`';
        }
        if ($this->isColumnModified(ListEventsPeer::FROM_DATE)) {
            $modifiedColumns[':p' . $index++]  = '`from_date`';
        }
        if ($this->isColumnModified(ListEventsPeer::TO_DATE)) {
            $modifiedColumns[':p' . $index++]  = '`to_date`';
        }
        if ($this->isColumnModified(ListEventsPeer::EVENT_NAME)) {
            $modifiedColumns[':p' . $index++]  = '`event_name`';
        }
        if ($this->isColumnModified(ListEventsPeer::EVENT_VENUE)) {
            $modifiedColumns[':p' . $index++]  = '`event_venue`';
        }
        if ($this->isColumnModified(ListEventsPeer::EVENT_DESC)) {
            $modifiedColumns[':p' . $index++]  = '`event_desc`';
        }
        if ($this->isColumnModified(ListEventsPeer::EVENT_TYPE)) {
            $modifiedColumns[':p' . $index++]  = '`event_type`';
        }
        if ($this->isColumnModified(ListEventsPeer::STATUS)) {
            $modifiedColumns[':p' . $index++]  = '`status`';
        }
        if ($this->isColumnModified(ListEventsPeer::IS_GOING)) {
            $modifiedColumns[':p' . $index++]  = '`is_going`';
        }
        if ($this->isColumnModified(ListEventsPeer::IS_GOING_NOTE)) {
            $modifiedColumns[':p' . $index++]  = '`is_going_note`';
        }
        if ($this->isColumnModified(ListEventsPeer::SMS_RESPONSE)) {
            $modifiedColumns[':p' . $index++]  = '`sms_response`';
        }

        $sql = sprintf(
            'INSERT INTO `list_events` (%s) VALUES (%s)',
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
                    case '`created_by`':						
                        $stmt->bindValue($identifier, $this->created_by, PDO::PARAM_INT);
                        break;
                    case '`date_created`':						
                        $stmt->bindValue($identifier, $this->date_created, PDO::PARAM_STR);
                        break;
                    case '`from_date`':						
                        $stmt->bindValue($identifier, $this->from_date, PDO::PARAM_STR);
                        break;
                    case '`to_date`':						
                        $stmt->bindValue($identifier, $this->to_date, PDO::PARAM_STR);
                        break;
                    case '`event_name`':						
                        $stmt->bindValue($identifier, $this->event_name, PDO::PARAM_STR);
                        break;
                    case '`event_venue`':						
                        $stmt->bindValue($identifier, $this->event_venue, PDO::PARAM_STR);
                        break;
                    case '`event_desc`':						
                        $stmt->bindValue($identifier, $this->event_desc, PDO::PARAM_STR);
                        break;
                    case '`event_type`':						
                        $stmt->bindValue($identifier, $this->event_type, PDO::PARAM_INT);
                        break;
                    case '`status`':						
                        $stmt->bindValue($identifier, $this->status, PDO::PARAM_INT);
                        break;
                    case '`is_going`':						
                        $stmt->bindValue($identifier, $this->is_going, PDO::PARAM_INT);
                        break;
                    case '`is_going_note`':						
                        $stmt->bindValue($identifier, $this->is_going_note, PDO::PARAM_STR);
                        break;
                    case '`sms_response`':						
                        $stmt->bindValue($identifier, $this->sms_response, PDO::PARAM_STR);
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

            if ($this->aListEventsType !== null) {
                if (!$this->aListEventsType->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aListEventsType->getValidationFailures());
                }
            }

            if ($this->aEmpAcc !== null) {
                if (!$this->aEmpAcc->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aEmpAcc->getValidationFailures());
                }
            }


            if (($retval = ListEventsPeer::doValidate($this, $columns)) !== true) {
                $failureMap = array_merge($failureMap, $retval);
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

                if ($this->collEventAttachments !== null) {
                    foreach ($this->collEventAttachments as $referrerFK) {
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
        $pos = ListEventsPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
                return $this->getCreatedBy();
                break;
            case 2:
                return $this->getDateCreated();
                break;
            case 3:
                return $this->getFromDate();
                break;
            case 4:
                return $this->getToDate();
                break;
            case 5:
                return $this->getEventName();
                break;
            case 6:
                return $this->getEventVenue();
                break;
            case 7:
                return $this->getEventDescription();
                break;
            case 8:
                return $this->getEventType();
                break;
            case 9:
                return $this->getStatus();
                break;
            case 10:
                return $this->getIsGoing();
                break;
            case 11:
                return $this->getIsGoingNote();
                break;
            case 12:
                return $this->getSmsResponse();
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
        if (isset($alreadyDumpedObjects['ListEvents'][$this->getPrimaryKey()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['ListEvents'][$this->getPrimaryKey()] = true;
        $keys = ListEventsPeer::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getCreatedBy(),
            $keys[2] => $this->getDateCreated(),
            $keys[3] => $this->getFromDate(),
            $keys[4] => $this->getToDate(),
            $keys[5] => $this->getEventName(),
            $keys[6] => $this->getEventVenue(),
            $keys[7] => $this->getEventDescription(),
            $keys[8] => $this->getEventType(),
            $keys[9] => $this->getStatus(),
            $keys[10] => $this->getIsGoing(),
            $keys[11] => $this->getIsGoingNote(),
            $keys[12] => $this->getSmsResponse(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }
        
        if ($includeForeignObjects) {
            if (null !== $this->aListEventsType) {
                $result['ListEventsType'] = $this->aListEventsType->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aEmpAcc) {
                $result['EmpAcc'] = $this->aEmpAcc->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->collEventNotess) {
                $result['EventNotess'] = $this->collEventNotess->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collEventTaggedPersonss) {
                $result['EventTaggedPersonss'] = $this->collEventTaggedPersonss->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collEventAttachments) {
                $result['EventAttachments'] = $this->collEventAttachments->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
        $pos = ListEventsPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);

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
                $this->setCreatedBy($value);
                break;
            case 2:
                $this->setDateCreated($value);
                break;
            case 3:
                $this->setFromDate($value);
                break;
            case 4:
                $this->setToDate($value);
                break;
            case 5:
                $this->setEventName($value);
                break;
            case 6:
                $this->setEventVenue($value);
                break;
            case 7:
                $this->setEventDescription($value);
                break;
            case 8:
                $this->setEventType($value);
                break;
            case 9:
                $this->setStatus($value);
                break;
            case 10:
                $this->setIsGoing($value);
                break;
            case 11:
                $this->setIsGoingNote($value);
                break;
            case 12:
                $this->setSmsResponse($value);
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
        $keys = ListEventsPeer::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
        if (array_key_exists($keys[1], $arr)) $this->setCreatedBy($arr[$keys[1]]);
        if (array_key_exists($keys[2], $arr)) $this->setDateCreated($arr[$keys[2]]);
        if (array_key_exists($keys[3], $arr)) $this->setFromDate($arr[$keys[3]]);
        if (array_key_exists($keys[4], $arr)) $this->setToDate($arr[$keys[4]]);
        if (array_key_exists($keys[5], $arr)) $this->setEventName($arr[$keys[5]]);
        if (array_key_exists($keys[6], $arr)) $this->setEventVenue($arr[$keys[6]]);
        if (array_key_exists($keys[7], $arr)) $this->setEventDescription($arr[$keys[7]]);
        if (array_key_exists($keys[8], $arr)) $this->setEventType($arr[$keys[8]]);
        if (array_key_exists($keys[9], $arr)) $this->setStatus($arr[$keys[9]]);
        if (array_key_exists($keys[10], $arr)) $this->setIsGoing($arr[$keys[10]]);
        if (array_key_exists($keys[11], $arr)) $this->setIsGoingNote($arr[$keys[11]]);
        if (array_key_exists($keys[12], $arr)) $this->setSmsResponse($arr[$keys[12]]);
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(ListEventsPeer::DATABASE_NAME);

        if ($this->isColumnModified(ListEventsPeer::ID)) $criteria->add(ListEventsPeer::ID, $this->id);
        if ($this->isColumnModified(ListEventsPeer::CREATED_BY)) $criteria->add(ListEventsPeer::CREATED_BY, $this->created_by);
        if ($this->isColumnModified(ListEventsPeer::DATE_CREATED)) $criteria->add(ListEventsPeer::DATE_CREATED, $this->date_created);
        if ($this->isColumnModified(ListEventsPeer::FROM_DATE)) $criteria->add(ListEventsPeer::FROM_DATE, $this->from_date);
        if ($this->isColumnModified(ListEventsPeer::TO_DATE)) $criteria->add(ListEventsPeer::TO_DATE, $this->to_date);
        if ($this->isColumnModified(ListEventsPeer::EVENT_NAME)) $criteria->add(ListEventsPeer::EVENT_NAME, $this->event_name);
        if ($this->isColumnModified(ListEventsPeer::EVENT_VENUE)) $criteria->add(ListEventsPeer::EVENT_VENUE, $this->event_venue);
        if ($this->isColumnModified(ListEventsPeer::EVENT_DESC)) $criteria->add(ListEventsPeer::EVENT_DESC, $this->event_desc);
        if ($this->isColumnModified(ListEventsPeer::EVENT_TYPE)) $criteria->add(ListEventsPeer::EVENT_TYPE, $this->event_type);
        if ($this->isColumnModified(ListEventsPeer::STATUS)) $criteria->add(ListEventsPeer::STATUS, $this->status);
        if ($this->isColumnModified(ListEventsPeer::IS_GOING)) $criteria->add(ListEventsPeer::IS_GOING, $this->is_going);
        if ($this->isColumnModified(ListEventsPeer::IS_GOING_NOTE)) $criteria->add(ListEventsPeer::IS_GOING_NOTE, $this->is_going_note);
        if ($this->isColumnModified(ListEventsPeer::SMS_RESPONSE)) $criteria->add(ListEventsPeer::SMS_RESPONSE, $this->sms_response);

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
        $criteria = new Criteria(ListEventsPeer::DATABASE_NAME);
        $criteria->add(ListEventsPeer::ID, $this->id);

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
     * @param object $copyObj An object of ListEvents (or compatible) type.
     * @param boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setCreatedBy($this->getCreatedBy());
        $copyObj->setDateCreated($this->getDateCreated());
        $copyObj->setFromDate($this->getFromDate());
        $copyObj->setToDate($this->getToDate());
        $copyObj->setEventName($this->getEventName());
        $copyObj->setEventVenue($this->getEventVenue());
        $copyObj->setEventDescription($this->getEventDescription());
        $copyObj->setEventType($this->getEventType());
        $copyObj->setStatus($this->getStatus());
        $copyObj->setIsGoing($this->getIsGoing());
        $copyObj->setIsGoingNote($this->getIsGoingNote());
        $copyObj->setSmsResponse($this->getSmsResponse());

        if ($deepCopy && !$this->startCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);
            // store object hash to prevent cycle
            $this->startCopy = true;

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

            foreach ($this->getEventAttachments() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addEventAttachment($relObj->copy($deepCopy));
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
     * @return ListEvents Clone of current object.
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
     * @return ListEventsPeer
     */
    public function getPeer()
    {
        if (self::$peer === null) {
            self::$peer = new ListEventsPeer();
        }

        return self::$peer;
    }

    /**
     * Declares an association between this object and a ListEventsType object.
     *
     * @param                  ListEventsType $v
     * @return ListEvents The current object (for fluent API support)
     * @throws PropelException
     */
    public function setListEventsType(ListEventsType $v = null)
    {
        if ($v === null) {
            $this->setEventType(NULL);
        } else {
            $this->setEventType($v->getId());
        }

        $this->aListEventsType = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ListEventsType object, it will not be re-added.
        if ($v !== null) {
            $v->addListEvents($this);
        }


        return $this;
    }


    /**
     * Get the associated ListEventsType object
     *
     * @param PropelPDO $con Optional Connection object.
     * @param $doQuery Executes a query to get the object if required
     * @return ListEventsType The associated ListEventsType object.
     * @throws PropelException
     */
    public function getListEventsType(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aListEventsType === null && ($this->event_type !== null) && $doQuery) {
            $this->aListEventsType = ListEventsTypeQuery::create()->findPk($this->event_type, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aListEventsType->addListEventss($this);
             */
        }

        return $this->aListEventsType;
    }

    /**
     * Declares an association between this object and a EmpAcc object.
     *
     * @param                  EmpAcc $v
     * @return ListEvents The current object (for fluent API support)
     * @throws PropelException
     */
    public function setEmpAcc(EmpAcc $v = null)
    {
        if ($v === null) {
            $this->setCreatedBy(NULL);
        } else {
            $this->setCreatedBy($v->getId());
        }

        $this->aEmpAcc = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the EmpAcc object, it will not be re-added.
        if ($v !== null) {
            $v->addListEvents($this);
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
        if ($this->aEmpAcc === null && ($this->created_by !== null) && $doQuery) {
            $this->aEmpAcc = EmpAccQuery::create()->findPk($this->created_by, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aEmpAcc->addListEventss($this);
             */
        }

        return $this->aEmpAcc;
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
        if ('EventNotes' == $relationName) {
            $this->initEventNotess();
        }
        if ('EventTaggedPersons' == $relationName) {
            $this->initEventTaggedPersonss();
        }
        if ('EventAttachment' == $relationName) {
            $this->initEventAttachments();
        }
    }

    /**
     * Clears out the collEventNotess collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return ListEvents The current object (for fluent API support)
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
     * If this ListEvents is new, it will return
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
                    ->filterByListEvents($this)
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
     * @return ListEvents The current object (for fluent API support)
     */
    public function setEventNotess(PropelCollection $eventNotess, PropelPDO $con = null)
    {
        $eventNotessToDelete = $this->getEventNotess(new Criteria(), $con)->diff($eventNotess);


        $this->eventNotessScheduledForDeletion = $eventNotessToDelete;

        foreach ($eventNotessToDelete as $eventNotesRemoved) {
            $eventNotesRemoved->setListEvents(null);
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
                ->filterByListEvents($this)
                ->count($con);
        }

        return count($this->collEventNotess);
    }

    /**
     * Method called to associate a EventNotes object to this object
     * through the EventNotes foreign key attribute.
     *
     * @param    EventNotes $l EventNotes
     * @return ListEvents The current object (for fluent API support)
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
        $eventNotes->setListEvents($this);
    }

    /**
     * @param	EventNotes $eventNotes The eventNotes object to remove.
     * @return ListEvents The current object (for fluent API support)
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
            $eventNotes->setListEvents(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this ListEvents is new, it will return
     * an empty collection; or if this ListEvents has previously
     * been saved, it will retrieve related EventNotess from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in ListEvents.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|EventNotes[] List of EventNotes objects
     */
    public function getEventNotessJoinEmpAcc($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = EventNotesQuery::create(null, $criteria);
        $query->joinWith('EmpAcc', $join_behavior);

        return $this->getEventNotess($query, $con);
    }

    /**
     * Clears out the collEventTaggedPersonss collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return ListEvents The current object (for fluent API support)
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
     * If this ListEvents is new, it will return
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
                    ->filterByListEvents($this)
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
     * @return ListEvents The current object (for fluent API support)
     */
    public function setEventTaggedPersonss(PropelCollection $eventTaggedPersonss, PropelPDO $con = null)
    {
        $eventTaggedPersonssToDelete = $this->getEventTaggedPersonss(new Criteria(), $con)->diff($eventTaggedPersonss);


        $this->eventTaggedPersonssScheduledForDeletion = $eventTaggedPersonssToDelete;

        foreach ($eventTaggedPersonssToDelete as $eventTaggedPersonsRemoved) {
            $eventTaggedPersonsRemoved->setListEvents(null);
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
                ->filterByListEvents($this)
                ->count($con);
        }

        return count($this->collEventTaggedPersonss);
    }

    /**
     * Method called to associate a EventTaggedPersons object to this object
     * through the EventTaggedPersons foreign key attribute.
     *
     * @param    EventTaggedPersons $l EventTaggedPersons
     * @return ListEvents The current object (for fluent API support)
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
        $eventTaggedPersons->setListEvents($this);
    }

    /**
     * @param	EventTaggedPersons $eventTaggedPersons The eventTaggedPersons object to remove.
     * @return ListEvents The current object (for fluent API support)
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
            $eventTaggedPersons->setListEvents(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this ListEvents is new, it will return
     * an empty collection; or if this ListEvents has previously
     * been saved, it will retrieve related EventTaggedPersonss from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in ListEvents.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|EventTaggedPersons[] List of EventTaggedPersons objects
     */
    public function getEventTaggedPersonssJoinEmpAcc($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = EventTaggedPersonsQuery::create(null, $criteria);
        $query->joinWith('EmpAcc', $join_behavior);

        return $this->getEventTaggedPersonss($query, $con);
    }

    /**
     * Clears out the collEventAttachments collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return ListEvents The current object (for fluent API support)
     * @see        addEventAttachments()
     */
    public function clearEventAttachments()
    {
        $this->collEventAttachments = null; // important to set this to null since that means it is uninitialized
        $this->collEventAttachmentsPartial = null;

        return $this;
    }

    /**
     * reset is the collEventAttachments collection loaded partially
     *
     * @return void
     */
    public function resetPartialEventAttachments($v = true)
    {
        $this->collEventAttachmentsPartial = $v;
    }

    /**
     * Initializes the collEventAttachments collection.
     *
     * By default this just sets the collEventAttachments collection to an empty array (like clearcollEventAttachments());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initEventAttachments($overrideExisting = true)
    {
        if (null !== $this->collEventAttachments && !$overrideExisting) {
            return;
        }
        $this->collEventAttachments = new PropelObjectCollection();
        $this->collEventAttachments->setModel('EventAttachment');
    }

    /**
     * Gets an array of EventAttachment objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ListEvents is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|EventAttachment[] List of EventAttachment objects
     * @throws PropelException
     */
    public function getEventAttachments($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collEventAttachmentsPartial && !$this->isNew();
        if (null === $this->collEventAttachments || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collEventAttachments) {
                // return empty collection
                $this->initEventAttachments();
            } else {
                $collEventAttachments = EventAttachmentQuery::create(null, $criteria)
                    ->filterByListEvents($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collEventAttachmentsPartial && count($collEventAttachments)) {
                      $this->initEventAttachments(false);

                      foreach ($collEventAttachments as $obj) {
                        if (false == $this->collEventAttachments->contains($obj)) {
                          $this->collEventAttachments->append($obj);
                        }
                      }

                      $this->collEventAttachmentsPartial = true;
                    }

                    $collEventAttachments->getInternalIterator()->rewind();

                    return $collEventAttachments;
                }

                if ($partial && $this->collEventAttachments) {
                    foreach ($this->collEventAttachments as $obj) {
                        if ($obj->isNew()) {
                            $collEventAttachments[] = $obj;
                        }
                    }
                }

                $this->collEventAttachments = $collEventAttachments;
                $this->collEventAttachmentsPartial = false;
            }
        }

        return $this->collEventAttachments;
    }

    /**
     * Sets a collection of EventAttachment objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $eventAttachments A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return ListEvents The current object (for fluent API support)
     */
    public function setEventAttachments(PropelCollection $eventAttachments, PropelPDO $con = null)
    {
        $eventAttachmentsToDelete = $this->getEventAttachments(new Criteria(), $con)->diff($eventAttachments);


        $this->eventAttachmentsScheduledForDeletion = $eventAttachmentsToDelete;

        foreach ($eventAttachmentsToDelete as $eventAttachmentRemoved) {
            $eventAttachmentRemoved->setListEvents(null);
        }

        $this->collEventAttachments = null;
        foreach ($eventAttachments as $eventAttachment) {
            $this->addEventAttachment($eventAttachment);
        }

        $this->collEventAttachments = $eventAttachments;
        $this->collEventAttachmentsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related EventAttachment objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related EventAttachment objects.
     * @throws PropelException
     */
    public function countEventAttachments(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collEventAttachmentsPartial && !$this->isNew();
        if (null === $this->collEventAttachments || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collEventAttachments) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getEventAttachments());
            }
            $query = EventAttachmentQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByListEvents($this)
                ->count($con);
        }

        return count($this->collEventAttachments);
    }

    /**
     * Method called to associate a EventAttachment object to this object
     * through the EventAttachment foreign key attribute.
     *
     * @param    EventAttachment $l EventAttachment
     * @return ListEvents The current object (for fluent API support)
     */
    public function addEventAttachment(EventAttachment $l)
    {
        if ($this->collEventAttachments === null) {
            $this->initEventAttachments();
            $this->collEventAttachmentsPartial = true;
        }

        if (!in_array($l, $this->collEventAttachments->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddEventAttachment($l);

            if ($this->eventAttachmentsScheduledForDeletion and $this->eventAttachmentsScheduledForDeletion->contains($l)) {
                $this->eventAttachmentsScheduledForDeletion->remove($this->eventAttachmentsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	EventAttachment $eventAttachment The eventAttachment object to add.
     */
    protected function doAddEventAttachment($eventAttachment)
    {
        $this->collEventAttachments[]= $eventAttachment;
        $eventAttachment->setListEvents($this);
    }

    /**
     * @param	EventAttachment $eventAttachment The eventAttachment object to remove.
     * @return ListEvents The current object (for fluent API support)
     */
    public function removeEventAttachment($eventAttachment)
    {
        if ($this->getEventAttachments()->contains($eventAttachment)) {
            $this->collEventAttachments->remove($this->collEventAttachments->search($eventAttachment));
            if (null === $this->eventAttachmentsScheduledForDeletion) {
                $this->eventAttachmentsScheduledForDeletion = clone $this->collEventAttachments;
                $this->eventAttachmentsScheduledForDeletion->clear();
            }
            $this->eventAttachmentsScheduledForDeletion[]= clone $eventAttachment;
            $eventAttachment->setListEvents(null);
        }

        return $this;
    }

    /**
     * Clears the current object and sets all attributes to their default values
     */
    public function clear()
    {
        $this->id = null;
        $this->created_by = null;
        $this->date_created = null;
        $this->from_date = null;
        $this->to_date = null;
        $this->event_name = null;
        $this->event_venue = null;
        $this->event_desc = null;
        $this->event_type = null;
        $this->status = null;
        $this->is_going = null;
        $this->is_going_note = null;
        $this->sms_response = null;
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
            if ($this->collEventAttachments) {
                foreach ($this->collEventAttachments as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->aListEventsType instanceof Persistent) {
              $this->aListEventsType->clearAllReferences($deep);
            }
            if ($this->aEmpAcc instanceof Persistent) {
              $this->aEmpAcc->clearAllReferences($deep);
            }

            $this->alreadyInClearAllReferencesDeep = false;
        } // if ($deep)

        if ($this->collEventNotess instanceof PropelCollection) {
            $this->collEventNotess->clearIterator();
        }
        $this->collEventNotess = null;
        if ($this->collEventTaggedPersonss instanceof PropelCollection) {
            $this->collEventTaggedPersonss->clearIterator();
        }
        $this->collEventTaggedPersonss = null;
        if ($this->collEventAttachments instanceof PropelCollection) {
            $this->collEventAttachments->clearIterator();
        }
        $this->collEventAttachments = null;
        $this->aListEventsType = null;
        $this->aEmpAcc = null;
    }

    /**
     * return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(ListEventsPeer::DEFAULT_STRING_FORMAT);
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
