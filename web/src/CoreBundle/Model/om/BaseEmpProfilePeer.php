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
use CoreBundle\Model\EmpProfile;
use CoreBundle\Model\EmpProfilePeer;
use CoreBundle\Model\EmpStatusTypePeer;
use CoreBundle\Model\ListDeptPeer;
use CoreBundle\Model\ListPosPeer;
use CoreBundle\Model\map\EmpProfileTableMap;

abstract class BaseEmpProfilePeer
{

    /** the default database name for this class */
    const DATABASE_NAME = 'default';

    /** the table name for this class */
    const TABLE_NAME = 'emp_profile';

    /** the related Propel class for this table */
    const OM_CLASS = 'CoreBundle\\Model\\EmpProfile';

    /** the related TableMap class for this table */
    const TM_CLASS = 'CoreBundle\\Model\\map\\EmpProfileTableMap';

    /** The total number of columns. */
    const NUM_COLUMNS = 17;

    /** The number of lazy-loaded columns. */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /** The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS) */
    const NUM_HYDRATE_COLUMNS = 17;

    /** the column name for the id field */
    const ID = 'emp_profile.id';

    /** the column name for the emp_acc_acc_id field */
    const EMP_ACC_ACC_ID = 'emp_profile.emp_acc_acc_id';

    /** the column name for the fname field */
    const FNAME = 'emp_profile.fname';

    /** the column name for the lname field */
    const LNAME = 'emp_profile.lname';

    /** the column name for the mname field */
    const MNAME = 'emp_profile.mname';

    /** the column name for the bday field */
    const BDAY = 'emp_profile.bday';

    /** the column name for the address field */
    const ADDRESS = 'emp_profile.address';

    /** the column name for the gender field */
    const GENDER = 'emp_profile.gender';

    /** the column name for the img_path field */
    const IMG_PATH = 'emp_profile.img_path';

    /** the column name for the date_joined field */
    const DATE_JOINED = 'emp_profile.date_joined';

    /** the column name for the emp_num field */
    const EMP_NUM = 'emp_profile.emp_num';

    /** the column name for the list_dept_id field */
    const LIST_DEPT_ID = 'emp_profile.list_dept_id';

    /** the column name for the list_pos_id field */
    const LIST_POS_ID = 'emp_profile.list_pos_id';

    /** the column name for the status field */
    const STATUS = 'emp_profile.status';

    /** the column name for the sss field */
    const SSS = 'emp_profile.sss';

    /** the column name for the bir field */
    const BIR = 'emp_profile.bir';

    /** the column name for the philhealth field */
    const PHILHEALTH = 'emp_profile.philhealth';

    /** The default string format for model objects of the related table **/
    const DEFAULT_STRING_FORMAT = 'YAML';

    /**
     * An identity map to hold any loaded instances of EmpProfile objects.
     * This must be public so that other peer classes can access this when hydrating from JOIN
     * queries.
     * @var        array EmpProfile[]
     */
    public static $instances = array();


