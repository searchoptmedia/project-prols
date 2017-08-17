<?php

namespace CoreBundle\Model\om;

use \Criteria;
use \Exception;
use \ModelCriteria;
use \ModelJoin;
use \PDO;
use \Propel;
use \PropelCollection;
use \PropelException;
use \PropelObjectCollection;
use \PropelPDO;
use CoreBundle\Model\EmpAcc;
use CoreBundle\Model\EmpTime;
use CoreBundle\Model\EmpTimePeer;
use CoreBundle\Model\EmpTimeQuery;

/**
 * @method EmpTimeQuery orderById($order = Criteria::ASC) Order by the id column
 * @method EmpTimeQuery orderByTimeIn($order = Criteria::ASC) Order by the time_in column
 * @method EmpTimeQuery orderByTimeOut($order = Criteria::ASC) Order by the time_out column
 * @method EmpTimeQuery orderByIpAdd($order = Criteria::ASC) Order by the ip_add column
 * @method EmpTimeQuery orderByDate($order = Criteria::ASC) Order by the date column
 * @method EmpTimeQuery orderByEmpAccAccId($order = Criteria::ASC) Order by the emp_acc_acc_id column
 * @method EmpTimeQuery orderByManhours($order = Criteria::ASC) Order by the manhours column
 * @method EmpTimeQuery orderByOvertime($order = Criteria::ASC) Order by the overtime column
 * @method EmpTimeQuery orderByCheckIp($order = Criteria::ASC) Order by the check_ip column
 * @method EmpTimeQuery orderByStatus($order = Criteria::ASC) Order by the status column
 *
 * @method EmpTimeQuery groupById() Group by the id column
 * @method EmpTimeQuery groupByTimeIn() Group by the time_in column
 * @method EmpTimeQuery groupByTimeOut() Group by the time_out column
 * @method EmpTimeQuery groupByIpAdd() Group by the ip_add column
 * @method EmpTimeQuery groupByDate() Group by the date column
 * @method EmpTimeQuery groupByEmpAccAccId() Group by the emp_acc_acc_id column
 * @method EmpTimeQuery groupByManhours() Group by the manhours column
 * @method EmpTimeQuery groupByOvertime() Group by the overtime column
 * @method EmpTimeQuery groupByCheckIp() Group by the check_ip column
 * @method EmpTimeQuery groupByStatus() Group by the status column
 *
 * @method EmpTimeQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method EmpTimeQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method EmpTimeQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method EmpTimeQuery leftJoinEmpAcc($relationAlias = null) Adds a LEFT JOIN clause to the query using the EmpAcc relation
 * @method EmpTimeQuery rightJoinEmpAcc($relationAlias = null) Adds a RIGHT JOIN clause to the query using the EmpAcc relation
 * @method EmpTimeQuery innerJoinEmpAcc($relationAlias = null) Adds a INNER JOIN clause to the query using the EmpAcc relation
 *
 * @method EmpTime findOne(PropelPDO $con = null) Return the first EmpTime matching the query
 * @method EmpTime findOneOrCreate(PropelPDO $con = null) Return the first EmpTime matching the query, or a new EmpTime object populated from the query conditions when no match is found
 *
 * @method EmpTime findOneByTimeIn(string $time_in) Return the first EmpTime filtered by the time_in column
 * @method EmpTime findOneByTimeOut(string $time_out) Return the first EmpTime filtered by the time_out column
 * @method EmpTime findOneByIpAdd(string $ip_add) Return the first EmpTime filtered by the ip_add column
 * @method EmpTime findOneByDate(string $date) Return the first EmpTime filtered by the date column
 * @method EmpTime findOneByEmpAccAccId(int $emp_acc_acc_id) Return the first EmpTime filtered by the emp_acc_acc_id column
 * @method EmpTime findOneByManhours(double $manhours) Return the first EmpTime filtered by the manhours column
 * @method EmpTime findOneByOvertime(double $overtime) Return the first EmpTime filtered by the overtime column
 * @method EmpTime findOneByCheckIp(int $check_ip) Return the first EmpTime filtered by the check_ip column
 * @method EmpTime findOneByStatus(int $status) Return the first EmpTime filtered by the status column
 *
 * @method array findById(int $id) Return EmpTime objects filtered by the id column
 * @method array findByTimeIn(string $time_in) Return EmpTime objects filtered by the time_in column
 * @method array findByTimeOut(string $time_out) Return EmpTime objects filtered by the time_out column
 * @method array findByIpAdd(string $ip_add) Return EmpTime objects filtered by the ip_add column
 * @method array findByDate(string $date) Return EmpTime objects filtered by the date column
 * @method array findByEmpAccAccId(int $emp_acc_acc_id) Return EmpTime objects filtered by the emp_acc_acc_id column
 * @method array findByManhours(double $manhours) Return EmpTime objects filtered by the manhours column
 * @method array findByOvertime(double $overtime) Return EmpTime objects filtered by the overtime column
 * @method array findByCheckIp(int $check_ip) Return EmpTime objects filtered by the check_ip column
 * @method array findByStatus(int $status) Return EmpTime objects filtered by the status column
 */
