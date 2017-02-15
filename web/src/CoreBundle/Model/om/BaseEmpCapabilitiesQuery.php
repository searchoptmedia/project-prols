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
use CoreBundle\Model\EmpCapabilities;
use CoreBundle\Model\EmpCapabilitiesPeer;
use CoreBundle\Model\EmpCapabilitiesQuery;

/**
 * @method EmpCapabilitiesQuery orderById($order = Criteria::ASC) Order by the id column
 * @method EmpCapabilitiesQuery orderByEmpId($order = Criteria::ASC) Order by the empid column
 * @method EmpCapabilitiesQuery orderByCapId($order = Criteria::ASC) Order by the capid column
 *
 * @method EmpCapabilitiesQuery groupById() Group by the id column
 * @method EmpCapabilitiesQuery groupByEmpId() Group by the empid column
 * @method EmpCapabilitiesQuery groupByCapId() Group by the capid column
 *
 * @method EmpCapabilitiesQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method EmpCapabilitiesQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method EmpCapabilitiesQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method EmpCapabilities findOne(PropelPDO $con = null) Return the first EmpCapabilities matching the query
 * @method EmpCapabilities findOneOrCreate(PropelPDO $con = null) Return the first EmpCapabilities matching the query, or a new EmpCapabilities object populated from the query conditions when no match is found
 *
 * @method EmpCapabilities findOneByEmpId(int $empid) Return the first EmpCapabilities filtered by the empid column
 * @method EmpCapabilities findOneByCapId(int $capid) Return the first EmpCapabilities filtered by the capid column
 *
 * @method array findById(int $id) Return EmpCapabilities objects filtered by the id column
 * @method array findByEmpId(int $empid) Return EmpCapabilities objects filtered by the empid column
 * @method array findByCapId(int $capid) Return EmpCapabilities objects filtered by the capid column
 */
abstract class BaseEmpCapabilitiesQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseEmpCapabilitiesQuery object.
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
            $modelName = 'CoreBundle\\Model\\EmpCapabilities';
        }
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new EmpCapabilitiesQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   EmpCapabilitiesQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return EmpCapabilitiesQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof EmpCapabilitiesQuery) {
            return $criteria;
        }
        $query = new EmpCapabilitiesQuery(null, null, $modelAlias);

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
     * @return   EmpCapabilities|EmpCapabilities[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = EmpCapabilitiesPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(EmpCapabilitiesPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 EmpCapabilities A model object, or null if the key is not found
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
     * @return                 EmpCapabilities A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id`, `empid`, `capid` FROM `emp_capabilities` WHERE `id` = :p0';
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
            $obj = new EmpCapabilities();
            $obj->hydrate($row);
            EmpCapabilitiesPeer::addInstanceToPool($obj, (string) $key);
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
     * @return EmpCapabilities|EmpCapabilities[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|EmpCapabilities[]|mixed the list of results, formatted by the current formatter
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
     * @return EmpCapabilitiesQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(EmpCapabilitiesPeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return EmpCapabilitiesQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(EmpCapabilitiesPeer::ID, $keys, Criteria::IN);
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
     * @return EmpCapabilitiesQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(EmpCapabilitiesPeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(EmpCapabilitiesPeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EmpCapabilitiesPeer::ID, $id, $comparison);
    }

    /**
     * Filter the query on the empid column
     *
     * Example usage:
     * <code>
     * $query->filterByEmpId(1234); // WHERE empid = 1234
     * $query->filterByEmpId(array(12, 34)); // WHERE empid IN (12, 34)
     * $query->filterByEmpId(array('min' => 12)); // WHERE empid >= 12
     * $query->filterByEmpId(array('max' => 12)); // WHERE empid <= 12
     * </code>
     *
     * @param     mixed $empId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return EmpCapabilitiesQuery The current query, for fluid interface
     */
    public function filterByEmpId($empId = null, $comparison = null)
    {
        if (is_array($empId)) {
            $useMinMax = false;
            if (isset($empId['min'])) {
                $this->addUsingAlias(EmpCapabilitiesPeer::EMPID, $empId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($empId['max'])) {
                $this->addUsingAlias(EmpCapabilitiesPeer::EMPID, $empId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EmpCapabilitiesPeer::EMPID, $empId, $comparison);
    }

    /**
     * Filter the query on the capid column
     *
     * Example usage:
     * <code>
     * $query->filterByCapId(1234); // WHERE capid = 1234
     * $query->filterByCapId(array(12, 34)); // WHERE capid IN (12, 34)
     * $query->filterByCapId(array('min' => 12)); // WHERE capid >= 12
     * $query->filterByCapId(array('max' => 12)); // WHERE capid <= 12
     * </code>
     *
     * @param     mixed $capId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return EmpCapabilitiesQuery The current query, for fluid interface
     */
    public function filterByCapId($capId = null, $comparison = null)
    {
        if (is_array($capId)) {
            $useMinMax = false;
            if (isset($capId['min'])) {
                $this->addUsingAlias(EmpCapabilitiesPeer::CAPID, $capId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($capId['max'])) {
                $this->addUsingAlias(EmpCapabilitiesPeer::CAPID, $capId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EmpCapabilitiesPeer::CAPID, $capId, $comparison);
    }

    /**
     * Exclude object from result
     *
     * @param   EmpCapabilities $empCapabilities Object to remove from the list of results
     *
     * @return EmpCapabilitiesQuery The current query, for fluid interface
     */
    public function prune($empCapabilities = null)
    {
        if ($empCapabilities) {
            $this->addUsingAlias(EmpCapabilitiesPeer::ID, $empCapabilities->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

}