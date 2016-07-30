<?php

namespace CoreBundle\Model\om;

use \Criteria;
use \Exception;
use \ModelCriteria;
use \PDO;
use \Propel;
use \PropelException;
use \PropelObjectCollection;
use \PropelPDO;
use CoreBundle\Model\prols_emp_time;
use CoreBundle\Model\prols_emp_timePeer;
use CoreBundle\Model\prols_emp_timeQuery;

/**
 * @method prols_emp_timeQuery orderById($order = Criteria::ASC) Order by the id column
 * @method prols_emp_timeQuery orderByTimeIn1($order = Criteria::ASC) Order by the time_in1 column
 * @method prols_emp_timeQuery orderByTimeOut1($order = Criteria::ASC) Order by the time_out1 column
 * @method prols_emp_timeQuery orderByTimeIn2($order = Criteria::ASC) Order by the time_in2 column
 * @method prols_emp_timeQuery orderByTimeOut2($order = Criteria::ASC) Order by the time_out2 column
 * @method prols_emp_timeQuery orderByTimeInOt($order = Criteria::ASC) Order by the time_in_ot column
 * @method prols_emp_timeQuery orderByTimeOutOt($order = Criteria::ASC) Order by the time_out_ot column
 * @method prols_emp_timeQuery orderByIpAdd($order = Criteria::ASC) Order by the ip_add column
 * @method prols_emp_timeQuery orderByDate($order = Criteria::ASC) Order by the date column
 * @method prols_emp_timeQuery orderByAccId($order = Criteria::ASC) Order by the acc_id column
 *
 * @method prols_emp_timeQuery groupById() Group by the id column
 * @method prols_emp_timeQuery groupByTimeIn1() Group by the time_in1 column
 * @method prols_emp_timeQuery groupByTimeOut1() Group by the time_out1 column
 * @method prols_emp_timeQuery groupByTimeIn2() Group by the time_in2 column
 * @method prols_emp_timeQuery groupByTimeOut2() Group by the time_out2 column
 * @method prols_emp_timeQuery groupByTimeInOt() Group by the time_in_ot column
 * @method prols_emp_timeQuery groupByTimeOutOt() Group by the time_out_ot column
 * @method prols_emp_timeQuery groupByIpAdd() Group by the ip_add column
 * @method prols_emp_timeQuery groupByDate() Group by the date column
 * @method prols_emp_timeQuery groupByAccId() Group by the acc_id column
 *
 * @method prols_emp_timeQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method prols_emp_timeQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method prols_emp_timeQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method prols_emp_time findOne(PropelPDO $con = null) Return the first prols_emp_time matching the query
 * @method prols_emp_time findOneOrCreate(PropelPDO $con = null) Return the first prols_emp_time matching the query, or a new prols_emp_time object populated from the query conditions when no match is found
 *
 * @method prols_emp_time findOneByTimeIn1(string $time_in1) Return the first prols_emp_time filtered by the time_in1 column
 * @method prols_emp_time findOneByTimeOut1(string $time_out1) Return the first prols_emp_time filtered by the time_out1 column
 * @method prols_emp_time findOneByTimeIn2(string $time_in2) Return the first prols_emp_time filtered by the time_in2 column
 * @method prols_emp_time findOneByTimeOut2(string $time_out2) Return the first prols_emp_time filtered by the time_out2 column
 * @method prols_emp_time findOneByTimeInOt(string $time_in_ot) Return the first prols_emp_time filtered by the time_in_ot column
 * @method prols_emp_time findOneByTimeOutOt(string $time_out_ot) Return the first prols_emp_time filtered by the time_out_ot column
 * @method prols_emp_time findOneByIpAdd(string $ip_add) Return the first prols_emp_time filtered by the ip_add column
 * @method prols_emp_time findOneByDate(string $date) Return the first prols_emp_time filtered by the date column
 * @method prols_emp_time findOneByAccId(int $acc_id) Return the first prols_emp_time filtered by the acc_id column
 *
 * @method array findById(int $id) Return prols_emp_time objects filtered by the id column
 * @method array findByTimeIn1(string $time_in1) Return prols_emp_time objects filtered by the time_in1 column
 * @method array findByTimeOut1(string $time_out1) Return prols_emp_time objects filtered by the time_out1 column
 * @method array findByTimeIn2(string $time_in2) Return prols_emp_time objects filtered by the time_in2 column
 * @method array findByTimeOut2(string $time_out2) Return prols_emp_time objects filtered by the time_out2 column
 * @method array findByTimeInOt(string $time_in_ot) Return prols_emp_time objects filtered by the time_in_ot column
 * @method array findByTimeOutOt(string $time_out_ot) Return prols_emp_time objects filtered by the time_out_ot column
 * @method array findByIpAdd(string $ip_add) Return prols_emp_time objects filtered by the ip_add column
 * @method array findByDate(string $date) Return prols_emp_time objects filtered by the date column
 * @method array findByAccId(int $acc_id) Return prols_emp_time objects filtered by the acc_id column
 */
