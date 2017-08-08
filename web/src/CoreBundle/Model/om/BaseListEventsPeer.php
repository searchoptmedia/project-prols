<?php

namespace CoreBundle\Model\om;

use \BasePeer;
use \Criteria;
use \PDO;
use \PDOStatement;
use \Propel;
use \PropelException;
use \PropelPDO;
use CoreBundle\Model\EmpAccPeer;
use CoreBundle\Model\ListEvents;
use CoreBundle\Model\ListEventsPeer;
use CoreBundle\Model\ListEventsTypePeer;
use CoreBundle\Model\map\ListEventsTableMap;

abstract class BaseListEventsPeer
{

    /** the default database name for this class */
    const DATABASE_NAME = 'default';

    /** the table name for this class */
    const TABLE_NAME = 'list_events';

    /** the related Propel class for this table */
    const OM_CLASS = 'CoreBundle\\Model\\ListEvents';

    /** the related TableMap class for this table */
    const TM_CLASS = 'CoreBundle\\Model\\map\\ListEventsTableMap';

    /** The total number of columns. */
    const NUM_COLUMNS = 13;

    /** The number of lazy-loaded columns. */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /** The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS) */
    const NUM_HYDRATE_COLUMNS = 13;

    /** the column name for the id field */
    const ID = 'list_events.id';

    /** the column name for the created_by field */
    const CREATED_BY = 'list_events.created_by';

    /** the column name for the date_created field */
    const DATE_CREATED = 'list_events.date_created';

    /** the column name for the from_date field */
    const FROM_DATE = 'list_events.from_date';

    /** the column name for the to_date field */
    const TO_DATE = 'list_events.to_date';

    /** the column name for the event_name field */
    const EVENT_NAME = 'list_events.event_name';

    /** the column name for the event_venue field */
    const EVENT_VENUE = 'list_events.event_venue';

    /** the column name for the event_desc field */
    const EVENT_DESC = 'list_events.event_desc';

    /** the column name for the event_type field */
    const EVENT_TYPE = 'list_events.event_type';

    /** the column name for the status field */
    const STATUS = 'list_events.status';

    /** the column name for the is_going field */
    const IS_GOING = 'list_events.is_going';

    /** the column name for the is_going_note field */
    const IS_GOING_NOTE = 'list_events.is_going_note';

    /** the column name for the sms_response field */
    const SMS_RESPONSE = 'list_events.sms_response';

    /** The default string format for model objects of the related table **/
    const DEFAULT_STRING_FORMAT = 'YAML';

    /**
     * An identity map to hold any loaded instances of ListEvents objects.
     * This must be public so that other peer classes can access this when hydrating from JOIN
     * queries.
     * @var        array ListEvents[]
     */
    public static $instances = array();


