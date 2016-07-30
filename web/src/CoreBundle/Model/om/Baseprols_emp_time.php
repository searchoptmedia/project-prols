<?php

namespace CoreBundle\Model\om;

use \BaseObject;
use \BasePeer;
use \Criteria;
use \Exception;
use \PDO;
use \Persistent;
use \Propel;
use \PropelException;
use \PropelPDO;
use CoreBundle\Model\prols_emp_time;
use CoreBundle\Model\prols_emp_timePeer;
use CoreBundle\Model\prols_emp_timeQuery;

abstract class Baseprols_emp_time extends BaseObject implements Persistent
{
    /**
     * Peer class name
     */
    const PEER = 'CoreBundle\\Model\\prols_emp_timePeer';

    /**
     * The Peer class.
     * Instance provides a convenient way of calling static methods on a class
     * that calling code may not be able to identify.
     * @var        prols_emp_timePeer
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
     * The value for the time_in1 field.
     * @var        string
     */
    protected $time_in1;

    /**
     * The value for the time_out1 field.
     * @var        string
     */
    protected $time_out1;

    /**
     * The value for the time_in2 field.
     * @var        string
     */
    protected $time_in2;

    /**
     * The value for the time_out2 field.
     * @var        string
     */
    protected $time_out2;

    /**
     * The value for the time_in_ot field.
     * @var        string
     */
    protected $time_in_ot;

    /**
     * The value for the time_out_ot field.
     * @var        string
     */
    protected $time_out_ot;

    /**
     * The value for the ip_add field.
     * @var        string
     */
    protected $ip_add;

    /**
     * The value for the date field.
     * @var        string
     */
    protected $date;

    /**
     * The value for the acc_id field.
     * @var        int
     */
    protected $acc_id;

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
     * Get the [time_in1] column value.
     *
     * @return string
     */
    public function getTimeIn1()
    {

        return $this->time_in1;
    }

    /**
     * Get the [time_out1] column value.
     *
     * @return string
     */
    public function getTimeOut1()
    {

        return $this->time_out1;
    }

    /**
     * Get the [time_in2] column value.
     *
     * @return string
     */
    public function getTimeIn2()
    {

        return $this->time_in2;
    }

    /**
     * Get the [time_out2] column value.
     *
     * @return string
     */
    public function getTimeOut2()
    {

        return $this->time_out2;
    }

    /**
     * Get the [time_in_ot] column value.
     *
     * @return string
     */
    public function getTimeInOt()
    {

        return $this->time_in_ot;
    }

    /**
     * Get the [time_out_ot] column value.
     *
     * @return string
     */
    public function getTimeOutOt()
    {

        return $this->time_out_ot;
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
     * Get the [date] column value.
     *
     * @return string
     */
    public function getDate()
    {

        return $this->date;
    }

    /**
     * Get the [acc_id] column value.
     *
     * @return int
     */
    public function getAccId()
    {

        return $this->acc_id;
    }

    /**
     * Set the value of [id] column.
     *
     * @param  int $v new value
     * @return prols_emp_time The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[] = prols_emp_timePeer::ID;
        }


        return $this;
    } // setId()

    /**
     * Set the value of [time_in1] column.
     *
     * @param  string $v new value
     * @return prols_emp_time The current object (for fluent API support)
     */
    public function setTimeIn1($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->time_in1 !== $v) {
            $this->time_in1 = $v;
            $this->modifiedColumns[] = prols_emp_timePeer::TIME_IN1;
        }


        return $this;
    } // setTimeIn1()

    /**
     * Set the value of [time_out1] column.
     *
     * @param  string $v new value
     * @return prols_emp_time The current object (for fluent API support)
     */
    public function setTimeOut1($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->time_out1 !== $v) {
            $this->time_out1 = $v;
            $this->modifiedColumns[] = prols_emp_timePeer::TIME_OUT1;
        }


