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
use \PropelDateTime;
use \PropelException;
use \PropelPDO;
use CoreBundle\Model\EventTagHistory;
use CoreBundle\Model\EventTagHistoryPeer;
use CoreBundle\Model\EventTagHistoryQuery;
use CoreBundle\Model\EventTaggedPersons;
use CoreBundle\Model\EventTaggedPersonsQuery;
use CoreBundle\Model\History;
use CoreBundle\Model\HistoryQuery;

abstract class BaseEventTagHistory extends BaseObject implements Persistent
{
    /**
     * Peer class name
     */
    const PEER = 'CoreBundle\\Model\\EventTagHistoryPeer';

    /**
     * The Peer class.
     * Instance provides a convenient way of calling static methods on a class
     * that calling code may not be able to identify.
     * @var        EventTagHistoryPeer
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
     * The value for the history_id field.
     * @var        int
     */
    protected $history_id;

    /**
     * The value for the event_tag_id field.
     * @var        int
     */
    protected $event_tag_id;

    /**
     * The value for the status field.
     * @var        int
     */
    protected $status;

    /**
     * The value for the message field.
     * @var        string
     */
    protected $message;

    /**
     * The value for the date_created field.
     * @var        string
     */
    protected $date_created;

    /**
     * @var        History
     */
    protected $aHistory;

    /**
     * @var        EventTaggedPersons
     */
    protected $aEventTaggedPersons;

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
     * Get the [id] column value.
     * 
     * @return int
     */
    public function getId()
    {

        return $this->id;
    }

    /**
     * Get the [history_id] column value.
     * 
     * @return int
     */
    public function getHistoryId()
    {

        return $this->history_id;
    }

    /**
     * Get the [event_tag_id] column value.
     * 
     * @return int
     */
    public function getEventTagId()
    {

        return $this->event_tag_id;
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
     * Get the [message] column value.
     * 
     * @return string
     */
    public function getMessage()
    {

        return $this->message;
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
     * Set the value of [id] column.
     * 
     * @param  int $v new value
     * @return EventTagHistory The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[] = EventTagHistoryPeer::ID;
        }


        return $this;
    } // setId()