    /**
     * holds an array of fieldnames
     *
     * first dimension keys are the type constants
     * e.g. EmpProfilePeer::$fieldNames[EmpProfilePeer::TYPE_PHPNAME][0] = 'Id'
     */
    protected static $fieldNames = array (
        BasePeer::TYPE_PHPNAME => array ('Id', 'EmpAccAccId', 'Fname', 'Lname', 'Mname', 'Bday', 'Address', 'Gender', 'ImgPath', 'DateJoined', 'EmployeeNumber', 'ListDeptDeptId', 'ListPosPosId', 'Status', 'Sss', 'Bir', 'Philhealth', ),
        BasePeer::TYPE_STUDLYPHPNAME => array ('id', 'empAccAccId', 'fname', 'lname', 'mname', 'bday', 'address', 'gender', 'imgPath', 'dateJoined', 'employeeNumber', 'listDeptDeptId', 'listPosPosId', 'status', 'sss', 'bir', 'philhealth', ),
        BasePeer::TYPE_COLNAME => array (EmpProfilePeer::ID, EmpProfilePeer::EMP_ACC_ACC_ID, EmpProfilePeer::FNAME, EmpProfilePeer::LNAME, EmpProfilePeer::MNAME, EmpProfilePeer::BDAY, EmpProfilePeer::ADDRESS, EmpProfilePeer::GENDER, EmpProfilePeer::IMG_PATH, EmpProfilePeer::DATE_JOINED, EmpProfilePeer::EMP_NUM, EmpProfilePeer::LIST_DEPT_ID, EmpProfilePeer::LIST_POS_ID, EmpProfilePeer::STATUS, EmpProfilePeer::SSS, EmpProfilePeer::BIR, EmpProfilePeer::PHILHEALTH, ),
        BasePeer::TYPE_RAW_COLNAME => array ('ID', 'EMP_ACC_ACC_ID', 'FNAME', 'LNAME', 'MNAME', 'BDAY', 'ADDRESS', 'GENDER', 'IMG_PATH', 'DATE_JOINED', 'EMP_NUM', 'LIST_DEPT_ID', 'LIST_POS_ID', 'STATUS', 'SSS', 'BIR', 'PHILHEALTH', ),
        BasePeer::TYPE_FIELDNAME => array ('id', 'emp_acc_acc_id', 'fname', 'lname', 'mname', 'bday', 'address', 'gender', 'img_path', 'date_joined', 'emp_num', 'list_dept_id', 'list_pos_id', 'status', 'sss', 'bir', 'philhealth', ),
        BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. EmpProfilePeer::$fieldNames[BasePeer::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        BasePeer::TYPE_PHPNAME => array ('Id' => 0, 'EmpAccAccId' => 1, 'Fname' => 2, 'Lname' => 3, 'Mname' => 4, 'Bday' => 5, 'Address' => 6, 'Gender' => 7, 'ImgPath' => 8, 'DateJoined' => 9, 'EmployeeNumber' => 10, 'ListDeptDeptId' => 11, 'ListPosPosId' => 12, 'Status' => 13, 'Sss' => 14, 'Bir' => 15, 'Philhealth' => 16, ),
        BasePeer::TYPE_STUDLYPHPNAME => array ('id' => 0, 'empAccAccId' => 1, 'fname' => 2, 'lname' => 3, 'mname' => 4, 'bday' => 5, 'address' => 6, 'gender' => 7, 'imgPath' => 8, 'dateJoined' => 9, 'employeeNumber' => 10, 'listDeptDeptId' => 11, 'listPosPosId' => 12, 'status' => 13, 'sss' => 14, 'bir' => 15, 'philhealth' => 16, ),
        BasePeer::TYPE_COLNAME => array (EmpProfilePeer::ID => 0, EmpProfilePeer::EMP_ACC_ACC_ID => 1, EmpProfilePeer::FNAME => 2, EmpProfilePeer::LNAME => 3, EmpProfilePeer::MNAME => 4, EmpProfilePeer::BDAY => 5, EmpProfilePeer::ADDRESS => 6, EmpProfilePeer::GENDER => 7, EmpProfilePeer::IMG_PATH => 8, EmpProfilePeer::DATE_JOINED => 9, EmpProfilePeer::EMP_NUM => 10, EmpProfilePeer::LIST_DEPT_ID => 11, EmpProfilePeer::LIST_POS_ID => 12, EmpProfilePeer::STATUS => 13, EmpProfilePeer::SSS => 14, EmpProfilePeer::BIR => 15, EmpProfilePeer::PHILHEALTH => 16, ),
        BasePeer::TYPE_RAW_COLNAME => array ('ID' => 0, 'EMP_ACC_ACC_ID' => 1, 'FNAME' => 2, 'LNAME' => 3, 'MNAME' => 4, 'BDAY' => 5, 'ADDRESS' => 6, 'GENDER' => 7, 'IMG_PATH' => 8, 'DATE_JOINED' => 9, 'EMP_NUM' => 10, 'LIST_DEPT_ID' => 11, 'LIST_POS_ID' => 12, 'STATUS' => 13, 'SSS' => 14, 'BIR' => 15, 'PHILHEALTH' => 16, ),
        BasePeer::TYPE_FIELDNAME => array ('id' => 0, 'emp_acc_acc_id' => 1, 'fname' => 2, 'lname' => 3, 'mname' => 4, 'bday' => 5, 'address' => 6, 'gender' => 7, 'img_path' => 8, 'date_joined' => 9, 'emp_num' => 10, 'list_dept_id' => 11, 'list_pos_id' => 12, 'status' => 13, 'sss' => 14, 'bir' => 15, 'philhealth' => 16, ),
        BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, )
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
        $toNames = EmpProfilePeer::getFieldNames($toType);
        $key = isset(EmpProfilePeer::$fieldKeys[$fromType][$name]) ? EmpProfilePeer::$fieldKeys[$fromType][$name] : null;
        if ($key === null) {
            throw new PropelException("'$name' could not be found in the field names of type '$fromType'. These are: " . print_r(EmpProfilePeer::$fieldKeys[$fromType], true));
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
        if (!array_key_exists($type, EmpProfilePeer::$fieldNames)) {
            throw new PropelException('Method getFieldNames() expects the parameter $type to be one of the class constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME, BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM. ' . $type . ' was given.');
        }

        return EmpProfilePeer::$fieldNames[$type];
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
     * @param      string $column The column name for current table. (i.e. EmpProfilePeer::COLUMN_NAME).
     * @return string
     */
    public static function alias($alias, $column)
    {
        return str_replace(EmpProfilePeer::TABLE_NAME.'.', $alias.'.', $column);
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
            $criteria->addSelectColumn(EmpProfilePeer::ID);
            $criteria->addSelectColumn(EmpProfilePeer::EMP_ACC_ACC_ID);
            $criteria->addSelectColumn(EmpProfilePeer::FNAME);
            $criteria->addSelectColumn(EmpProfilePeer::LNAME);
            $criteria->addSelectColumn(EmpProfilePeer::MNAME);
            $criteria->addSelectColumn(EmpProfilePeer::BDAY);
            $criteria->addSelectColumn(EmpProfilePeer::ADDRESS);
            $criteria->addSelectColumn(EmpProfilePeer::GENDER);
            $criteria->addSelectColumn(EmpProfilePeer::IMG_PATH);
            $criteria->addSelectColumn(EmpProfilePeer::DATE_JOINED);
            $criteria->addSelectColumn(EmpProfilePeer::EMP_NUM);
            $criteria->addSelectColumn(EmpProfilePeer::LIST_DEPT_ID);
            $criteria->addSelectColumn(EmpProfilePeer::LIST_POS_ID);
            $criteria->addSelectColumn(EmpProfilePeer::STATUS);
            $criteria->addSelectColumn(EmpProfilePeer::SSS);
            $criteria->addSelectColumn(EmpProfilePeer::BIR);
            $criteria->addSelectColumn(EmpProfilePeer::PHILHEALTH);
        } else {
            $criteria->addSelectColumn($alias . '.id');
            $criteria->addSelectColumn($alias . '.emp_acc_acc_id');
            $criteria->addSelectColumn($alias . '.fname');
            $criteria->addSelectColumn($alias . '.lname');
            $criteria->addSelectColumn($alias . '.mname');
            $criteria->addSelectColumn($alias . '.bday');
            $criteria->addSelectColumn($alias . '.address');
            $criteria->addSelectColumn($alias . '.gender');
            $criteria->addSelectColumn($alias . '.img_path');
            $criteria->addSelectColumn($alias . '.date_joined');
            $criteria->addSelectColumn($alias . '.emp_num');
            $criteria->addSelectColumn($alias . '.list_dept_id');
            $criteria->addSelectColumn($alias . '.list_pos_id');
            $criteria->addSelectColumn($alias . '.status');
            $criteria->addSelectColumn($alias . '.sss');
            $criteria->addSelectColumn($alias . '.bir');
            $criteria->addSelectColumn($alias . '.philhealth');
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
        $criteria->setPrimaryTableName(EmpProfilePeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            EmpProfilePeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count
        $criteria->setDbName(EmpProfilePeer::DATABASE_NAME); // Set the correct dbName

        if ($con === null) {
            $con = Propel::getConnection(EmpProfilePeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return EmpProfile
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectOne(Criteria $criteria, PropelPDO $con = null)
    {
        $critcopy = clone $criteria;
        $critcopy->setLimit(1);
        $objects = EmpProfilePeer::doSelect($critcopy, $con);
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
        return EmpProfilePeer::populateObjects(EmpProfilePeer::doSelectStmt($criteria, $con));
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
            $con = Propel::getConnection(EmpProfilePeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        if (!$criteria->hasSelectClause()) {
            $criteria = clone $criteria;
            EmpProfilePeer::addSelectColumns($criteria);
        }

        // Set the correct dbName
        $criteria->setDbName(EmpProfilePeer::DATABASE_NAME);

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
     * @param EmpProfile $obj A EmpProfile object.
     * @param      string $key (optional) key to use for instance map (for performance boost if key was already calculated externally).
     */
    public static function addInstanceToPool($obj, $key = null)
    {
        if (Propel::isInstancePoolingEnabled()) {
            if ($key === null) {
                $key = (string) $obj->getId();
            } // if key === null
            EmpProfilePeer::$instances[$key] = $obj;
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
     * @param      mixed $value A EmpProfile object or a primary key value.
     *
     * @return void
     * @throws PropelException - if the value is invalid.
     */
    public static function removeInstanceFromPool($value)
    {
        if (Propel::isInstancePoolingEnabled() && $value !== null) {
            if (is_object($value) && $value instanceof EmpProfile) {
                $key = (string) $value->getId();
            } elseif (is_scalar($value)) {
                // assume we've been passed a primary key
                $key = (string) $value;
            } else {
                $e = new PropelException("Invalid value passed to removeInstanceFromPool().  Expected primary key or EmpProfile object; got " . (is_object($value) ? get_class($value) . ' object.' : var_export($value,true)));
                throw $e;
            }

            unset(EmpProfilePeer::$instances[$key]);
        }
    } // removeInstanceFromPool()

    /**
     * Retrieves a string version of the primary key from the DB resultset row that can be used to uniquely identify a row in this table.
     *
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, a serialize()d version of the primary key will be returned.
     *
     * @param      string $key The key (@see getPrimaryKeyHash()) for this instance.
     * @return EmpProfile Found object or null if 1) no instance exists for specified key or 2) instance pooling has been disabled.
     * @see        getPrimaryKeyHash()
     */
    public static function getInstanceFromPool($key)
    {
        if (Propel::isInstancePoolingEnabled()) {
            if (isset(EmpProfilePeer::$instances[$key])) {
                return EmpProfilePeer::$instances[$key];
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
        foreach (EmpProfilePeer::$instances as $instance) {
          $instance->clearAllReferences(true);
        }
      }
        EmpProfilePeer::$instances = array();
    }

    /**
     * Method to invalidate the instance pool of all tables related to emp_profile
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
        $cls = EmpProfilePeer::getOMClass();
        // populate the object(s)
        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key = EmpProfilePeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj = EmpProfilePeer::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                EmpProfilePeer::addInstanceToPool($obj, $key);
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
     * @return array (EmpProfile object, last column rank)
     */
    public static function populateObject($row, $startcol = 0)
    {
        $key = EmpProfilePeer::getPrimaryKeyHashFromRow($row, $startcol);
        if (null !== ($obj = EmpProfilePeer::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $startcol, true); // rehydrate
            $col = $startcol + EmpProfilePeer::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = EmpProfilePeer::OM_CLASS;
            $obj = new $cls();
            $col = $obj->hydrate($row, $startcol);
            EmpProfilePeer::addInstanceToPool($obj, $key);
        }

        return array($obj, $col);
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
        $criteria->setPrimaryTableName(EmpProfilePeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            EmpProfilePeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(EmpProfilePeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(EmpProfilePeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(EmpProfilePeer::EMP_ACC_ACC_ID, EmpAccPeer::ID, $join_behavior);

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
     * Returns the number of rows matching criteria, joining the related ListDept table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinListDept(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(EmpProfilePeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            EmpProfilePeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(EmpProfilePeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(EmpProfilePeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(EmpProfilePeer::LIST_DEPT_ID, ListDeptPeer::ID, $join_behavior);

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
     * Returns the number of rows matching criteria, joining the related ListPos table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinListPos(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(EmpProfilePeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            EmpProfilePeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(EmpProfilePeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(EmpProfilePeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(EmpProfilePeer::LIST_POS_ID, ListPosPeer::ID, $join_behavior);

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
     * Returns the number of rows matching criteria, joining the related EmpStatusType table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinEmpStatusType(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(EmpProfilePeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            EmpProfilePeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(EmpProfilePeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(EmpProfilePeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(EmpProfilePeer::STATUS, EmpStatusTypePeer::ID, $join_behavior);

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
     * Selects a collection of EmpProfile objects pre-filled with their EmpAcc objects.
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of EmpProfile objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinEmpAcc(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(EmpProfilePeer::DATABASE_NAME);
        }

        EmpProfilePeer::addSelectColumns($criteria);
        $startcol = EmpProfilePeer::NUM_HYDRATE_COLUMNS;
        EmpAccPeer::addSelectColumns($criteria);

        $criteria->addJoin(EmpProfilePeer::EMP_ACC_ACC_ID, EmpAccPeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = EmpProfilePeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = EmpProfilePeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {

                $cls = EmpProfilePeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                EmpProfilePeer::addInstanceToPool($obj1, $key1);
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

                // Add the $obj1 (EmpProfile) to $obj2 (EmpAcc)
                $obj2->addEmpProfile($obj1);

            } // if joined row was not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of EmpProfile objects pre-filled with their ListDept objects.
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of EmpProfile objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinListDept(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(EmpProfilePeer::DATABASE_NAME);
        }

        EmpProfilePeer::addSelectColumns($criteria);
        $startcol = EmpProfilePeer::NUM_HYDRATE_COLUMNS;
        ListDeptPeer::addSelectColumns($criteria);

        $criteria->addJoin(EmpProfilePeer::LIST_DEPT_ID, ListDeptPeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = EmpProfilePeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = EmpProfilePeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {

                $cls = EmpProfilePeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                EmpProfilePeer::addInstanceToPool($obj1, $key1);
            } // if $obj1 already loaded

            $key2 = ListDeptPeer::getPrimaryKeyHashFromRow($row, $startcol);
            if ($key2 !== null) {
                $obj2 = ListDeptPeer::getInstanceFromPool($key2);
                if (!$obj2) {

                    $cls = ListDeptPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol);
                    ListDeptPeer::addInstanceToPool($obj2, $key2);
                } // if obj2 already loaded

                // Add the $obj1 (EmpProfile) to $obj2 (ListDept)
                $obj2->addEmpProfile($obj1);

            } // if joined row was not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of EmpProfile objects pre-filled with their ListPos objects.
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of EmpProfile objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinListPos(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(EmpProfilePeer::DATABASE_NAME);
        }

        EmpProfilePeer::addSelectColumns($criteria);
        $startcol = EmpProfilePeer::NUM_HYDRATE_COLUMNS;
        ListPosPeer::addSelectColumns($criteria);

        $criteria->addJoin(EmpProfilePeer::LIST_POS_ID, ListPosPeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = EmpProfilePeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = EmpProfilePeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {

                $cls = EmpProfilePeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                EmpProfilePeer::addInstanceToPool($obj1, $key1);
            } // if $obj1 already loaded

            $key2 = ListPosPeer::getPrimaryKeyHashFromRow($row, $startcol);
            if ($key2 !== null) {
                $obj2 = ListPosPeer::getInstanceFromPool($key2);
                if (!$obj2) {

                    $cls = ListPosPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol);
                    ListPosPeer::addInstanceToPool($obj2, $key2);
                } // if obj2 already loaded

                // Add the $obj1 (EmpProfile) to $obj2 (ListPos)
                $obj2->addEmpProfile($obj1);

            } // if joined row was not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of EmpProfile objects pre-filled with their EmpStatusType objects.
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of EmpProfile objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinEmpStatusType(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(EmpProfilePeer::DATABASE_NAME);
        }

        EmpProfilePeer::addSelectColumns($criteria);
        $startcol = EmpProfilePeer::NUM_HYDRATE_COLUMNS;
        EmpStatusTypePeer::addSelectColumns($criteria);

        $criteria->addJoin(EmpProfilePeer::STATUS, EmpStatusTypePeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = EmpProfilePeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = EmpProfilePeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {

                $cls = EmpProfilePeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                EmpProfilePeer::addInstanceToPool($obj1, $key1);
            } // if $obj1 already loaded

            $key2 = EmpStatusTypePeer::getPrimaryKeyHashFromRow($row, $startcol);
            if ($key2 !== null) {
                $obj2 = EmpStatusTypePeer::getInstanceFromPool($key2);
                if (!$obj2) {

                    $cls = EmpStatusTypePeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol);
                    EmpStatusTypePeer::addInstanceToPool($obj2, $key2);
                } // if obj2 already loaded

                // Add the $obj1 (EmpProfile) to $obj2 (EmpStatusType)
                $obj2->addEmpProfile($obj1);

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
        $criteria->setPrimaryTableName(EmpProfilePeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            EmpProfilePeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(EmpProfilePeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(EmpProfilePeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(EmpProfilePeer::EMP_ACC_ACC_ID, EmpAccPeer::ID, $join_behavior);

        $criteria->addJoin(EmpProfilePeer::LIST_DEPT_ID, ListDeptPeer::ID, $join_behavior);

        $criteria->addJoin(EmpProfilePeer::LIST_POS_ID, ListPosPeer::ID, $join_behavior);

        $criteria->addJoin(EmpProfilePeer::STATUS, EmpStatusTypePeer::ID, $join_behavior);

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
     * Selects a collection of EmpProfile objects pre-filled with all related objects.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of EmpProfile objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAll(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(EmpProfilePeer::DATABASE_NAME);
        }

        EmpProfilePeer::addSelectColumns($criteria);
        $startcol2 = EmpProfilePeer::NUM_HYDRATE_COLUMNS;

        EmpAccPeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + EmpAccPeer::NUM_HYDRATE_COLUMNS;

        ListDeptPeer::addSelectColumns($criteria);
        $startcol4 = $startcol3 + ListDeptPeer::NUM_HYDRATE_COLUMNS;

        ListPosPeer::addSelectColumns($criteria);
        $startcol5 = $startcol4 + ListPosPeer::NUM_HYDRATE_COLUMNS;

        EmpStatusTypePeer::addSelectColumns($criteria);
        $startcol6 = $startcol5 + EmpStatusTypePeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(EmpProfilePeer::EMP_ACC_ACC_ID, EmpAccPeer::ID, $join_behavior);

        $criteria->addJoin(EmpProfilePeer::LIST_DEPT_ID, ListDeptPeer::ID, $join_behavior);

        $criteria->addJoin(EmpProfilePeer::LIST_POS_ID, ListPosPeer::ID, $join_behavior);

        $criteria->addJoin(EmpProfilePeer::STATUS, EmpStatusTypePeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = EmpProfilePeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = EmpProfilePeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = EmpProfilePeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                EmpProfilePeer::addInstanceToPool($obj1, $key1);
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
                } // if obj2 loaded

                // Add the $obj1 (EmpProfile) to the collection in $obj2 (EmpAcc)
                $obj2->addEmpProfile($obj1);
            } // if joined row not null

            // Add objects for joined ListDept rows

            $key3 = ListDeptPeer::getPrimaryKeyHashFromRow($row, $startcol3);
            if ($key3 !== null) {
                $obj3 = ListDeptPeer::getInstanceFromPool($key3);
                if (!$obj3) {

                    $cls = ListDeptPeer::getOMClass();

                    $obj3 = new $cls();
                    $obj3->hydrate($row, $startcol3);
                    ListDeptPeer::addInstanceToPool($obj3, $key3);
                } // if obj3 loaded

                // Add the $obj1 (EmpProfile) to the collection in $obj3 (ListDept)
                $obj3->addEmpProfile($obj1);
            } // if joined row not null

            // Add objects for joined ListPos rows

            $key4 = ListPosPeer::getPrimaryKeyHashFromRow($row, $startcol4);
            if ($key4 !== null) {
                $obj4 = ListPosPeer::getInstanceFromPool($key4);
                if (!$obj4) {

                    $cls = ListPosPeer::getOMClass();

                    $obj4 = new $cls();
                    $obj4->hydrate($row, $startcol4);
                    ListPosPeer::addInstanceToPool($obj4, $key4);
                } // if obj4 loaded

                // Add the $obj1 (EmpProfile) to the collection in $obj4 (ListPos)
                $obj4->addEmpProfile($obj1);
            } // if joined row not null

            // Add objects for joined EmpStatusType rows

            $key5 = EmpStatusTypePeer::getPrimaryKeyHashFromRow($row, $startcol5);
            if ($key5 !== null) {
                $obj5 = EmpStatusTypePeer::getInstanceFromPool($key5);
                if (!$obj5) {

                    $cls = EmpStatusTypePeer::getOMClass();

                    $obj5 = new $cls();
                    $obj5->hydrate($row, $startcol5);
                    EmpStatusTypePeer::addInstanceToPool($obj5, $key5);
                } // if obj5 loaded

                // Add the $obj1 (EmpProfile) to the collection in $obj5 (EmpStatusType)
                $obj5->addEmpProfile($obj1);
            } // if joined row not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
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
        $criteria->setPrimaryTableName(EmpProfilePeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            EmpProfilePeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY should not affect count

        // Set the correct dbName
        $criteria->setDbName(EmpProfilePeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(EmpProfilePeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(EmpProfilePeer::LIST_DEPT_ID, ListDeptPeer::ID, $join_behavior);

        $criteria->addJoin(EmpProfilePeer::LIST_POS_ID, ListPosPeer::ID, $join_behavior);

        $criteria->addJoin(EmpProfilePeer::STATUS, EmpStatusTypePeer::ID, $join_behavior);

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
     * Returns the number of rows matching criteria, joining the related ListDept table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinAllExceptListDept(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(EmpProfilePeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            EmpProfilePeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY should not affect count

        // Set the correct dbName
        $criteria->setDbName(EmpProfilePeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(EmpProfilePeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(EmpProfilePeer::EMP_ACC_ACC_ID, EmpAccPeer::ID, $join_behavior);

        $criteria->addJoin(EmpProfilePeer::LIST_POS_ID, ListPosPeer::ID, $join_behavior);

        $criteria->addJoin(EmpProfilePeer::STATUS, EmpStatusTypePeer::ID, $join_behavior);

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
     * Returns the number of rows matching criteria, joining the related ListPos table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinAllExceptListPos(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(EmpProfilePeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            EmpProfilePeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY should not affect count

        // Set the correct dbName
        $criteria->setDbName(EmpProfilePeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(EmpProfilePeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(EmpProfilePeer::EMP_ACC_ACC_ID, EmpAccPeer::ID, $join_behavior);

        $criteria->addJoin(EmpProfilePeer::LIST_DEPT_ID, ListDeptPeer::ID, $join_behavior);

        $criteria->addJoin(EmpProfilePeer::STATUS, EmpStatusTypePeer::ID, $join_behavior);

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
     * Returns the number of rows matching criteria, joining the related EmpStatusType table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinAllExceptEmpStatusType(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(EmpProfilePeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            EmpProfilePeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY should not affect count

        // Set the correct dbName
        $criteria->setDbName(EmpProfilePeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(EmpProfilePeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(EmpProfilePeer::EMP_ACC_ACC_ID, EmpAccPeer::ID, $join_behavior);

        $criteria->addJoin(EmpProfilePeer::LIST_DEPT_ID, ListDeptPeer::ID, $join_behavior);

        $criteria->addJoin(EmpProfilePeer::LIST_POS_ID, ListPosPeer::ID, $join_behavior);

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
     * Selects a collection of EmpProfile objects pre-filled with all related objects except EmpAcc.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of EmpProfile objects.
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
            $criteria->setDbName(EmpProfilePeer::DATABASE_NAME);
        }

        EmpProfilePeer::addSelectColumns($criteria);
        $startcol2 = EmpProfilePeer::NUM_HYDRATE_COLUMNS;

        ListDeptPeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + ListDeptPeer::NUM_HYDRATE_COLUMNS;

        ListPosPeer::addSelectColumns($criteria);
        $startcol4 = $startcol3 + ListPosPeer::NUM_HYDRATE_COLUMNS;

        EmpStatusTypePeer::addSelectColumns($criteria);
        $startcol5 = $startcol4 + EmpStatusTypePeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(EmpProfilePeer::LIST_DEPT_ID, ListDeptPeer::ID, $join_behavior);

        $criteria->addJoin(EmpProfilePeer::LIST_POS_ID, ListPosPeer::ID, $join_behavior);

        $criteria->addJoin(EmpProfilePeer::STATUS, EmpStatusTypePeer::ID, $join_behavior);


        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = EmpProfilePeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = EmpProfilePeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = EmpProfilePeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                EmpProfilePeer::addInstanceToPool($obj1, $key1);
            } // if obj1 already loaded

                // Add objects for joined ListDept rows

                $key2 = ListDeptPeer::getPrimaryKeyHashFromRow($row, $startcol2);
                if ($key2 !== null) {
                    $obj2 = ListDeptPeer::getInstanceFromPool($key2);
                    if (!$obj2) {

                        $cls = ListDeptPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol2);
                    ListDeptPeer::addInstanceToPool($obj2, $key2);
                } // if $obj2 already loaded

                // Add the $obj1 (EmpProfile) to the collection in $obj2 (ListDept)
                $obj2->addEmpProfile($obj1);

            } // if joined row is not null

                // Add objects for joined ListPos rows

                $key3 = ListPosPeer::getPrimaryKeyHashFromRow($row, $startcol3);
                if ($key3 !== null) {
                    $obj3 = ListPosPeer::getInstanceFromPool($key3);
                    if (!$obj3) {

                        $cls = ListPosPeer::getOMClass();

                    $obj3 = new $cls();
                    $obj3->hydrate($row, $startcol3);
                    ListPosPeer::addInstanceToPool($obj3, $key3);
                } // if $obj3 already loaded

                // Add the $obj1 (EmpProfile) to the collection in $obj3 (ListPos)
                $obj3->addEmpProfile($obj1);

            } // if joined row is not null

                // Add objects for joined EmpStatusType rows

                $key4 = EmpStatusTypePeer::getPrimaryKeyHashFromRow($row, $startcol4);
                if ($key4 !== null) {
                    $obj4 = EmpStatusTypePeer::getInstanceFromPool($key4);
                    if (!$obj4) {

                        $cls = EmpStatusTypePeer::getOMClass();

                    $obj4 = new $cls();
                    $obj4->hydrate($row, $startcol4);
                    EmpStatusTypePeer::addInstanceToPool($obj4, $key4);
                } // if $obj4 already loaded

                // Add the $obj1 (EmpProfile) to the collection in $obj4 (EmpStatusType)
                $obj4->addEmpProfile($obj1);

            } // if joined row is not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of EmpProfile objects pre-filled with all related objects except ListDept.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of EmpProfile objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAllExceptListDept(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        // $criteria->getDbName() will return the same object if not set to another value
        // so == check is okay and faster
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(EmpProfilePeer::DATABASE_NAME);
        }

        EmpProfilePeer::addSelectColumns($criteria);
        $startcol2 = EmpProfilePeer::NUM_HYDRATE_COLUMNS;

        EmpAccPeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + EmpAccPeer::NUM_HYDRATE_COLUMNS;

        ListPosPeer::addSelectColumns($criteria);
        $startcol4 = $startcol3 + ListPosPeer::NUM_HYDRATE_COLUMNS;

        EmpStatusTypePeer::addSelectColumns($criteria);
        $startcol5 = $startcol4 + EmpStatusTypePeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(EmpProfilePeer::EMP_ACC_ACC_ID, EmpAccPeer::ID, $join_behavior);

        $criteria->addJoin(EmpProfilePeer::LIST_POS_ID, ListPosPeer::ID, $join_behavior);

        $criteria->addJoin(EmpProfilePeer::STATUS, EmpStatusTypePeer::ID, $join_behavior);


        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = EmpProfilePeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = EmpProfilePeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = EmpProfilePeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                EmpProfilePeer::addInstanceToPool($obj1, $key1);
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

                // Add the $obj1 (EmpProfile) to the collection in $obj2 (EmpAcc)
                $obj2->addEmpProfile($obj1);

            } // if joined row is not null

                // Add objects for joined ListPos rows

                $key3 = ListPosPeer::getPrimaryKeyHashFromRow($row, $startcol3);
                if ($key3 !== null) {
                    $obj3 = ListPosPeer::getInstanceFromPool($key3);
                    if (!$obj3) {

                        $cls = ListPosPeer::getOMClass();

                    $obj3 = new $cls();
                    $obj3->hydrate($row, $startcol3);
                    ListPosPeer::addInstanceToPool($obj3, $key3);
                } // if $obj3 already loaded

                // Add the $obj1 (EmpProfile) to the collection in $obj3 (ListPos)
                $obj3->addEmpProfile($obj1);

            } // if joined row is not null

                // Add objects for joined EmpStatusType rows

                $key4 = EmpStatusTypePeer::getPrimaryKeyHashFromRow($row, $startcol4);
                if ($key4 !== null) {
                    $obj4 = EmpStatusTypePeer::getInstanceFromPool($key4);
                    if (!$obj4) {

                        $cls = EmpStatusTypePeer::getOMClass();

                    $obj4 = new $cls();
                    $obj4->hydrate($row, $startcol4);
                    EmpStatusTypePeer::addInstanceToPool($obj4, $key4);
                } // if $obj4 already loaded

                // Add the $obj1 (EmpProfile) to the collection in $obj4 (EmpStatusType)
                $obj4->addEmpProfile($obj1);

            } // if joined row is not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of EmpProfile objects pre-filled with all related objects except ListPos.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of EmpProfile objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAllExceptListPos(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        // $criteria->getDbName() will return the same object if not set to another value
        // so == check is okay and faster
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(EmpProfilePeer::DATABASE_NAME);
        }

        EmpProfilePeer::addSelectColumns($criteria);
        $startcol2 = EmpProfilePeer::NUM_HYDRATE_COLUMNS;

        EmpAccPeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + EmpAccPeer::NUM_HYDRATE_COLUMNS;

        ListDeptPeer::addSelectColumns($criteria);
        $startcol4 = $startcol3 + ListDeptPeer::NUM_HYDRATE_COLUMNS;

        EmpStatusTypePeer::addSelectColumns($criteria);
        $startcol5 = $startcol4 + EmpStatusTypePeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(EmpProfilePeer::EMP_ACC_ACC_ID, EmpAccPeer::ID, $join_behavior);

        $criteria->addJoin(EmpProfilePeer::LIST_DEPT_ID, ListDeptPeer::ID, $join_behavior);

        $criteria->addJoin(EmpProfilePeer::STATUS, EmpStatusTypePeer::ID, $join_behavior);


        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = EmpProfilePeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = EmpProfilePeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = EmpProfilePeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                EmpProfilePeer::addInstanceToPool($obj1, $key1);
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

                // Add the $obj1 (EmpProfile) to the collection in $obj2 (EmpAcc)
                $obj2->addEmpProfile($obj1);

            } // if joined row is not null

                // Add objects for joined ListDept rows

                $key3 = ListDeptPeer::getPrimaryKeyHashFromRow($row, $startcol3);
                if ($key3 !== null) {
                    $obj3 = ListDeptPeer::getInstanceFromPool($key3);
                    if (!$obj3) {

                        $cls = ListDeptPeer::getOMClass();

                    $obj3 = new $cls();
                    $obj3->hydrate($row, $startcol3);
                    ListDeptPeer::addInstanceToPool($obj3, $key3);
                } // if $obj3 already loaded

                // Add the $obj1 (EmpProfile) to the collection in $obj3 (ListDept)
                $obj3->addEmpProfile($obj1);

            } // if joined row is not null

                // Add objects for joined EmpStatusType rows

                $key4 = EmpStatusTypePeer::getPrimaryKeyHashFromRow($row, $startcol4);
                if ($key4 !== null) {
                    $obj4 = EmpStatusTypePeer::getInstanceFromPool($key4);
                    if (!$obj4) {

                        $cls = EmpStatusTypePeer::getOMClass();

                    $obj4 = new $cls();
                    $obj4->hydrate($row, $startcol4);
                    EmpStatusTypePeer::addInstanceToPool($obj4, $key4);
                } // if $obj4 already loaded

                // Add the $obj1 (EmpProfile) to the collection in $obj4 (EmpStatusType)
                $obj4->addEmpProfile($obj1);

            } // if joined row is not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of EmpProfile objects pre-filled with all related objects except EmpStatusType.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of EmpProfile objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAllExceptEmpStatusType(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        // $criteria->getDbName() will return the same object if not set to another value
        // so == check is okay and faster
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(EmpProfilePeer::DATABASE_NAME);
        }

        EmpProfilePeer::addSelectColumns($criteria);
        $startcol2 = EmpProfilePeer::NUM_HYDRATE_COLUMNS;

        EmpAccPeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + EmpAccPeer::NUM_HYDRATE_COLUMNS;

        ListDeptPeer::addSelectColumns($criteria);
        $startcol4 = $startcol3 + ListDeptPeer::NUM_HYDRATE_COLUMNS;

        ListPosPeer::addSelectColumns($criteria);
        $startcol5 = $startcol4 + ListPosPeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(EmpProfilePeer::EMP_ACC_ACC_ID, EmpAccPeer::ID, $join_behavior);

        $criteria->addJoin(EmpProfilePeer::LIST_DEPT_ID, ListDeptPeer::ID, $join_behavior);

        $criteria->addJoin(EmpProfilePeer::LIST_POS_ID, ListPosPeer::ID, $join_behavior);


        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = EmpProfilePeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = EmpProfilePeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = EmpProfilePeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                EmpProfilePeer::addInstanceToPool($obj1, $key1);
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

                // Add the $obj1 (EmpProfile) to the collection in $obj2 (EmpAcc)
                $obj2->addEmpProfile($obj1);

            } // if joined row is not null

                // Add objects for joined ListDept rows

                $key3 = ListDeptPeer::getPrimaryKeyHashFromRow($row, $startcol3);
                if ($key3 !== null) {
                    $obj3 = ListDeptPeer::getInstanceFromPool($key3);
                    if (!$obj3) {

                        $cls = ListDeptPeer::getOMClass();

                    $obj3 = new $cls();
                    $obj3->hydrate($row, $startcol3);
                    ListDeptPeer::addInstanceToPool($obj3, $key3);
                } // if $obj3 already loaded

                // Add the $obj1 (EmpProfile) to the collection in $obj3 (ListDept)
                $obj3->addEmpProfile($obj1);

            } // if joined row is not null

                // Add objects for joined ListPos rows

                $key4 = ListPosPeer::getPrimaryKeyHashFromRow($row, $startcol4);
                if ($key4 !== null) {
                    $obj4 = ListPosPeer::getInstanceFromPool($key4);
                    if (!$obj4) {

                        $cls = ListPosPeer::getOMClass();

                    $obj4 = new $cls();
                    $obj4->hydrate($row, $startcol4);
                    ListPosPeer::addInstanceToPool($obj4, $key4);
                } // if $obj4 already loaded

                // Add the $obj1 (EmpProfile) to the collection in $obj4 (ListPos)
                $obj4->addEmpProfile($obj1);

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
        return Propel::getDatabaseMap(EmpProfilePeer::DATABASE_NAME)->getTable(EmpProfilePeer::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this peer class.
     */
    public static function buildTableMap()
    {
      $dbMap = Propel::getDatabaseMap(BaseEmpProfilePeer::DATABASE_NAME);
      if (!$dbMap->hasTable(BaseEmpProfilePeer::TABLE_NAME)) {
        $dbMap->addTableObject(new \CoreBundle\Model\map\EmpProfileTableMap());
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
        return EmpProfilePeer::OM_CLASS;
    }

    /**
     * Performs an INSERT on the database, given a EmpProfile or Criteria object.
     *
     * @param      mixed $values Criteria or EmpProfile object containing data that is used to create the INSERT statement.
     * @param      PropelPDO $con the PropelPDO connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doInsert($values, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(EmpProfilePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        if ($values instanceof Criteria) {
            $criteria = clone $values; // rename for clarity
        } else {
            $criteria = $values->buildCriteria(); // build Criteria from EmpProfile object
        }

        if ($criteria->containsKey(EmpProfilePeer::ID) && $criteria->keyContainsValue(EmpProfilePeer::ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.EmpProfilePeer::ID.')');
        }


        // Set the correct dbName
        $criteria->setDbName(EmpProfilePeer::DATABASE_NAME);

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
     * Performs an UPDATE on the database, given a EmpProfile or Criteria object.
     *
     * @param      mixed $values Criteria or EmpProfile object containing data that is used to create the UPDATE statement.
     * @param      PropelPDO $con The connection to use (specify PropelPDO connection object to exert more control over transactions).
     * @return int             The number of affected rows (if supported by underlying database driver).
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doUpdate($values, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(EmpProfilePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $selectCriteria = new Criteria(EmpProfilePeer::DATABASE_NAME);

        if ($values instanceof Criteria) {
            $criteria = clone $values; // rename for clarity

            $comparison = $criteria->getComparison(EmpProfilePeer::ID);
            $value = $criteria->remove(EmpProfilePeer::ID);
            if ($value) {
                $selectCriteria->add(EmpProfilePeer::ID, $value, $comparison);
            } else {
                $selectCriteria->setPrimaryTableName(EmpProfilePeer::TABLE_NAME);
            }

        } else { // $values is EmpProfile object
            $criteria = $values->buildCriteria(); // gets full criteria
            $selectCriteria = $values->buildPkeyCriteria(); // gets criteria w/ primary key(s)
        }

        // set the correct dbName
        $criteria->setDbName(EmpProfilePeer::DATABASE_NAME);

        return BasePeer::doUpdate($selectCriteria, $criteria, $con);
    }

    /**
     * Deletes all rows from the emp_profile table.
     *
     * @param      PropelPDO $con the connection to use
     * @return int             The number of affected rows (if supported by underlying database driver).
     * @throws PropelException
     */
    public static function doDeleteAll(PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(EmpProfilePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }
        $affectedRows = 0; // initialize var to track total num of affected rows
        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();
            $affectedRows += BasePeer::doDeleteAll(EmpProfilePeer::TABLE_NAME, $con, EmpProfilePeer::DATABASE_NAME);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            EmpProfilePeer::clearInstancePool();
            EmpProfilePeer::clearRelatedInstancePool();
            $con->commit();

            return $affectedRows;
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Performs a DELETE on the database, given a EmpProfile or Criteria object OR a primary key value.
     *
     * @param      mixed $values Criteria or EmpProfile object or primary key or array of primary keys
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
            $con = Propel::getConnection(EmpProfilePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        if ($values instanceof Criteria) {
            // invalidate the cache for all objects of this type, since we have no
            // way of knowing (without running a query) what objects should be invalidated
            // from the cache based on this Criteria.
            EmpProfilePeer::clearInstancePool();
            // rename for clarity
            $criteria = clone $values;
        } elseif ($values instanceof EmpProfile) { // it's a model object
            // invalidate the cache for this single object
            EmpProfilePeer::removeInstanceFromPool($values);
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(EmpProfilePeer::DATABASE_NAME);
            $criteria->add(EmpProfilePeer::ID, (array) $values, Criteria::IN);
            // invalidate the cache for this object(s)
            foreach ((array) $values as $singleval) {
                EmpProfilePeer::removeInstanceFromPool($singleval);
            }
        }

        // Set the correct dbName
        $criteria->setDbName(EmpProfilePeer::DATABASE_NAME);

        $affectedRows = 0; // initialize var to track total num of affected rows

        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();

            $affectedRows += BasePeer::doDelete($criteria, $con);
            EmpProfilePeer::clearRelatedInstancePool();
            $con->commit();

            return $affectedRows;
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Validates all modified columns of given EmpProfile object.
     * If parameter $columns is either a single column name or an array of column names
     * than only those columns are validated.
     *
     * NOTICE: This does not apply to primary or foreign keys for now.
     *
     * @param EmpProfile $obj The object to validate.
     * @param      mixed $cols Column name or array of column names.
     *
     * @return mixed TRUE if all columns are valid or the error message of the first invalid column.
     */
    public static function doValidate($obj, $cols = null)
    {
        $columns = array();

        if ($cols) {
            $dbMap = Propel::getDatabaseMap(EmpProfilePeer::DATABASE_NAME);
            $tableMap = $dbMap->getTable(EmpProfilePeer::TABLE_NAME);

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

        return BasePeer::doValidate(EmpProfilePeer::DATABASE_NAME, EmpProfilePeer::TABLE_NAME, $columns);
    }

    /**
     * Retrieve a single object by pkey.
     *
     * @param int $pk the primary key.
     * @param      PropelPDO $con the connection to use
     * @return EmpProfile
     */
    public static function retrieveByPK($pk, PropelPDO $con = null)
    {

        if (null !== ($obj = EmpProfilePeer::getInstanceFromPool((string) $pk))) {
            return $obj;
        }

        if ($con === null) {
            $con = Propel::getConnection(EmpProfilePeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria = new Criteria(EmpProfilePeer::DATABASE_NAME);
        $criteria->add(EmpProfilePeer::ID, $pk);

        $v = EmpProfilePeer::doSelect($criteria, $con);

        return !empty($v) > 0 ? $v[0] : null;
    }

    /**
     * Retrieve multiple objects by pkey.
     *
     * @param      array $pks List of primary keys
     * @param      PropelPDO $con the connection to use
     * @return EmpProfile[]
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function retrieveByPKs($pks, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(EmpProfilePeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $objs = null;
        if (empty($pks)) {
            $objs = array();
        } else {
            $criteria = new Criteria(EmpProfilePeer::DATABASE_NAME);
            $criteria->add(EmpProfilePeer::ID, $pks, Criteria::IN);
            $objs = EmpProfilePeer::doSelect($criteria, $con);
        }

        return $objs;
    }

} // BaseEmpProfilePeer

// This is the static code needed to register the TableMap for this table with the main Propel class.
//
BaseEmpProfilePeer::buildTableMap();