    /**
     * holds an array of fieldnames
     *
     * first dimension keys are the type constants
     * e.g. ListEventsPeer::$fieldNames[ListEventsPeer::TYPE_PHPNAME][0] = 'Id'
     */
    protected static $fieldNames = array (
        BasePeer::TYPE_PHPNAME => array ('Id', 'CreatedBy', 'DateCreated', 'FromDate', 'ToDate', 'EventName', 'EventVenue', 'EventDescription', 'EventType', 'Status', 'IsGoing', 'IsGoingNote', 'SmsResponse', ),
        BasePeer::TYPE_STUDLYPHPNAME => array ('id', 'createdBy', 'dateCreated', 'fromDate', 'toDate', 'eventName', 'eventVenue', 'eventDescription', 'eventType', 'status', 'isGoing', 'isGoingNote', 'smsResponse', ),
        BasePeer::TYPE_COLNAME => array (ListEventsPeer::ID, ListEventsPeer::CREATED_BY, ListEventsPeer::DATE_CREATED, ListEventsPeer::FROM_DATE, ListEventsPeer::TO_DATE, ListEventsPeer::EVENT_NAME, ListEventsPeer::EVENT_VENUE, ListEventsPeer::EVENT_DESC, ListEventsPeer::EVENT_TYPE, ListEventsPeer::STATUS, ListEventsPeer::IS_GOING, ListEventsPeer::IS_GOING_NOTE, ListEventsPeer::SMS_RESPONSE, ),
        BasePeer::TYPE_RAW_COLNAME => array ('ID', 'CREATED_BY', 'DATE_CREATED', 'FROM_DATE', 'TO_DATE', 'EVENT_NAME', 'EVENT_VENUE', 'EVENT_DESC', 'EVENT_TYPE', 'STATUS', 'IS_GOING', 'IS_GOING_NOTE', 'SMS_RESPONSE', ),
        BasePeer::TYPE_FIELDNAME => array ('id', 'created_by', 'date_created', 'from_date', 'to_date', 'event_name', 'event_venue', 'event_desc', 'event_type', 'status', 'is_going', 'is_going_note', 'sms_response', ),
        BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. ListEventsPeer::$fieldNames[BasePeer::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        BasePeer::TYPE_PHPNAME => array ('Id' => 0, 'CreatedBy' => 1, 'DateCreated' => 2, 'FromDate' => 3, 'ToDate' => 4, 'EventName' => 5, 'EventVenue' => 6, 'EventDescription' => 7, 'EventType' => 8, 'Status' => 9, 'IsGoing' => 10, 'IsGoingNote' => 11, 'SmsResponse' => 12, ),
        BasePeer::TYPE_STUDLYPHPNAME => array ('id' => 0, 'createdBy' => 1, 'dateCreated' => 2, 'fromDate' => 3, 'toDate' => 4, 'eventName' => 5, 'eventVenue' => 6, 'eventDescription' => 7, 'eventType' => 8, 'status' => 9, 'isGoing' => 10, 'isGoingNote' => 11, 'smsResponse' => 12, ),
        BasePeer::TYPE_COLNAME => array (ListEventsPeer::ID => 0, ListEventsPeer::CREATED_BY => 1, ListEventsPeer::DATE_CREATED => 2, ListEventsPeer::FROM_DATE => 3, ListEventsPeer::TO_DATE => 4, ListEventsPeer::EVENT_NAME => 5, ListEventsPeer::EVENT_VENUE => 6, ListEventsPeer::EVENT_DESC => 7, ListEventsPeer::EVENT_TYPE => 8, ListEventsPeer::STATUS => 9, ListEventsPeer::IS_GOING => 10, ListEventsPeer::IS_GOING_NOTE => 11, ListEventsPeer::SMS_RESPONSE => 12, ),
        BasePeer::TYPE_RAW_COLNAME => array ('ID' => 0, 'CREATED_BY' => 1, 'DATE_CREATED' => 2, 'FROM_DATE' => 3, 'TO_DATE' => 4, 'EVENT_NAME' => 5, 'EVENT_VENUE' => 6, 'EVENT_DESC' => 7, 'EVENT_TYPE' => 8, 'STATUS' => 9, 'IS_GOING' => 10, 'IS_GOING_NOTE' => 11, 'SMS_RESPONSE' => 12, ),
        BasePeer::TYPE_FIELDNAME => array ('id' => 0, 'created_by' => 1, 'date_created' => 2, 'from_date' => 3, 'to_date' => 4, 'event_name' => 5, 'event_venue' => 6, 'event_desc' => 7, 'event_type' => 8, 'status' => 9, 'is_going' => 10, 'is_going_note' => 11, 'sms_response' => 12, ),
        BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, )
    );

    /**
     * Translates a fieldname to another type
     *
     * @param      string $name field name
     * @param      string $fromType One of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME
     *                         BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM
     * @param      string $toType   One of the class type constants
     * @return string          translated name of the field.
     * @throws PropelException - if the specified name could not be found in the fieldname mappings.
     */
    public static function translateFieldName($name, $fromType, $toType)
    {
        $toNames = ListEventsPeer::getFieldNames($toType);
        $key = isset(ListEventsPeer::$fieldKeys[$fromType][$name]) ? ListEventsPeer::$fieldKeys[$fromType][$name] : null;
        if ($key === null) {
            throw new PropelException("'$name' could not be found in the field names of type '$fromType'. These are: " . print_r(ListEventsPeer::$fieldKeys[$fromType], true));
        }

        return $toNames[$key];
    }

    /**
     * Returns an array of field names.
     *
     * @param      string $type The type of fieldnames to return:
     *                      One of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME
     *                      BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM
     * @return array           A list of field names
     * @throws PropelException - if the type is not valid.
     */
    public static function getFieldNames($type = BasePeer::TYPE_PHPNAME)
    {
        if (!array_key_exists($type, ListEventsPeer::$fieldNames)) {
            throw new PropelException('Method getFieldNames() expects the parameter $type to be one of the class constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME, BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM. ' . $type . ' was given.');
        }

        return ListEventsPeer::$fieldNames[$type];
    }

    /**
     * Convenience method which changes table.column to alias.column.
     *
     * Using this method you can maintain SQL abstraction while using column aliases.
     * <code>
     *		$c->addAlias("alias1", TablePeer::TABLE_NAME);
     *		$c->addJoin(TablePeer::alias("alias1", TablePeer::PRIMARY_KEY_COLUMN), TablePeer::PRIMARY_KEY_COLUMN);
     * </code>
     * @param      string $alias The alias for the current table.
     * @param      string $column The column name for current table. (i.e. ListEventsPeer::COLUMN_NAME).
     * @return string
     */
    public static function alias($alias, $column)
    {
        return str_replace(ListEventsPeer::TABLE_NAME.'.', $alias.'.', $column);
    }