        return $this;
    } // setTimeOut1()

    /**
     * Set the value of [time_in2] column.
     *
     * @param  string $v new value
     * @return prols_emp_time The current object (for fluent API support)
     */
    public function setTimeIn2($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->time_in2 !== $v) {
            $this->time_in2 = $v;
            $this->modifiedColumns[] = prols_emp_timePeer::TIME_IN2;
        }


        return $this;
    } // setTimeIn2()

    /**
     * Set the value of [time_out2] column.
     *
     * @param  string $v new value
     * @return prols_emp_time The current object (for fluent API support)
     */
    public function setTimeOut2($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->time_out2 !== $v) {
            $this->time_out2 = $v;
            $this->modifiedColumns[] = prols_emp_timePeer::TIME_OUT2;
        }


        return $this;
    } // setTimeOut2()

    /**
     * Set the value of [time_in_ot] column.
     *
     * @param  string $v new value
     * @return prols_emp_time The current object (for fluent API support)
     */
    public function setTimeInOt($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->time_in_ot !== $v) {
            $this->time_in_ot = $v;
            $this->modifiedColumns[] = prols_emp_timePeer::TIME_IN_OT;
        }


        return $this;
    } // setTimeInOt()

    /**
     * Set the value of [time_out_ot] column.
     *
     * @param  string $v new value
     * @return prols_emp_time The current object (for fluent API support)
     */
    public function setTimeOutOt($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->time_out_ot !== $v) {
            $this->time_out_ot = $v;
            $this->modifiedColumns[] = prols_emp_timePeer::TIME_OUT_OT;
        }


        return $this;
    } // setTimeOutOt()

    /**
     * Set the value of [ip_add] column.
     *
     * @param  string $v new value
     * @return prols_emp_time The current object (for fluent API support)
     */
    public function setIpAdd($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->ip_add !== $v) {
            $this->ip_add = $v;
            $this->modifiedColumns[] = prols_emp_timePeer::IP_ADD;
        }


        return $this;
    } // setIpAdd()

    /**
     * Set the value of [date] column.
     *
     * @param  string $v new value
     * @return prols_emp_time The current object (for fluent API support)
     */
    public function setDate($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->date !== $v) {
            $this->date = $v;
            $this->modifiedColumns[] = prols_emp_timePeer::DATE;
        }


        return $this;
    } // setDate()

    /**
     * Set the value of [acc_id] column.
     *
     * @param  int $v new value
     * @return prols_emp_time The current object (for fluent API support)
     */
    public function setAccId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->acc_id !== $v) {
            $this->acc_id = $v;
            $this->modifiedColumns[] = prols_emp_timePeer::ACC_ID;
        }


        return $this;
    } // setAccId()

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
            $this->time_in1 = ($row[$startcol + 1] !== null) ? (string) $row[$startcol + 1] : null;
            $this->time_out1 = ($row[$startcol + 2] !== null) ? (string) $row[$startcol + 2] : null;
            $this->time_in2 = ($row[$startcol + 3] !== null) ? (string) $row[$startcol + 3] : null;
            $this->time_out2 = ($row[$startcol + 4] !== null) ? (string) $row[$startcol + 4] : null;
            $this->time_in_ot = ($row[$startcol + 5] !== null) ? (string) $row[$startcol + 5] : null;
            $this->time_out_ot = ($row[$startcol + 6] !== null) ? (string) $row[$startcol + 6] : null;
            $this->ip_add = ($row[$startcol + 7] !== null) ? (string) $row[$startcol + 7] : null;
            $this->date = ($row[$startcol + 8] !== null) ? (string) $row[$startcol + 8] : null;
            $this->acc_id = ($row[$startcol + 9] !== null) ? (int) $row[$startcol + 9] : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }
            $this->postHydrate($row, $startcol, $rehydrate);

            return $startcol + 10; // 10 = prols_emp_timePeer::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException("Error populating prols_emp_time object", $e);
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
            $con = Propel::getConnection(prols_emp_timePeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $stmt = prols_emp_timePeer::doSelectStmt($this->buildPkeyCriteria(), $con);
        $row = $stmt->fetch(PDO::FETCH_NUM);
        $stmt->closeCursor();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true); // rehydrate

        if ($deep) {  // also de-associate any related objects?

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
            $con = Propel::getConnection(prols_emp_timePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        try {
            $deleteQuery = prols_emp_timeQuery::create()
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
            $con = Propel::getConnection(prols_emp_timePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
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
                prols_emp_timePeer::addInstanceToPool($this);
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

        $this->modifiedColumns[] = prols_emp_timePeer::ID;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . prols_emp_timePeer::ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(prols_emp_timePeer::ID)) {
            $modifiedColumns[':p' . $index++]  = '`id`';
        }
        if ($this->isColumnModified(prols_emp_timePeer::TIME_IN1)) {
            $modifiedColumns[':p' . $index++]  = '`time_in1`';
        }
        if ($this->isColumnModified(prols_emp_timePeer::TIME_OUT1)) {
            $modifiedColumns[':p' . $index++]  = '`time_out1`';
        }
        if ($this->isColumnModified(prols_emp_timePeer::TIME_IN2)) {
            $modifiedColumns[':p' . $index++]  = '`time_in2`';
        }
        if ($this->isColumnModified(prols_emp_timePeer::TIME_OUT2)) {
            $modifiedColumns[':p' . $index++]  = '`time_out2`';
        }
        if ($this->isColumnModified(prols_emp_timePeer::TIME_IN_OT)) {
            $modifiedColumns[':p' . $index++]  = '`time_in_ot`';
        }
        if ($this->isColumnModified(prols_emp_timePeer::TIME_OUT_OT)) {
            $modifiedColumns[':p' . $index++]  = '`time_out_ot`';
        }
        if ($this->isColumnModified(prols_emp_timePeer::IP_ADD)) {
            $modifiedColumns[':p' . $index++]  = '`ip_add`';
        }
        if ($this->isColumnModified(prols_emp_timePeer::DATE)) {
            $modifiedColumns[':p' . $index++]  = '`date`';
        }
        if ($this->isColumnModified(prols_emp_timePeer::ACC_ID)) {
            $modifiedColumns[':p' . $index++]  = '`acc_id`';
        }

        $sql = sprintf(
            'INSERT INTO `emp_time` (%s) VALUES (%s)',
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
                    case '`time_in1`':
                        $stmt->bindValue($identifier, $this->time_in1, PDO::PARAM_STR);
                        break;
                    case '`time_out1`':
                        $stmt->bindValue($identifier, $this->time_out1, PDO::PARAM_STR);
                        break;
                    case '`time_in2`':
                        $stmt->bindValue($identifier, $this->time_in2, PDO::PARAM_STR);
                        break;
                    case '`time_out2`':
                        $stmt->bindValue($identifier, $this->time_out2, PDO::PARAM_STR);
                        break;
                    case '`time_in_ot`':
                        $stmt->bindValue($identifier, $this->time_in_ot, PDO::PARAM_STR);
                        break;
                    case '`time_out_ot`':
                        $stmt->bindValue($identifier, $this->time_out_ot, PDO::PARAM_STR);
                        break;
                    case '`ip_add`':
                        $stmt->bindValue($identifier, $this->ip_add, PDO::PARAM_STR);
                        break;
                    case '`date`':
                        $stmt->bindValue($identifier, $this->date, PDO::PARAM_STR);
                        break;
                    case '`acc_id`':
                        $stmt->bindValue($identifier, $this->acc_id, PDO::PARAM_INT);
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


            if (($retval = prols_emp_timePeer::doValidate($this, $columns)) !== true) {
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
        $pos = prols_emp_timePeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
                return $this->getTimeIn1();
                break;
            case 2:
                return $this->getTimeOut1();
                break;
            case 3:
                return $this->getTimeIn2();
                break;
            case 4:
                return $this->getTimeOut2();
                break;
            case 5:
                return $this->getTimeInOt();
                break;
            case 6:
                return $this->getTimeOutOt();
                break;
            case 7:
                return $this->getIpAdd();
                break;
            case 8:
                return $this->getDate();
                break;
            case 9:
                return $this->getAccId();
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
     *
     * @return array an associative array containing the field names (as keys) and field values
     */
    public function toArray($keyType = BasePeer::TYPE_PHPNAME, $includeLazyLoadColumns = true, $alreadyDumpedObjects = array())
    {
        if (isset($alreadyDumpedObjects['prols_emp_time'][$this->getPrimaryKey()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['prols_emp_time'][$this->getPrimaryKey()] = true;
        $keys = prols_emp_timePeer::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getTimeIn1(),
            $keys[2] => $this->getTimeOut1(),
            $keys[3] => $this->getTimeIn2(),
            $keys[4] => $this->getTimeOut2(),
            $keys[5] => $this->getTimeInOt(),
            $keys[6] => $this->getTimeOutOt(),
            $keys[7] => $this->getIpAdd(),
            $keys[8] => $this->getDate(),
            $keys[9] => $this->getAccId(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
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
        $pos = prols_emp_timePeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);

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
                $this->setTimeIn1($value);
                break;
            case 2:
                $this->setTimeOut1($value);
                break;
            case 3:
                $this->setTimeIn2($value);
                break;
            case 4:
                $this->setTimeOut2($value);
                break;
            case 5:
                $this->setTimeInOt($value);
                break;
            case 6:
                $this->setTimeOutOt($value);
                break;
            case 7:
                $this->setIpAdd($value);
                break;
            case 8:
                $this->setDate($value);
                break;
            case 9:
                $this->setAccId($value);
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
        $keys = prols_emp_timePeer::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
        if (array_key_exists($keys[1], $arr)) $this->setTimeIn1($arr[$keys[1]]);
        if (array_key_exists($keys[2], $arr)) $this->setTimeOut1($arr[$keys[2]]);
        if (array_key_exists($keys[3], $arr)) $this->setTimeIn2($arr[$keys[3]]);
        if (array_key_exists($keys[4], $arr)) $this->setTimeOut2($arr[$keys[4]]);
        if (array_key_exists($keys[5], $arr)) $this->setTimeInOt($arr[$keys[5]]);
        if (array_key_exists($keys[6], $arr)) $this->setTimeOutOt($arr[$keys[6]]);
        if (array_key_exists($keys[7], $arr)) $this->setIpAdd($arr[$keys[7]]);
        if (array_key_exists($keys[8], $arr)) $this->setDate($arr[$keys[8]]);
        if (array_key_exists($keys[9], $arr)) $this->setAccId($arr[$keys[9]]);
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(prols_emp_timePeer::DATABASE_NAME);

        if ($this->isColumnModified(prols_emp_timePeer::ID)) $criteria->add(prols_emp_timePeer::ID, $this->id);
        if ($this->isColumnModified(prols_emp_timePeer::TIME_IN1)) $criteria->add(prols_emp_timePeer::TIME_IN1, $this->time_in1);
        if ($this->isColumnModified(prols_emp_timePeer::TIME_OUT1)) $criteria->add(prols_emp_timePeer::TIME_OUT1, $this->time_out1);
        if ($this->isColumnModified(prols_emp_timePeer::TIME_IN2)) $criteria->add(prols_emp_timePeer::TIME_IN2, $this->time_in2);
        if ($this->isColumnModified(prols_emp_timePeer::TIME_OUT2)) $criteria->add(prols_emp_timePeer::TIME_OUT2, $this->time_out2);
        if ($this->isColumnModified(prols_emp_timePeer::TIME_IN_OT)) $criteria->add(prols_emp_timePeer::TIME_IN_OT, $this->time_in_ot);
        if ($this->isColumnModified(prols_emp_timePeer::TIME_OUT_OT)) $criteria->add(prols_emp_timePeer::TIME_OUT_OT, $this->time_out_ot);
        if ($this->isColumnModified(prols_emp_timePeer::IP_ADD)) $criteria->add(prols_emp_timePeer::IP_ADD, $this->ip_add);
        if ($this->isColumnModified(prols_emp_timePeer::DATE)) $criteria->add(prols_emp_timePeer::DATE, $this->date);
        if ($this->isColumnModified(prols_emp_timePeer::ACC_ID)) $criteria->add(prols_emp_timePeer::ACC_ID, $this->acc_id);

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
        $criteria = new Criteria(prols_emp_timePeer::DATABASE_NAME);
        $criteria->add(prols_emp_timePeer::ID, $this->id);

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
     * @param object $copyObj An object of prols_emp_time (or compatible) type.
     * @param boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setTimeIn1($this->getTimeIn1());
        $copyObj->setTimeOut1($this->getTimeOut1());
        $copyObj->setTimeIn2($this->getTimeIn2());
        $copyObj->setTimeOut2($this->getTimeOut2());
        $copyObj->setTimeInOt($this->getTimeInOt());
        $copyObj->setTimeOutOt($this->getTimeOutOt());
        $copyObj->setIpAdd($this->getIpAdd());
        $copyObj->setDate($this->getDate());
        $copyObj->setAccId($this->getAccId());
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
     * @return prols_emp_time Clone of current object.
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
     * @return prols_emp_timePeer
     */
    public function getPeer()
    {
        if (self::$peer === null) {
            self::$peer = new prols_emp_timePeer();
        }

        return self::$peer;
    }

    /**
     * Clears the current object and sets all attributes to their default values
     */
    public function clear()
    {
        $this->id = null;
        $this->time_in1 = null;
        $this->time_out1 = null;
        $this->time_in2 = null;
        $this->time_out2 = null;
        $this->time_in_ot = null;
        $this->time_out_ot = null;
        $this->ip_add = null;
        $this->date = null;
        $this->acc_id = null;
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

            $this->alreadyInClearAllReferencesDeep = false;
        } // if ($deep)

    }

    /**
     * return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(prols_emp_timePeer::DEFAULT_STRING_FORMAT);
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
