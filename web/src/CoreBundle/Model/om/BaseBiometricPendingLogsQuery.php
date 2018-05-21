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
use CoreBundle\Model\BiometricPendingLogs;
use CoreBundle\Model\BiometricPendingLogsPeer;
use CoreBundle\Model\BiometricPendingLogsQuery;

/**
 * @method BiometricPendingLogsQuery orderByid($order = Criteria::ASC) Order by the id column
 * @method BiometricPendingLogsQuery orderByC_Date($order = Criteria::ASC) Order by the C_Date column
 * @method BiometricPendingLogsQuery orderByC_Time($order = Criteria::ASC) Order by the C_Time column
 * @method BiometricPendingLogsQuery orderByL_TID($order = Criteria::ASC) Order by the L_TID column
 * @method BiometricPendingLogsQuery orderByL_UID($order = Criteria::ASC) Order by the L_UID column
 *
 * @method BiometricPendingLogsQuery groupByid() Group by the id column
 * @method BiometricPendingLogsQuery groupByC_Date() Group by the C_Date column
 * @method BiometricPendingLogsQuery groupByC_Time() Group by the C_Time column
 * @method BiometricPendingLogsQuery groupByL_TID() Group by the L_TID column
 * @method BiometricPendingLogsQuery groupByL_UID() Group by the L_UID column
 *
 * @method BiometricPendingLogsQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method BiometricPendingLogsQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method BiometricPendingLogsQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method BiometricPendingLogs findOne(PropelPDO $con = null) Return the first BiometricPendingLogs matching the query
 * @method BiometricPendingLogs findOneOrCreate(PropelPDO $con = null) Return the first BiometricPendingLogs matching the query, or a new BiometricPendingLogs object populated from the query conditions when no match is found
 *
 * @method BiometricPendingLogs findOneByC_Date(string $C_Date) Return the first BiometricPendingLogs filtered by the C_Date column
 * @method BiometricPendingLogs findOneByC_Time(string $C_Time) Return the first BiometricPendingLogs filtered by the C_Time column
 * @method BiometricPendingLogs findOneByL_TID(int $L_TID) Return the first BiometricPendingLogs filtered by the L_TID column
 * @method BiometricPendingLogs findOneByL_UID(int $L_UID) Return the first BiometricPendingLogs filtered by the L_UID column
 *
 * @method array findByid(int $id) Return BiometricPendingLogs objects filtered by the id column
 * @method array findByC_Date(string $C_Date) Return BiometricPendingLogs objects filtered by the C_Date column
 * @method array findByC_Time(string $C_Time) Return BiometricPendingLogs objects filtered by the C_Time column
 * @method array findByL_TID(int $L_TID) Return BiometricPendingLogs objects filtered by the L_TID column
 * @method array findByL_UID(int $L_UID) Return BiometricPendingLogs objects filtered by the L_UID column
 */
abstract class BaseBiometricPendingLogsQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseBiometricPendingLogsQuery object.
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
            $modelName = 'CoreBundle\\Model\\BiometricPendingLogs';
        }
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new BiometricPendingLogsQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   BiometricPendingLogsQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return BiometricPendingLogsQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof BiometricPendingLogsQuery) {
            return $criteria;
        }
        $query = new BiometricPendingLogsQuery(null, null, $modelAlias);

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
     * @return   BiometricPendingLogs|BiometricPendingLogs[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = BiometricPendingLogsPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(BiometricPendingLogsPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 BiometricPendingLogs A model object, or null if the key is not found
     * @throws PropelException
     */
     public function findOneByid($key, $con = null)
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
     * @return                 BiometricPendingLogs A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id`, `C_Date`, `C_Time`, `L_TID`, `L_UID` FROM `biometric_pending_logs` WHERE `id` = :p0';
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
            $obj = new BiometricPendingLogs();
            $obj->hydrate($row);
            BiometricPendingLogsPeer::addInstanceToPool($obj, (string) $key);
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
     * @return BiometricPendingLogs|BiometricPendingLogs[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|BiometricPendingLogs[]|mixed the list of results, formatted by the current formatter
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
     * @return BiometricPendingLogsQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(BiometricPendingLogsPeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return BiometricPendingLogsQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(BiometricPendingLogsPeer::ID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the id column
     *
     * Example usage:
     * <code>
     * $query->filterByid(1234); // WHERE id = 1234
     * $query->filterByid(array(12, 34)); // WHERE id IN (12, 34)
     * $query->filterByid(array('min' => 12)); // WHERE id >= 12
     * $query->filterByid(array('max' => 12)); // WHERE id <= 12
     * </code>
     *
     * @param     mixed $id The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return BiometricPendingLogsQuery The current query, for fluid interface
     */
    public function filterByid($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(BiometricPendingLogsPeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(BiometricPendingLogsPeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(BiometricPendingLogsPeer::ID, $id, $comparison);
    }

    /**
     * Filter the query on the C_Date column
     *
     * Example usage:
     * <code>
     * $query->filterByC_Date('fooValue');   // WHERE C_Date = 'fooValue'
     * $query->filterByC_Date('%fooValue%'); // WHERE C_Date LIKE '%fooValue%'
     * </code>
     *
     * @param     string $c_Date The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return BiometricPendingLogsQuery The current query, for fluid interface
     */
    public function filterByC_Date($c_Date = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($c_Date)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $c_Date)) {
                $c_Date = str_replace('*', '%', $c_Date);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(BiometricPendingLogsPeer::C_DATE, $c_Date, $comparison);
    }

    /**
     * Filter the query on the C_Time column
     *
     * Example usage:
     * <code>
     * $query->filterByC_Time('fooValue');   // WHERE C_Time = 'fooValue'
     * $query->filterByC_Time('%fooValue%'); // WHERE C_Time LIKE '%fooValue%'
     * </code>
     *
     * @param     string $c_Time The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return BiometricPendingLogsQuery The current query, for fluid interface
     */
    public function filterByC_Time($c_Time = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($c_Time)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $c_Time)) {
                $c_Time = str_replace('*', '%', $c_Time);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(BiometricPendingLogsPeer::C_TIME, $c_Time, $comparison);
    }

    /**
     * Filter the query on the L_TID column
     *
     * Example usage:
     * <code>
     * $query->filterByL_TID(1234); // WHERE L_TID = 1234
     * $query->filterByL_TID(array(12, 34)); // WHERE L_TID IN (12, 34)
     * $query->filterByL_TID(array('min' => 12)); // WHERE L_TID >= 12
     * $query->filterByL_TID(array('max' => 12)); // WHERE L_TID <= 12
     * </code>
     *
     * @param     mixed $l_TID The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return BiometricPendingLogsQuery The current query, for fluid interface
     */
    public function filterByL_TID($l_TID = null, $comparison = null)
    {
        if (is_array($l_TID)) {
            $useMinMax = false;
            if (isset($l_TID['min'])) {
                $this->addUsingAlias(BiometricPendingLogsPeer::L_TID, $l_TID['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($l_TID['max'])) {
                $this->addUsingAlias(BiometricPendingLogsPeer::L_TID, $l_TID['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(BiometricPendingLogsPeer::L_TID, $l_TID, $comparison);
    }

    /**
     * Filter the query on the L_UID column
     *
     * Example usage:
     * <code>
     * $query->filterByL_UID(1234); // WHERE L_UID = 1234
     * $query->filterByL_UID(array(12, 34)); // WHERE L_UID IN (12, 34)
     * $query->filterByL_UID(array('min' => 12)); // WHERE L_UID >= 12
     * $query->filterByL_UID(array('max' => 12)); // WHERE L_UID <= 12
     * </code>
     *
     * @param     mixed $l_UID The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return BiometricPendingLogsQuery The current query, for fluid interface
     */
    public function filterByL_UID($l_UID = null, $comparison = null)
    {
        if (is_array($l_UID)) {
            $useMinMax = false;
            if (isset($l_UID['min'])) {
                $this->addUsingAlias(BiometricPendingLogsPeer::L_UID, $l_UID['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($l_UID['max'])) {
                $this->addUsingAlias(BiometricPendingLogsPeer::L_UID, $l_UID['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(BiometricPendingLogsPeer::L_UID, $l_UID, $comparison);
    }

    /**
     * Exclude object from result
     *
     * @param   BiometricPendingLogs $biometricPendingLogs Object to remove from the list of results
     *
     * @return BiometricPendingLogsQuery The current query, for fluid interface
     */
    public function prune($biometricPendingLogs = null)
    {
        if ($biometricPendingLogs) {
            $this->addUsingAlias(BiometricPendingLogsPeer::ID, $biometricPendingLogs->getid(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

}