abstract class Baseprols_emp_timeQuery extends ModelCriteria
{
    /**
     * Initializes internal state of Baseprols_emp_timeQuery object.
     *
     * @param     string $dbName The dabase name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = null, $modelName = null, $modelAlias = null)
    {
        if (null === $dbName) {
            $dbName = 'prols';
        }
        if (null === $modelName) {
            $modelName = 'CoreBundle\\Model\\prols_emp_time';
        }
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new prols_emp_timeQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   prols_emp_timeQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return prols_emp_timeQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof prols_emp_timeQuery) {
            return $criteria;
        }
        $query = new prols_emp_timeQuery(null, null, $modelAlias);

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
     * @return   prols_emp_time|prols_emp_time[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = prols_emp_timePeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(prols_emp_timePeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 prols_emp_time A model object, or null if the key is not found
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
     * @return                 prols_emp_time A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id`, `time_in1`, `time_out1`, `time_in2`, `time_out2`, `time_in_ot`, `time_out_ot`, `ip_add`, `date`, `acc_id` FROM `emp_time` WHERE `id` = :p0';
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
            $obj = new prols_emp_time();
            $obj->hydrate($row);
            prols_emp_timePeer::addInstanceToPool($obj, (string) $key);
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
     * @return prols_emp_time|prols_emp_time[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|prols_emp_time[]|mixed the list of results, formatted by the current formatter
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
     * @return prols_emp_timeQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(prols_emp_timePeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return prols_emp_timeQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(prols_emp_timePeer::ID, $keys, Criteria::IN);
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
     * @return prols_emp_timeQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(prols_emp_timePeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(prols_emp_timePeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(prols_emp_timePeer::ID, $id, $comparison);
    }

    /**
     * Filter the query on the time_in1 column
     *
     * Example usage:
     * <code>
     * $query->filterByTimeIn1('fooValue');   // WHERE time_in1 = 'fooValue'
     * $query->filterByTimeIn1('%fooValue%'); // WHERE time_in1 LIKE '%fooValue%'
     * </code>
     *
     * @param     string $timeIn1 The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return prols_emp_timeQuery The current query, for fluid interface
     */
    public function filterByTimeIn1($timeIn1 = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($timeIn1)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $timeIn1)) {
                $timeIn1 = str_replace('*', '%', $timeIn1);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(prols_emp_timePeer::TIME_IN1, $timeIn1, $comparison);
    }

    /**
     * Filter the query on the time_out1 column
     *
     * Example usage:
     * <code>
     * $query->filterByTimeOut1('fooValue');   // WHERE time_out1 = 'fooValue'
     * $query->filterByTimeOut1('%fooValue%'); // WHERE time_out1 LIKE '%fooValue%'
     * </code>
     *
     * @param     string $timeOut1 The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return prols_emp_timeQuery The current query, for fluid interface
     */
    public function filterByTimeOut1($timeOut1 = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($timeOut1)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $timeOut1)) {
                $timeOut1 = str_replace('*', '%', $timeOut1);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(prols_emp_timePeer::TIME_OUT1, $timeOut1, $comparison);
    }

    /**
     * Filter the query on the time_in2 column
     *
     * Example usage:
     * <code>
     * $query->filterByTimeIn2('fooValue');   // WHERE time_in2 = 'fooValue'
     * $query->filterByTimeIn2('%fooValue%'); // WHERE time_in2 LIKE '%fooValue%'
     * </code>
     *
     * @param     string $timeIn2 The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return prols_emp_timeQuery The current query, for fluid interface
     */
    public function filterByTimeIn2($timeIn2 = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($timeIn2)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $timeIn2)) {
                $timeIn2 = str_replace('*', '%', $timeIn2);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(prols_emp_timePeer::TIME_IN2, $timeIn2, $comparison);
    }

    /**
     * Filter the query on the time_out2 column
     *
     * Example usage:
     * <code>
     * $query->filterByTimeOut2('fooValue');   // WHERE time_out2 = 'fooValue'
     * $query->filterByTimeOut2('%fooValue%'); // WHERE time_out2 LIKE '%fooValue%'
     * </code>
     *
     * @param     string $timeOut2 The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return prols_emp_timeQuery The current query, for fluid interface
     */
    public function filterByTimeOut2($timeOut2 = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($timeOut2)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $timeOut2)) {
                $timeOut2 = str_replace('*', '%', $timeOut2);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(prols_emp_timePeer::TIME_OUT2, $timeOut2, $comparison);
    }

    /**
     * Filter the query on the time_in_ot column
     *
     * Example usage:
     * <code>
     * $query->filterByTimeInOt('fooValue');   // WHERE time_in_ot = 'fooValue'
     * $query->filterByTimeInOt('%fooValue%'); // WHERE time_in_ot LIKE '%fooValue%'
     * </code>
     *
     * @param     string $timeInOt The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return prols_emp_timeQuery The current query, for fluid interface
     */
    public function filterByTimeInOt($timeInOt = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($timeInOt)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $timeInOt)) {
                $timeInOt = str_replace('*', '%', $timeInOt);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(prols_emp_timePeer::TIME_IN_OT, $timeInOt, $comparison);
    }

    /**
     * Filter the query on the time_out_ot column
     *
     * Example usage:
     * <code>
     * $query->filterByTimeOutOt('fooValue');   // WHERE time_out_ot = 'fooValue'
     * $query->filterByTimeOutOt('%fooValue%'); // WHERE time_out_ot LIKE '%fooValue%'
     * </code>
     *
     * @param     string $timeOutOt The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return prols_emp_timeQuery The current query, for fluid interface
     */
    public function filterByTimeOutOt($timeOutOt = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($timeOutOt)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $timeOutOt)) {
                $timeOutOt = str_replace('*', '%', $timeOutOt);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(prols_emp_timePeer::TIME_OUT_OT, $timeOutOt, $comparison);
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
     * @return prols_emp_timeQuery The current query, for fluid interface
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

        return $this->addUsingAlias(prols_emp_timePeer::IP_ADD, $ipAdd, $comparison);
    }

    /**
     * Filter the query on the date column
     *
     * Example usage:
     * <code>
     * $query->filterByDate('fooValue');   // WHERE date = 'fooValue'
     * $query->filterByDate('%fooValue%'); // WHERE date LIKE '%fooValue%'
     * </code>
     *
     * @param     string $date The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return prols_emp_timeQuery The current query, for fluid interface
     */
    public function filterByDate($date = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($date)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $date)) {
                $date = str_replace('*', '%', $date);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(prols_emp_timePeer::DATE, $date, $comparison);
    }

    /**
     * Filter the query on the acc_id column
     *
     * Example usage:
     * <code>
     * $query->filterByAccId(1234); // WHERE acc_id = 1234
     * $query->filterByAccId(array(12, 34)); // WHERE acc_id IN (12, 34)
     * $query->filterByAccId(array('min' => 12)); // WHERE acc_id >= 12
     * $query->filterByAccId(array('max' => 12)); // WHERE acc_id <= 12
     * </code>
     *
     * @param     mixed $accId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return prols_emp_timeQuery The current query, for fluid interface
     */
    public function filterByAccId($accId = null, $comparison = null)
    {
        if (is_array($accId)) {
            $useMinMax = false;
            if (isset($accId['min'])) {
                $this->addUsingAlias(prols_emp_timePeer::ACC_ID, $accId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($accId['max'])) {
                $this->addUsingAlias(prols_emp_timePeer::ACC_ID, $accId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(prols_emp_timePeer::ACC_ID, $accId, $comparison);
    }

    /**
     * Exclude object from result
     *
     * @param   prols_emp_time $prols_emp_time Object to remove from the list of results
     *
     * @return prols_emp_timeQuery The current query, for fluid interface
     */
    public function prune($prols_emp_time = null)
    {
        if ($prols_emp_time) {
            $this->addUsingAlias(prols_emp_timePeer::ID, $prols_emp_time->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

}