    /**
     * Add all the columns needed to create a new object.
     *
     * Note: any columns that were marked with lazyLoad="true" in the
     * XML schema will not be added to the select list and only loaded
     * on demand.
     *
     * @param      Criteria $criteria object containing the columns to add.
     * @param      string   $alias    optional table alias
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function addSelectColumns(Criteria $criteria, $alias = null)
    {
        if (null === $alias) {
            $criteria->addSelectColumn(ListEventsPeer::ID);
            $criteria->addSelectColumn(ListEventsPeer::CREATED_BY);
            $criteria->addSelectColumn(ListEventsPeer::DATE_CREATED);
            $criteria->addSelectColumn(ListEventsPeer::FROM_DATE);
            $criteria->addSelectColumn(ListEventsPeer::TO_DATE);
            $criteria->addSelectColumn(ListEventsPeer::EVENT_NAME);
            $criteria->addSelectColumn(ListEventsPeer::EVENT_VENUE);
            $criteria->addSelectColumn(ListEventsPeer::EVENT_DESC);
            $criteria->addSelectColumn(ListEventsPeer::EVENT_TYPE);
            $criteria->addSelectColumn(ListEventsPeer::STATUS);
            $criteria->addSelectColumn(ListEventsPeer::IS_GOING);
            $criteria->addSelectColumn(ListEventsPeer::IS_GOING_NOTE);
            $criteria->addSelectColumn(ListEventsPeer::SMS_RESPONSE);
        } else {
            $criteria->addSelectColumn($alias . '.id');
            $criteria->addSelectColumn($alias . '.created_by');
            $criteria->addSelectColumn($alias . '.date_created');
            $criteria->addSelectColumn($alias . '.from_date');
            $criteria->addSelectColumn($alias . '.to_date');
            $criteria->addSelectColumn($alias . '.event_name');
            $criteria->addSelectColumn($alias . '.event_venue');
            $criteria->addSelectColumn($alias . '.event_desc');
            $criteria->addSelectColumn($alias . '.event_type');
            $criteria->addSelectColumn($alias . '.status');
            $criteria->addSelectColumn($alias . '.is_going');
            $criteria->addSelectColumn($alias . '.is_going_note');
            $criteria->addSelectColumn($alias . '.sms_response');
        }
    }

    /**
     * Returns the number of rows matching criteria.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @return int Number of matching rows.
     */
    public static function doCount(Criteria $criteria, $distinct = false, PropelPDO $con = null)
    {
        // we may modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(ListEventsPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            ListEventsPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count
        $criteria->setDbName(ListEventsPeer::DATABASE_NAME); // Set the correct dbName

        if ($con === null) {
            $con = Propel::getConnection(ListEventsPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }
        // BasePeer returns a PDOStatement
        $stmt = BasePeer::doCount($criteria, $con);

        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $count = (int) $row[0];
        } else {
            $count = 0; // no rows returned; we infer that means 0 matches.
        }
        $stmt->closeCursor();

        return $count;
    }
    /**
     * Selects one object from the DB.
     *
     * @param      Criteria $criteria object used to create the SELECT statement.
     * @param      PropelPDO $con
     * @return ListEvents
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectOne(Criteria $criteria, PropelPDO $con = null)
    {
        $critcopy = clone $criteria;
        $critcopy->setLimit(1);
        $objects = ListEventsPeer::doSelect($critcopy, $con);
        if ($objects) {
            return $objects[0];
        }

        return null;
    }
    /**
     * Selects several row from the DB.
     *
     * @param      Criteria $criteria The Criteria object used to build the SELECT statement.
     * @param      PropelPDO $con
     * @return array           Array of selected Objects
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelect(Criteria $criteria, PropelPDO $con = null)
    {
        return ListEventsPeer::populateObjects(ListEventsPeer::doSelectStmt($criteria, $con));
    }
    /**
     * Prepares the Criteria object and uses the parent doSelect() method to execute a PDOStatement.
     *
     * Use this method directly if you want to work with an executed statement directly (for example
     * to perform your own object hydration).
     *
     * @param      Criteria $criteria The Criteria object used to build the SELECT statement.
     * @param      PropelPDO $con The connection to use
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     * @return PDOStatement The executed PDOStatement object.
     * @see        BasePeer::doSelect()
     */
    public static function doSelectStmt(Criteria $criteria, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(ListEventsPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        if (!$criteria->hasSelectClause()) {
            $criteria = clone $criteria;
            ListEventsPeer::addSelectColumns($criteria);
        }

        // Set the correct dbName
        $criteria->setDbName(ListEventsPeer::DATABASE_NAME);

        // BasePeer returns a PDOStatement
        return BasePeer::doSelect($criteria, $con);
    }
    /**
     * Adds an object to the instance pool.
     *
     * Propel keeps cached copies of objects in an instance pool when they are retrieved
     * from the database.  In some cases -- especially when you override doSelect*()
     * methods in your stub classes -- you may need to explicitly add objects
     * to the cache in order to ensure that the same objects are always returned by doSelect*()
     * and retrieveByPK*() calls.
     *
     * @param ListEvents $obj A ListEvents object.
     * @param      string $key (optional) key to use for instance map (for performance boost if key was already calculated externally).
     */
    public static function addInstanceToPool($obj, $key = null)
    {
        if (Propel::isInstancePoolingEnabled()) {
            if ($key === null) {
                $key = (string) $obj->getId();
            } // if key === null
            ListEventsPeer::$instances[$key] = $obj;
        }
    }

    /**
     * Removes an object from the instance pool.
     *
     * Propel keeps cached copies of objects in an instance pool when they are retrieved
     * from the database.  In some cases -- especially when you override doDelete
     * methods in your stub classes -- you may need to explicitly remove objects
     * from the cache in order to prevent returning objects that no longer exist.
     *
     * @param      mixed $value A ListEvents object or a primary key value.
     *
     * @return void
     * @throws PropelException - if the value is invalid.
     */
    public static function removeInstanceFromPool($value)
    {
        if (Propel::isInstancePoolingEnabled() && $value !== null) {
            if (is_object($value) && $value instanceof ListEvents) {
                $key = (string) $value->getId();
            } elseif (is_scalar($value)) {
                // assume we've been passed a primary key
                $key = (string) $value;
            } else {
                $e = new PropelException("Invalid value passed to removeInstanceFromPool().  Expected primary key or ListEvents object; got " . (is_object($value) ? get_class($value) . ' object.' : var_export($value,true)));
                throw $e;
            }

            unset(ListEventsPeer::$instances[$key]);
        }
    } // removeInstanceFromPool()

    /**
     * Retrieves a string version of the primary key from the DB resultset row that can be used to uniquely identify a row in this table.
     *
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, a serialize()d version of the primary key will be returned.
     *
     * @param      string $key The key (@see getPrimaryKeyHash()) for this instance.
     * @return ListEvents Found object or null if 1) no instance exists for specified key or 2) instance pooling has been disabled.
     * @see        getPrimaryKeyHash()
     */
    public static function getInstanceFromPool($key)
    {
        if (Propel::isInstancePoolingEnabled()) {
            if (isset(ListEventsPeer::$instances[$key])) {
                return ListEventsPeer::$instances[$key];
            }
        }

        return null; // just to be explicit
    }

    /**
     * Clear the instance pool.
     *
     * @return void
     */
    public static function clearInstancePool($and_clear_all_references = false)
    {
      if ($and_clear_all_references) {
        foreach (ListEventsPeer::$instances as $instance) {
          $instance->clearAllReferences(true);
        }
      }
        ListEventsPeer::$instances = array();
    }

    /**
     * Method to invalidate the instance pool of all tables related to list_events
     * by a foreign key with ON DELETE CASCADE
     */
    public static function clearRelatedInstancePool()
    {
    }

    /**
     * Retrieves a string version of the primary key from the DB resultset row that can be used to uniquely identify a row in this table.
     *
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, a serialize()d version of the primary key will be returned.
     *
     * @param      array $row PropelPDO resultset row.
     * @param      int $startcol The 0-based offset for reading from the resultset row.
     * @return string A string version of PK or null if the components of primary key in result array are all null.
     */
    public static function getPrimaryKeyHashFromRow($row, $startcol = 0)
    {
        // If the PK cannot be derived from the row, return null.
        if ($row[$startcol] === null) {
            return null;
        }

        return (string) $row[$startcol];
    }

    /**
     * Retrieves the primary key from the DB resultset row
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, an array of the primary key columns will be returned.
     *
     * @param      array $row PropelPDO resultset row.
     * @param      int $startcol The 0-based offset for reading from the resultset row.
     * @return mixed The primary key of the row
     */
    public static function getPrimaryKeyFromRow($row, $startcol = 0)
    {

        return (int) $row[$startcol];
    }

    /**
     * The returned array will contain objects of the default type or
     * objects that inherit from the default.
     *
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function populateObjects(PDOStatement $stmt)
    {
        $results = array();

        // set the class once to avoid overhead in the loop
        $cls = ListEventsPeer::getOMClass();
        // populate the object(s)
        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key = ListEventsPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj = ListEventsPeer::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                ListEventsPeer::addInstanceToPool($obj, $key);
            } // if key exists
        }
        $stmt->closeCursor();

        return $results;
    }
    /**
     * Populates an object of the default type or an object that inherit from the default.
     *
     * @param      array $row PropelPDO resultset row.
     * @param      int $startcol The 0-based offset for reading from the resultset row.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     * @return array (ListEvents object, last column rank)
     */
    public static function populateObject($row, $startcol = 0)
    {
        $key = ListEventsPeer::getPrimaryKeyHashFromRow($row, $startcol);
        if (null !== ($obj = ListEventsPeer::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $startcol, true); // rehydrate
            $col = $startcol + ListEventsPeer::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = ListEventsPeer::OM_CLASS;
            $obj = new $cls();
            $col = $obj->hydrate($row, $startcol);
            ListEventsPeer::addInstanceToPool($obj, $key);
        }

        return array($obj, $col);
    }


    /**
     * Returns the number of rows matching criteria, joining the related ListEventsType table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinListEventsType(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(ListEventsPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            ListEventsPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(ListEventsPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(ListEventsPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(ListEventsPeer::EVENT_TYPE, ListEventsTypePeer::ID, $join_behavior);

        $stmt = BasePeer::doCount($criteria, $con);

        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $count = (int) $row[0];
        } else {
            $count = 0; // no rows returned; we infer that means 0 matches.
        }
        $stmt->closeCursor();

        return $count;
    }


    /**
     * Returns the number of rows matching criteria, joining the related EmpAcc table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinEmpAcc(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(ListEventsPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            ListEventsPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(ListEventsPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(ListEventsPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(ListEventsPeer::CREATED_BY, EmpAccPeer::ID, $join_behavior);

        $stmt = BasePeer::doCount($criteria, $con);

        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $count = (int) $row[0];
        } else {
            $count = 0; // no rows returned; we infer that means 0 matches.
        }
        $stmt->closeCursor();

        return $count;
    }


    /**
     * Selects a collection of ListEvents objects pre-filled with their ListEventsType objects.
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of ListEvents objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinListEventsType(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(ListEventsPeer::DATABASE_NAME);
        }

        ListEventsPeer::addSelectColumns($criteria);
        $startcol = ListEventsPeer::NUM_HYDRATE_COLUMNS;
        ListEventsTypePeer::addSelectColumns($criteria);

        $criteria->addJoin(ListEventsPeer::EVENT_TYPE, ListEventsTypePeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = ListEventsPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = ListEventsPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {

                $cls = ListEventsPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                ListEventsPeer::addInstanceToPool($obj1, $key1);
            } // if $obj1 already loaded

            $key2 = ListEventsTypePeer::getPrimaryKeyHashFromRow($row, $startcol);
            if ($key2 !== null) {
                $obj2 = ListEventsTypePeer::getInstanceFromPool($key2);
                if (!$obj2) {

                    $cls = ListEventsTypePeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol);
                    ListEventsTypePeer::addInstanceToPool($obj2, $key2);
                } // if obj2 already loaded

                // Add the $obj1 (ListEvents) to $obj2 (ListEventsType)
                $obj2->addListEvents($obj1);

            } // if joined row was not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of ListEvents objects pre-filled with their EmpAcc objects.
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of ListEvents objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinEmpAcc(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(ListEventsPeer::DATABASE_NAME);
        }

        ListEventsPeer::addSelectColumns($criteria);
        $startcol = ListEventsPeer::NUM_HYDRATE_COLUMNS;
        EmpAccPeer::addSelectColumns($criteria);

        $criteria->addJoin(ListEventsPeer::CREATED_BY, EmpAccPeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = ListEventsPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = ListEventsPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {

                $cls = ListEventsPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                ListEventsPeer::addInstanceToPool($obj1, $key1);
            } // if $obj1 already loaded

            $key2 = EmpAccPeer::getPrimaryKeyHashFromRow($row, $startcol);
            if ($key2 !== null) {
                $obj2 = EmpAccPeer::getInstanceFromPool($key2);
                if (!$obj2) {

                    $cls = EmpAccPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol);
                    EmpAccPeer::addInstanceToPool($obj2, $key2);
                } // if obj2 already loaded

                // Add the $obj1 (ListEvents) to $obj2 (EmpAcc)
                $obj2->addListEvents($obj1);

            } // if joined row was not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Returns the number of rows matching criteria, joining all related tables
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinAll(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(ListEventsPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            ListEventsPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(ListEventsPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(ListEventsPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(ListEventsPeer::EVENT_TYPE, ListEventsTypePeer::ID, $join_behavior);

        $criteria->addJoin(ListEventsPeer::CREATED_BY, EmpAccPeer::ID, $join_behavior);

        $stmt = BasePeer::doCount($criteria, $con);

        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $count = (int) $row[0];
        } else {
            $count = 0; // no rows returned; we infer that means 0 matches.
        }
        $stmt->closeCursor();

        return $count;
    }

    /**
     * Selects a collection of ListEvents objects pre-filled with all related objects.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of ListEvents objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAll(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(ListEventsPeer::DATABASE_NAME);
        }

        ListEventsPeer::addSelectColumns($criteria);
        $startcol2 = ListEventsPeer::NUM_HYDRATE_COLUMNS;

        ListEventsTypePeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + ListEventsTypePeer::NUM_HYDRATE_COLUMNS;

        EmpAccPeer::addSelectColumns($criteria);
        $startcol4 = $startcol3 + EmpAccPeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(ListEventsPeer::EVENT_TYPE, ListEventsTypePeer::ID, $join_behavior);

        $criteria->addJoin(ListEventsPeer::CREATED_BY, EmpAccPeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = ListEventsPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = ListEventsPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = ListEventsPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                ListEventsPeer::addInstanceToPool($obj1, $key1);
            } // if obj1 already loaded

            // Add objects for joined ListEventsType rows

            $key2 = ListEventsTypePeer::getPrimaryKeyHashFromRow($row, $startcol2);
            if ($key2 !== null) {
                $obj2 = ListEventsTypePeer::getInstanceFromPool($key2);
                if (!$obj2) {

                    $cls = ListEventsTypePeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol2);
                    ListEventsTypePeer::addInstanceToPool($obj2, $key2);
                } // if obj2 loaded

                // Add the $obj1 (ListEvents) to the collection in $obj2 (ListEventsType)
                $obj2->addListEvents($obj1);
            } // if joined row not null

            // Add objects for joined EmpAcc rows

            $key3 = EmpAccPeer::getPrimaryKeyHashFromRow($row, $startcol3);
            if ($key3 !== null) {
                $obj3 = EmpAccPeer::getInstanceFromPool($key3);
                if (!$obj3) {

                    $cls = EmpAccPeer::getOMClass();

                    $obj3 = new $cls();
                    $obj3->hydrate($row, $startcol3);
                    EmpAccPeer::addInstanceToPool($obj3, $key3);
                } // if obj3 loaded

                // Add the $obj1 (ListEvents) to the collection in $obj3 (EmpAcc)
                $obj3->addListEvents($obj1);
            } // if joined row not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Returns the number of rows matching criteria, joining the related ListEventsType table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinAllExceptListEventsType(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(ListEventsPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            ListEventsPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY should not affect count

        // Set the correct dbName
        $criteria->setDbName(ListEventsPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(ListEventsPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(ListEventsPeer::CREATED_BY, EmpAccPeer::ID, $join_behavior);

        $stmt = BasePeer::doCount($criteria, $con);

        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $count = (int) $row[0];
        } else {
            $count = 0; // no rows returned; we infer that means 0 matches.
        }
        $stmt->closeCursor();

        return $count;
    }


    /**
     * Returns the number of rows matching criteria, joining the related EmpAcc table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinAllExceptEmpAcc(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(ListEventsPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            ListEventsPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY should not affect count

        // Set the correct dbName
        $criteria->setDbName(ListEventsPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(ListEventsPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(ListEventsPeer::EVENT_TYPE, ListEventsTypePeer::ID, $join_behavior);

        $stmt = BasePeer::doCount($criteria, $con);

        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $count = (int) $row[0];
        } else {
            $count = 0; // no rows returned; we infer that means 0 matches.
        }
        $stmt->closeCursor();

        return $count;
    }


    /**
     * Selects a collection of ListEvents objects pre-filled with all related objects except ListEventsType.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of ListEvents objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAllExceptListEventsType(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        // $criteria->getDbName() will return the same object if not set to another value
        // so == check is okay and faster
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(ListEventsPeer::DATABASE_NAME);
        }

        ListEventsPeer::addSelectColumns($criteria);
        $startcol2 = ListEventsPeer::NUM_HYDRATE_COLUMNS;

        EmpAccPeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + EmpAccPeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(ListEventsPeer::CREATED_BY, EmpAccPeer::ID, $join_behavior);


        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = ListEventsPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = ListEventsPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = ListEventsPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                ListEventsPeer::addInstanceToPool($obj1, $key1);
            } // if obj1 already loaded

                // Add objects for joined EmpAcc rows

                $key2 = EmpAccPeer::getPrimaryKeyHashFromRow($row, $startcol2);
                if ($key2 !== null) {
                    $obj2 = EmpAccPeer::getInstanceFromPool($key2);
                    if (!$obj2) {

                        $cls = EmpAccPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol2);
                    EmpAccPeer::addInstanceToPool($obj2, $key2);
                } // if $obj2 already loaded

                // Add the $obj1 (ListEvents) to the collection in $obj2 (EmpAcc)
                $obj2->addListEvents($obj1);

            } // if joined row is not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of ListEvents objects pre-filled with all related objects except EmpAcc.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of ListEvents objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAllExceptEmpAcc(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        // $criteria->getDbName() will return the same object if not set to another value
        // so == check is okay and faster
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(ListEventsPeer::DATABASE_NAME);
        }

        ListEventsPeer::addSelectColumns($criteria);
        $startcol2 = ListEventsPeer::NUM_HYDRATE_COLUMNS;

        ListEventsTypePeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + ListEventsTypePeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(ListEventsPeer::EVENT_TYPE, ListEventsTypePeer::ID, $join_behavior);


        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = ListEventsPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = ListEventsPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = ListEventsPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                ListEventsPeer::addInstanceToPool($obj1, $key1);
            } // if obj1 already loaded

                // Add objects for joined ListEventsType rows

                $key2 = ListEventsTypePeer::getPrimaryKeyHashFromRow($row, $startcol2);
                if ($key2 !== null) {
                    $obj2 = ListEventsTypePeer::getInstanceFromPool($key2);
                    if (!$obj2) {

                        $cls = ListEventsTypePeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol2);
                    ListEventsTypePeer::addInstanceToPool($obj2, $key2);
                } // if $obj2 already loaded

                // Add the $obj1 (ListEvents) to the collection in $obj2 (ListEventsType)
                $obj2->addListEvents($obj1);

            } // if joined row is not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }

    /**
     * Returns the TableMap related to this peer.
     * This method is not needed for general use but a specific application could have a need.
     * @return TableMap
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function getTableMap()
    {
        return Propel::getDatabaseMap(ListEventsPeer::DATABASE_NAME)->getTable(ListEventsPeer::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this peer class.
     */
    public static function buildTableMap()
    {
      $dbMap = Propel::getDatabaseMap(BaseListEventsPeer::DATABASE_NAME);
      if (!$dbMap->hasTable(BaseListEventsPeer::TABLE_NAME)) {
        $dbMap->addTableObject(new \CoreBundle\Model\map\ListEventsTableMap());
      }
    }

    /**
     * The class that the Peer will make instances of.
     *
     *
     * @return string ClassName
     */
    public static function getOMClass($row = 0, $colnum = 0)
    {
        return ListEventsPeer::OM_CLASS;
    }

    /**
     * Performs an INSERT on the database, given a ListEvents or Criteria object.
     *
     * @param      mixed $values Criteria or ListEvents object containing data that is used to create the INSERT statement.
     * @param      PropelPDO $con the PropelPDO connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doInsert($values, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(ListEventsPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        if ($values instanceof Criteria) {
            $criteria = clone $values; // rename for clarity
        } else {
            $criteria = $values->buildCriteria(); // build Criteria from ListEvents object
        }

        if ($criteria->containsKey(ListEventsPeer::ID) && $criteria->keyContainsValue(ListEventsPeer::ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.ListEventsPeer::ID.')');
        }


        // Set the correct dbName
        $criteria->setDbName(ListEventsPeer::DATABASE_NAME);

        try {
            // use transaction because $criteria could contain info
            // for more than one table (I guess, conceivably)
            $con->beginTransaction();
            $pk = BasePeer::doInsert($criteria, $con);
            $con->commit();
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }

        return $pk;
    }

    /**
     * Performs an UPDATE on the database, given a ListEvents or Criteria object.
     *
     * @param      mixed $values Criteria or ListEvents object containing data that is used to create the UPDATE statement.
     * @param      PropelPDO $con The connection to use (specify PropelPDO connection object to exert more control over transactions).
     * @return int             The number of affected rows (if supported by underlying database driver).
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doUpdate($values, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(ListEventsPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $selectCriteria = new Criteria(ListEventsPeer::DATABASE_NAME);

        if ($values instanceof Criteria) {
            $criteria = clone $values; // rename for clarity

            $comparison = $criteria->getComparison(ListEventsPeer::ID);
            $value = $criteria->remove(ListEventsPeer::ID);
            if ($value) {
                $selectCriteria->add(ListEventsPeer::ID, $value, $comparison);
            } else {
                $selectCriteria->setPrimaryTableName(ListEventsPeer::TABLE_NAME);
            }

        } else { // $values is ListEvents object
            $criteria = $values->buildCriteria(); // gets full criteria
            $selectCriteria = $values->buildPkeyCriteria(); // gets criteria w/ primary key(s)
        }

        // set the correct dbName
        $criteria->setDbName(ListEventsPeer::DATABASE_NAME);

        return BasePeer::doUpdate($selectCriteria, $criteria, $con);
    }

    /**
     * Deletes all rows from the list_events table.
     *
     * @param      PropelPDO $con the connection to use
     * @return int             The number of affected rows (if supported by underlying database driver).
     * @throws PropelException
     */
    public static function doDeleteAll(PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(ListEventsPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }
        $affectedRows = 0; // initialize var to track total num of affected rows
        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();
            $affectedRows += BasePeer::doDeleteAll(ListEventsPeer::TABLE_NAME, $con, ListEventsPeer::DATABASE_NAME);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            ListEventsPeer::clearInstancePool();
            ListEventsPeer::clearRelatedInstancePool();
            $con->commit();

            return $affectedRows;
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Performs a DELETE on the database, given a ListEvents or Criteria object OR a primary key value.
     *
     * @param      mixed $values Criteria or ListEvents object or primary key or array of primary keys
     *              which is used to create the DELETE statement
     * @param      PropelPDO $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *				if supported by native driver or if emulated using Propel.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
     public static function doDelete($values, PropelPDO $con = null)
     {
        if ($con === null) {
            $con = Propel::getConnection(ListEventsPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        if ($values instanceof Criteria) {
            // invalidate the cache for all objects of this type, since we have no
            // way of knowing (without running a query) what objects should be invalidated
            // from the cache based on this Criteria.
            ListEventsPeer::clearInstancePool();
            // rename for clarity
            $criteria = clone $values;
        } elseif ($values instanceof ListEvents) { // it's a model object
            // invalidate the cache for this single object
            ListEventsPeer::removeInstanceFromPool($values);
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(ListEventsPeer::DATABASE_NAME);
            $criteria->add(ListEventsPeer::ID, (array) $values, Criteria::IN);
            // invalidate the cache for this object(s)
            foreach ((array) $values as $singleval) {
                ListEventsPeer::removeInstanceFromPool($singleval);
            }
        }

        // Set the correct dbName
        $criteria->setDbName(ListEventsPeer::DATABASE_NAME);

        $affectedRows = 0; // initialize var to track total num of affected rows

        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();

            $affectedRows += BasePeer::doDelete($criteria, $con);
            ListEventsPeer::clearRelatedInstancePool();
            $con->commit();

            return $affectedRows;
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Validates all modified columns of given ListEvents object.
     * If parameter $columns is either a single column name or an array of column names
     * than only those columns are validated.
     *
     * NOTICE: This does not apply to primary or foreign keys for now.
     *
     * @param ListEvents $obj The object to validate.
     * @param      mixed $cols Column name or array of column names.
     *
     * @return mixed TRUE if all columns are valid or the error message of the first invalid column.
     */
    public static function doValidate($obj, $cols = null)
    {
        $columns = array();

        if ($cols) {
            $dbMap = Propel::getDatabaseMap(ListEventsPeer::DATABASE_NAME);
            $tableMap = $dbMap->getTable(ListEventsPeer::TABLE_NAME);

            if (! is_array($cols)) {
                $cols = array($cols);
            }

            foreach ($cols as $colName) {
                if ($tableMap->hasColumn($colName)) {
                    $get = 'get' . $tableMap->getColumn($colName)->getPhpName();
                    $columns[$colName] = $obj->$get();
                }
            }
        } else {

        }

        return BasePeer::doValidate(ListEventsPeer::DATABASE_NAME, ListEventsPeer::TABLE_NAME, $columns);
    }

    /**
     * Retrieve a single object by pkey.
     *
     * @param int $pk the primary key.
     * @param      PropelPDO $con the connection to use
     * @return ListEvents
     */
    public static function retrieveByPK($pk, PropelPDO $con = null)
    {

        if (null !== ($obj = ListEventsPeer::getInstanceFromPool((string) $pk))) {
            return $obj;
        }

        if ($con === null) {
            $con = Propel::getConnection(ListEventsPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria = new Criteria(ListEventsPeer::DATABASE_NAME);
        $criteria->add(ListEventsPeer::ID, $pk);

        $v = ListEventsPeer::doSelect($criteria, $con);

        return !empty($v) > 0 ? $v[0] : null;
    }

    /**
     * Retrieve multiple objects by pkey.
     *
     * @param      array $pks List of primary keys
     * @param      PropelPDO $con the connection to use
     * @return ListEvents[]
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function retrieveByPKs($pks, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(ListEventsPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $objs = null;
        if (empty($pks)) {
            $objs = array();
        } else {
            $criteria = new Criteria(ListEventsPeer::DATABASE_NAME);
            $criteria->add(ListEventsPeer::ID, $pks, Criteria::IN);
            $objs = ListEventsPeer::doSelect($criteria, $con);
        }

        return $objs;
    }

} // BaseListEventsPeer

// This is the static code needed to register the TableMap for this table with the main Propel class.
//
BaseListEventsPeer::buildTableMap();