    /**
     * Set the value of [history_id] column.
     * 
     * @param  int $v new value
     * @return EventTagHistory The current object (for fluent API support)
     */
    public function setHistoryId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->history_id !== $v) {
            $this->history_id = $v;
            $this->modifiedColumns[] = EventTagHistoryPeer::HISTORY_ID;
        }

        if ($this->aHistory !== null && $this->aHistory->getId() !== $v) {
            $this->aHistory = null;
        }


        return $this;
    } // setHistoryId()

    /**
     * Set the value of [event_tag_id] column.
     * 
     * @param  int $v new value
     * @return EventTagHistory The current object (for fluent API support)
     */
    public function setEventTagId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->event_tag_id !== $v) {
            $this->event_tag_id = $v;
            $this->modifiedColumns[] = EventTagHistoryPeer::EVENT_TAG_ID;
        }

        if ($this->aEventTaggedPersons !== null && $this->aEventTaggedPersons->getId() !== $v) {
            $this->aEventTaggedPersons = null;
        }


        return $this;
    } // setEventTagId()

    /**
     * Set the value of [status] column.
     * 
     * @param  int $v new value
     * @return EventTagHistory The current object (for fluent API support)
     */
    public function setStatus($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->status !== $v) {
            $this->status = $v;
            $this->modifiedColumns[] = EventTagHistoryPeer::STATUS;
        }


        return $this;
    } // setStatus()

    /**
     * Set the value of [message] column.
     * 
     * @param  string $v new value
     * @return EventTagHistory The current object (for fluent API support)
     */
    public function setMessage($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->message !== $v) {
            $this->message = $v;
            $this->modifiedColumns[] = EventTagHistoryPeer::MESSAGE;
        }


        return $this;
    } // setMessage()

    /**
     * Sets the value of [date_created] column to a normalized version of the date/time value specified.
     * 
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return EventTagHistory The current object (for fluent API support)
     */
    public function setDateCreated($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->date_created !== null || $dt !== null) {
            $currentDateAsString = ($this->date_created !== null && $tmpDt = new DateTime($this->date_created)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->date_created = $newDateAsString;
                $this->modifiedColumns[] = EventTagHistoryPeer::DATE_CREATED;
            }
        } // if either are not null


        return $this;
    } // setDateCreated()

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
            $this->history_id = ($row[$startcol + 1] !== null) ? (int) $row[$startcol + 1] : null;
            $this->event_tag_id = ($row[$startcol + 2] !== null) ? (int) $row[$startcol + 2] : null;
            $this->status = ($row[$startcol + 3] !== null) ? (int) $row[$startcol + 3] : null;
            $this->message = ($row[$startcol + 4] !== null) ? (string) $row[$startcol + 4] : null;
            $this->date_created = ($row[$startcol + 5] !== null) ? (string) $row[$startcol + 5] : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }
            $this->postHydrate($row, $startcol, $rehydrate);

            return $startcol + 6; // 6 = EventTagHistoryPeer::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException("Error populating EventTagHistory object", $e);
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

        if ($this->aHistory !== null && $this->history_id !== $this->aHistory->getId()) {
            $this->aHistory = null;
        }
        if ($this->aEventTaggedPersons !== null && $this->event_tag_id !== $this->aEventTaggedPersons->getId()) {
            $this->aEventTaggedPersons = null;
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
            $con = Propel::getConnection(EventTagHistoryPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $stmt = EventTagHistoryPeer::doSelectStmt($this->buildPkeyCriteria(), $con);
        $row = $stmt->fetch(PDO::FETCH_NUM);
        $stmt->closeCursor();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aHistory = null;
            $this->aEventTaggedPersons = null;
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
            $con = Propel::getConnection(EventTagHistoryPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        try {
            $deleteQuery = EventTagHistoryQuery::create()
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
            $con = Propel::getConnection(EventTagHistoryPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
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
                EventTagHistoryPeer::addInstanceToPool($this);
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

            if ($this->aHistory !== null) {
                if ($this->aHistory->isModified() || $this->aHistory->isNew()) {
                    $affectedRows += $this->aHistory->save($con);
                }
                $this->setHistory($this->aHistory);
            }

            if ($this->aEventTaggedPersons !== null) {
                if ($this->aEventTaggedPersons->isModified() || $this->aEventTaggedPersons->isNew()) {
                    $affectedRows += $this->aEventTaggedPersons->save($con);
                }
                $this->setEventTaggedPersons($this->aEventTaggedPersons);
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

        $this->modifiedColumns[] = EventTagHistoryPeer::ID;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . EventTagHistoryPeer::ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(EventTagHistoryPeer::ID)) {
            $modifiedColumns[':p' . $index++]  = '`id`';
        }
        if ($this->isColumnModified(EventTagHistoryPeer::HISTORY_ID)) {
            $modifiedColumns[':p' . $index++]  = '`history_id`';
        }
        if ($this->isColumnModified(EventTagHistoryPeer::EVENT_TAG_ID)) {
            $modifiedColumns[':p' . $index++]  = '`event_tag_id`';
        }
        if ($this->isColumnModified(EventTagHistoryPeer::STATUS)) {
            $modifiedColumns[':p' . $index++]  = '`status`';
        }
        if ($this->isColumnModified(EventTagHistoryPeer::MESSAGE)) {
            $modifiedColumns[':p' . $index++]  = '`message`';
        }
        if ($this->isColumnModified(EventTagHistoryPeer::DATE_CREATED)) {
            $modifiedColumns[':p' . $index++]  = '`date_created`';
        }

        $sql = sprintf(
            'INSERT INTO `event_tag_history` (%s) VALUES (%s)',
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
                    case '`history_id`':						
                        $stmt->bindValue($identifier, $this->history_id, PDO::PARAM_INT);
                        break;
                    case '`event_tag_id`':						
                        $stmt->bindValue($identifier, $this->event_tag_id, PDO::PARAM_INT);
                        break;
                    case '`status`':						
                        $stmt->bindValue($identifier, $this->status, PDO::PARAM_INT);
                        break;
                    case '`message`':						
                        $stmt->bindValue($identifier, $this->message, PDO::PARAM_STR);
                        break;
                    case '`date_created`':						
                        $stmt->bindValue($identifier, $this->date_created, PDO::PARAM_STR);
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

            if ($this->aHistory !== null) {
                if (!$this->aHistory->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aHistory->getValidationFailures());
                }
            }

            if ($this->aEventTaggedPersons !== null) {
                if (!$this->aEventTaggedPersons->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aEventTaggedPersons->getValidationFailures());
                }
            }


            if (($retval = EventTagHistoryPeer::doValidate($this, $columns)) !== true) {
                $failureMap = array_merge($failureMap, $retval);
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
        $pos = EventTagHistoryPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
                return $this->getHistoryId();
                break;
            case 2:
                return $this->getEventTagId();
                break;
            case 3:
                return $this->getStatus();
                break;
            case 4:
                return $this->getMessage();
                break;
            case 5:
                return $this->getDateCreated();
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
        if (isset($alreadyDumpedObjects['EventTagHistory'][$this->getPrimaryKey()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['EventTagHistory'][$this->getPrimaryKey()] = true;
        $keys = EventTagHistoryPeer::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getHistoryId(),
            $keys[2] => $this->getEventTagId(),
            $keys[3] => $this->getStatus(),
            $keys[4] => $this->getMessage(),
            $keys[5] => $this->getDateCreated(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }
        
        if ($includeForeignObjects) {
            if (null !== $this->aHistory) {
                $result['History'] = $this->aHistory->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aEventTaggedPersons) {
                $result['EventTaggedPersons'] = $this->aEventTaggedPersons->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
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
        $pos = EventTagHistoryPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);

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
                $this->setHistoryId($value);
                break;
            case 2:
                $this->setEventTagId($value);
                break;
            case 3:
                $this->setStatus($value);
                break;
            case 4:
                $this->setMessage($value);
                break;
            case 5:
                $this->setDateCreated($value);
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
        $keys = EventTagHistoryPeer::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
        if (array_key_exists($keys[1], $arr)) $this->setHistoryId($arr[$keys[1]]);
        if (array_key_exists($keys[2], $arr)) $this->setEventTagId($arr[$keys[2]]);
        if (array_key_exists($keys[3], $arr)) $this->setStatus($arr[$keys[3]]);
        if (array_key_exists($keys[4], $arr)) $this->setMessage($arr[$keys[4]]);
        if (array_key_exists($keys[5], $arr)) $this->setDateCreated($arr[$keys[5]]);
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(EventTagHistoryPeer::DATABASE_NAME);

        if ($this->isColumnModified(EventTagHistoryPeer::ID)) $criteria->add(EventTagHistoryPeer::ID, $this->id);
        if ($this->isColumnModified(EventTagHistoryPeer::HISTORY_ID)) $criteria->add(EventTagHistoryPeer::HISTORY_ID, $this->history_id);
        if ($this->isColumnModified(EventTagHistoryPeer::EVENT_TAG_ID)) $criteria->add(EventTagHistoryPeer::EVENT_TAG_ID, $this->event_tag_id);
        if ($this->isColumnModified(EventTagHistoryPeer::STATUS)) $criteria->add(EventTagHistoryPeer::STATUS, $this->status);
        if ($this->isColumnModified(EventTagHistoryPeer::MESSAGE)) $criteria->add(EventTagHistoryPeer::MESSAGE, $this->message);
        if ($this->isColumnModified(EventTagHistoryPeer::DATE_CREATED)) $criteria->add(EventTagHistoryPeer::DATE_CREATED, $this->date_created);

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
        $criteria = new Criteria(EventTagHistoryPeer::DATABASE_NAME);
        $criteria->add(EventTagHistoryPeer::ID, $this->id);

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
     * @param object $copyObj An object of EventTagHistory (or compatible) type.
     * @param boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setHistoryId($this->getHistoryId());
        $copyObj->setEventTagId($this->getEventTagId());
        $copyObj->setStatus($this->getStatus());
        $copyObj->setMessage($this->getMessage());
        $copyObj->setDateCreated($this->getDateCreated());

        if ($deepCopy && !$this->startCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);
            // store object hash to prevent cycle
            $this->startCopy = true;

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
     * @return EventTagHistory Clone of current object.
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
     * @return EventTagHistoryPeer
     */
    public function getPeer()
    {
        if (self::$peer === null) {
            self::$peer = new EventTagHistoryPeer();
        }

        return self::$peer;
    }

    /**
     * Declares an association between this object and a History object.
     *
     * @param                  History $v
     * @return EventTagHistory The current object (for fluent API support)
     * @throws PropelException
     */
    public function setHistory(History $v = null)
    {
        if ($v === null) {
            $this->setHistoryId(NULL);
        } else {
            $this->setHistoryId($v->getId());
        }

        $this->aHistory = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the History object, it will not be re-added.
        if ($v !== null) {
            $v->addEventTagHistory($this);
        }


        return $this;
    }


    /**
     * Get the associated History object
     *
     * @param PropelPDO $con Optional Connection object.
     * @param $doQuery Executes a query to get the object if required
     * @return History The associated History object.
     * @throws PropelException
     */
    public function getHistory(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aHistory === null && ($this->history_id !== null) && $doQuery) {
            $this->aHistory = HistoryQuery::create()->findPk($this->history_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aHistory->addEventTagHistories($this);
             */
        }

        return $this->aHistory;
    }

    /**
     * Declares an association between this object and a EventTaggedPersons object.
     *
     * @param                  EventTaggedPersons $v
     * @return EventTagHistory The current object (for fluent API support)
     * @throws PropelException
     */
    public function setEventTaggedPersons(EventTaggedPersons $v = null)
    {
        if ($v === null) {
            $this->setEventTagId(NULL);
        } else {
            $this->setEventTagId($v->getId());
        }

        $this->aEventTaggedPersons = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the EventTaggedPersons object, it will not be re-added.
        if ($v !== null) {
            $v->addEventTagHistory($this);
        }


        return $this;
    }


    /**
     * Get the associated EventTaggedPersons object
     *
     * @param PropelPDO $con Optional Connection object.
     * @param $doQuery Executes a query to get the object if required
     * @return EventTaggedPersons The associated EventTaggedPersons object.
     * @throws PropelException
     */
    public function getEventTaggedPersons(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aEventTaggedPersons === null && ($this->event_tag_id !== null) && $doQuery) {
            $this->aEventTaggedPersons = EventTaggedPersonsQuery::create()->findPk($this->event_tag_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aEventTaggedPersons->addEventTagHistories($this);
             */
        }

        return $this->aEventTaggedPersons;
    }

    /**
     * Clears the current object and sets all attributes to their default values
     */
    public function clear()
    {
        $this->id = null;
        $this->history_id = null;
        $this->event_tag_id = null;
        $this->status = null;
        $this->message = null;
        $this->date_created = null;
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
            if ($this->aHistory instanceof Persistent) {
              $this->aHistory->clearAllReferences($deep);
            }
            if ($this->aEventTaggedPersons instanceof Persistent) {
              $this->aEventTaggedPersons->clearAllReferences($deep);
            }

            $this->alreadyInClearAllReferencesDeep = false;
        } // if ($deep)

        $this->aHistory = null;
        $this->aEventTaggedPersons = null;
    }

    /**
     * return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(EventTagHistoryPeer::DEFAULT_STRING_FORMAT);
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