abstract class BaseEmpTimeQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseEmpTimeQuery object.
     *
     * @param     string $dbName The dabase name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = null, $modelName = null, $modelAlias = null)
    {
        if (null === $dbName) {
            $dbName = 'default';
        }
        if (null === $modelName) {
            $modelName = 'CoreBundle\\Model\\EmpTime';
        }
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new EmpTimeQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   EmpTimeQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return EmpTimeQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof EmpTimeQuery) {
            return $criteria;
        }
        $query = new EmpTimeQuery(null, null, $modelAlias);

        if ($criteria instanceof Criteria) {
            $query->mergeWith($criteria);
        }

        return $query;
    }

    /**
     * Find object by primary key.
     * Propel uses the instance pool to skip the database if the object exists.
     * Go fast if the query is untouched.
     *
     * <code>
     * $obj  = $c->findPk(12, $con);
     * </code>
     *
     * @param mixed $key Primary key to use for the query 
     * @param     PropelPDO $con an optional connection object
     *
     * @return   EmpTime|EmpTime[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = EmpTimePeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(EmpTimePeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }
        $this->basePreSelect($con);
        if ($this->formatter || $this->modelAlias || $this->with || $this->select
         || $this->selectColumns || $this->asColumns || $this->selectModifiers
         || $this->map || $this->having || $this->joins) {
            return $this->findPkComplex($key, $con);
        } else {
            return $this->findPkSimple($key, $con);
        }
    }

    /**
     * Alias of findPk to use instance pooling
     *
     * @param     mixed $key Primary key to use for the query
     * @param     PropelPDO $con A connection object
     *
     * @return                 EmpTime A model object, or null if the key is not found
     * @throws PropelException
     */
     public function findOneById($key, $con = null)
     {
        return $this->findPk($key, $con);
     }

    /**
     * Find object by primary key using raw SQL to go fast.
     * Bypass doSelect() and the object formatter by using generated code.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     PropelPDO $con A connection object
     *
     * @return                 EmpTime A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id`, `time_in`, `time_out`, `ip_add`, `date`, `emp_acc_acc_id`, `manhours`, `overtime`, `check_ip`, `status` FROM `emp_time` WHERE `id` = :p0';
        try {
            $stmt = $con->prepare($sql);			
            $stmt->bindValue(':p0', $key, PDO::PARAM_INT);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $obj = new EmpTime();
            $obj->hydrate($row);
            EmpTimePeer::addInstanceToPool($obj, (string) $key);
        }
        $stmt->closeCursor();

        return $obj;
    }

    /**
     * Find object by primary key.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     PropelPDO $con A connection object
     *
     * @return EmpTime|EmpTime[]|mixed the result, formatted by the current formatter
     */
    protected function findPkComplex($key, $con)
    {
        // As the query uses a PK condition, no limit(1) is necessary.
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $stmt = $criteria
            ->filterByPrimaryKey($key)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->formatOne($stmt);
    }

    /**
     * Find objects by primary key
     * <code>
     * $objs = $c->findPks(array(12, 56, 832), $con);
     * </code>
     * @param     array $keys Primary keys to use for the query
     * @param     PropelPDO $con an optional connection object
     *
     * @return PropelObjectCollection|EmpTime[]|mixed the list of results, formatted by the current formatter
     */
    public function findPks($keys, $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection($this->getDbName(), Propel::CONNECTION_READ);
        }
        $this->basePreSelect($con);
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $stmt = $criteria
            ->filterByPrimaryKeys($keys)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->format($stmt);
    }

    /**
     * Filter the query by primary key
     *
     * @param     mixed $key Primary key to use for the query
     *
     * @return EmpTimeQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(EmpTimePeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return EmpTimeQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(EmpTimePeer::ID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the id column
     *
     * Example usage:
     * <code>
     * $query->filterById(1234); // WHERE id = 1234
     * $query->filterById(array(12, 34)); // WHERE id IN (12, 34)
     * $query->filterById(array('min' => 12)); // WHERE id >= 12
     * $query->filterById(array('max' => 12)); // WHERE id <= 12
     * </code>
     *
     * @param     mixed $id The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return EmpTimeQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(EmpTimePeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(EmpTimePeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EmpTimePeer::ID, $id, $comparison);
    }

    /**
     * Filter the query on the time_in column
     *
     * Example usage:
     * <code>
     * $query->filterByTimeIn('2011-03-14'); // WHERE time_in = '2011-03-14'
     * $query->filterByTimeIn('now'); // WHERE time_in = '2011-03-14'
     * $query->filterByTimeIn(array('max' => 'yesterday')); // WHERE time_in < '2011-03-13'
     * </code>
     *
     * @param     mixed $timeIn The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return EmpTimeQuery The current query, for fluid interface
     */
    public function filterByTimeIn($timeIn = null, $comparison = null)
    {
        if (is_array($timeIn)) {
            $useMinMax = false;
            if (isset($timeIn['min'])) {
                $this->addUsingAlias(EmpTimePeer::TIME_IN, $timeIn['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($timeIn['max'])) {
                $this->addUsingAlias(EmpTimePeer::TIME_IN, $timeIn['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EmpTimePeer::TIME_IN, $timeIn, $comparison);
    }

    /**
     * Filter the query on the time_out column
     *
     * Example usage:
     * <code>
     * $query->filterByTimeOut('2011-03-14'); // WHERE time_out = '2011-03-14'
     * $query->filterByTimeOut('now'); // WHERE time_out = '2011-03-14'
     * $query->filterByTimeOut(array('max' => 'yesterday')); // WHERE time_out < '2011-03-13'
     * </code>
     *
     * @param     mixed $timeOut The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return EmpTimeQuery The current query, for fluid interface
     */
    public function filterByTimeOut($timeOut = null, $comparison = null)
    {
        if (is_array($timeOut)) {
            $useMinMax = false;
            if (isset($timeOut['min'])) {
                $this->addUsingAlias(EmpTimePeer::TIME_OUT, $timeOut['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($timeOut['max'])) {
                $this->addUsingAlias(EmpTimePeer::TIME_OUT, $timeOut['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EmpTimePeer::TIME_OUT, $timeOut, $comparison);
    }

    /**
     * Filter the query on the ip_add column
     *
     * Example usage:
     * <code>
     * $query->filterByIpAdd('fooValue');   // WHERE ip_add = 'fooValue'
     * $query->filterByIpAdd('%fooValue%'); // WHERE ip_add LIKE '%fooValue%'
     * </code>
     *
     * @param     string $ipAdd The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return EmpTimeQuery The current query, for fluid interface
     */
    public function filterByIpAdd($ipAdd = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($ipAdd)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $ipAdd)) {
                $ipAdd = str_replace('*', '%', $ipAdd);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(EmpTimePeer::IP_ADD, $ipAdd, $comparison);
    }

    /**
     * Filter the query on the date column
     *
     * Example usage:
     * <code>
     * $query->filterByDate('2011-03-14'); // WHERE date = '2011-03-14'
     * $query->filterByDate('now'); // WHERE date = '2011-03-14'
     * $query->filterByDate(array('max' => 'yesterday')); // WHERE date < '2011-03-13'
     * </code>
     *
     * @param     mixed $date The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return EmpTimeQuery The current query, for fluid interface
     */
    public function filterByDate($date = null, $comparison = null)
    {
        if (is_array($date)) {
            $useMinMax = false;
            if (isset($date['min'])) {
                $this->addUsingAlias(EmpTimePeer::DATE, $date['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($date['max'])) {
                $this->addUsingAlias(EmpTimePeer::DATE, $date['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EmpTimePeer::DATE, $date, $comparison);
    }

    /**
     * Filter the query on the emp_acc_acc_id column
     *
     * Example usage:
     * <code>
     * $query->filterByEmpAccAccId(1234); // WHERE emp_acc_acc_id = 1234
     * $query->filterByEmpAccAccId(array(12, 34)); // WHERE emp_acc_acc_id IN (12, 34)
     * $query->filterByEmpAccAccId(array('min' => 12)); // WHERE emp_acc_acc_id >= 12
     * $query->filterByEmpAccAccId(array('max' => 12)); // WHERE emp_acc_acc_id <= 12
     * </code>
     *
     * @see       filterByEmpAcc()
     *
     * @param     mixed $empAccAccId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return EmpTimeQuery The current query, for fluid interface
     */
    public function filterByEmpAccAccId($empAccAccId = null, $comparison = null)
    {
        if (is_array($empAccAccId)) {
            $useMinMax = false;
            if (isset($empAccAccId['min'])) {
                $this->addUsingAlias(EmpTimePeer::EMP_ACC_ACC_ID, $empAccAccId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($empAccAccId['max'])) {
                $this->addUsingAlias(EmpTimePeer::EMP_ACC_ACC_ID, $empAccAccId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EmpTimePeer::EMP_ACC_ACC_ID, $empAccAccId, $comparison);
    }

    /**
     * Filter the query on the manhours column
     *
     * Example usage:
     * <code>
     * $query->filterByManhours(1234); // WHERE manhours = 1234
     * $query->filterByManhours(array(12, 34)); // WHERE manhours IN (12, 34)
     * $query->filterByManhours(array('min' => 12)); // WHERE manhours >= 12
     * $query->filterByManhours(array('max' => 12)); // WHERE manhours <= 12
     * </code>
     *
     * @param     mixed $manhours The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return EmpTimeQuery The current query, for fluid interface
     */
    public function filterByManhours($manhours = null, $comparison = null)
    {
        if (is_array($manhours)) {
            $useMinMax = false;
            if (isset($manhours['min'])) {
                $this->addUsingAlias(EmpTimePeer::MANHOURS, $manhours['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($manhours['max'])) {
                $this->addUsingAlias(EmpTimePeer::MANHOURS, $manhours['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EmpTimePeer::MANHOURS, $manhours, $comparison);
    }

    /**
     * Filter the query on the overtime column
     *
     * Example usage:
     * <code>
     * $query->filterByOvertime(1234); // WHERE overtime = 1234
     * $query->filterByOvertime(array(12, 34)); // WHERE overtime IN (12, 34)
     * $query->filterByOvertime(array('min' => 12)); // WHERE overtime >= 12
     * $query->filterByOvertime(array('max' => 12)); // WHERE overtime <= 12
     * </code>
     *
     * @param     mixed $overtime The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return EmpTimeQuery The current query, for fluid interface
     */
    public function filterByOvertime($overtime = null, $comparison = null)
    {
        if (is_array($overtime)) {
            $useMinMax = false;
            if (isset($overtime['min'])) {
                $this->addUsingAlias(EmpTimePeer::OVERTIME, $overtime['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($overtime['max'])) {
                $this->addUsingAlias(EmpTimePeer::OVERTIME, $overtime['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EmpTimePeer::OVERTIME, $overtime, $comparison);
    }

    /**
     * Filter the query on the check_ip column
     *
     * Example usage:
     * <code>
     * $query->filterByCheckIp(1234); // WHERE check_ip = 1234
     * $query->filterByCheckIp(array(12, 34)); // WHERE check_ip IN (12, 34)
     * $query->filterByCheckIp(array('min' => 12)); // WHERE check_ip >= 12
     * $query->filterByCheckIp(array('max' => 12)); // WHERE check_ip <= 12
     * </code>
     *
     * @param     mixed $checkIp The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return EmpTimeQuery The current query, for fluid interface
     */
    public function filterByCheckIp($checkIp = null, $comparison = null)
    {
        if (is_array($checkIp)) {
            $useMinMax = false;
            if (isset($checkIp['min'])) {
                $this->addUsingAlias(EmpTimePeer::CHECK_IP, $checkIp['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($checkIp['max'])) {
                $this->addUsingAlias(EmpTimePeer::CHECK_IP, $checkIp['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EmpTimePeer::CHECK_IP, $checkIp, $comparison);
    }

    /**
     * Filter the query on the status column
     *
     * Example usage:
     * <code>
     * $query->filterByStatus(1234); // WHERE status = 1234
     * $query->filterByStatus(array(12, 34)); // WHERE status IN (12, 34)
     * $query->filterByStatus(array('min' => 12)); // WHERE status >= 12
     * $query->filterByStatus(array('max' => 12)); // WHERE status <= 12
     * </code>
     *
     * @param     mixed $status The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return EmpTimeQuery The current query, for fluid interface
     */
    public function filterByStatus($status = null, $comparison = null)
    {
        if (is_array($status)) {
            $useMinMax = false;
            if (isset($status['min'])) {
                $this->addUsingAlias(EmpTimePeer::STATUS, $status['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($status['max'])) {
                $this->addUsingAlias(EmpTimePeer::STATUS, $status['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EmpTimePeer::STATUS, $status, $comparison);
    }

    /**
     * Filter the query by a related EmpAcc object
     *
     * @param   EmpAcc|PropelObjectCollection $empAcc The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 EmpTimeQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByEmpAcc($empAcc, $comparison = null)
    {
        if ($empAcc instanceof EmpAcc) {
            return $this
                ->addUsingAlias(EmpTimePeer::EMP_ACC_ACC_ID, $empAcc->getId(), $comparison);
        } elseif ($empAcc instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(EmpTimePeer::EMP_ACC_ACC_ID, $empAcc->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByEmpAcc() only accepts arguments of type EmpAcc or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the EmpAcc relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return EmpTimeQuery The current query, for fluid interface
     */
    public function joinEmpAcc($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('EmpAcc');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'EmpAcc');
        }

        return $this;
    }

    /**
     * Use the EmpAcc relation EmpAcc object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \CoreBundle\Model\EmpAccQuery A secondary query class using the current class as primary query
     */
    public function useEmpAccQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinEmpAcc($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'EmpAcc', '\CoreBundle\Model\EmpAccQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   EmpTime $empTime Object to remove from the list of results
     *
     * @return EmpTimeQuery The current query, for fluid interface
     */
    public function prune($empTime = null)
    {
        if ($empTime) {
            $this->addUsingAlias(EmpTimePeer::ID, $empTime->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

}
