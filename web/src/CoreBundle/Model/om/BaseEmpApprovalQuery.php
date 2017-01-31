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
use CoreBundle\Model\EmpApproval;
use CoreBundle\Model\EmpApprovalPeer;
use CoreBundle\Model\EmpApprovalQuery;
use CoreBundle\Model\EmpTime;

/**
 * @method EmpApprovalQuery orderById($order = Criteria::ASC) Order by the id column
 * @method EmpApprovalQuery orderByRequest($order = Criteria::ASC) Order by the request column
 * @method EmpApprovalQuery orderByStatus($order = Criteria::ASC) Order by the status column
 * @method EmpApprovalQuery orderByDate($order = Criteria::ASC) Order by the date column
 * @method EmpApprovalQuery orderByIpAdd($order = Criteria::ASC) Order by the ip_add column
 * @method EmpApprovalQuery orderByEmpTimeId($order = Criteria::ASC) Order by the emp_time_id column
 *
 * @method EmpApprovalQuery groupById() Group by the id column
 * @method EmpApprovalQuery groupByRequest() Group by the request column
 * @method EmpApprovalQuery groupByStatus() Group by the status column
 * @method EmpApprovalQuery groupByDate() Group by the date column
 * @method EmpApprovalQuery groupByIpAdd() Group by the ip_add column
 * @method EmpApprovalQuery groupByEmpTimeId() Group by the emp_time_id column
 *
 * @method EmpApprovalQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method EmpApprovalQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method EmpApprovalQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method EmpApprovalQuery leftJoinEmpTime($relationAlias = null) Adds a LEFT JOIN clause to the query using the EmpTime relation
 * @method EmpApprovalQuery rightJoinEmpTime($relationAlias = null) Adds a RIGHT JOIN clause to the query using the EmpTime relation
 * @method EmpApprovalQuery innerJoinEmpTime($relationAlias = null) Adds a INNER JOIN clause to the query using the EmpTime relation
 *
 * @method EmpApproval findOne(PropelPDO $con = null) Return the first EmpApproval matching the query
 * @method EmpApproval findOneOrCreate(PropelPDO $con = null) Return the first EmpApproval matching the query, or a new EmpApproval object populated from the query conditions when no match is found
 *
 * @method EmpApproval findOneByRequest(string $request) Return the first EmpApproval filtered by the request column
 * @method EmpApproval findOneByStatus(string $status) Return the first EmpApproval filtered by the status column
 * @method EmpApproval findOneByDate(string $date) Return the first EmpApproval filtered by the date column
 * @method EmpApproval findOneByIpAdd(string $ip_add) Return the first EmpApproval filtered by the ip_add column
 * @method EmpApproval findOneByEmpTimeId(int $emp_time_id) Return the first EmpApproval filtered by the emp_time_id column
 *
 * @method array findById(int $id) Return EmpApproval objects filtered by the id column
 * @method array findByRequest(string $request) Return EmpApproval objects filtered by the request column
 * @method array findByStatus(string $status) Return EmpApproval objects filtered by the status column
 * @method array findByDate(string $date) Return EmpApproval objects filtered by the date column
 * @method array findByIpAdd(string $ip_add) Return EmpApproval objects filtered by the ip_add column
 * @method array findByEmpTimeId(int $emp_time_id) Return EmpApproval objects filtered by the emp_time_id column
 */
abstract class BaseEmpApprovalQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseEmpApprovalQuery object.
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
            $modelName = 'CoreBundle\\Model\\EmpApproval';
        }
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new EmpApprovalQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   EmpApprovalQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return EmpApprovalQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof EmpApprovalQuery) {
            return $criteria;
        }
        $query = new EmpApprovalQuery(null, null, $modelAlias);

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
     * @return   EmpApproval|EmpApproval[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = EmpApprovalPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(EmpApprovalPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 EmpApproval A model object, or null if the key is not found
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
     * @return                 EmpApproval A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id`, `request`, `status`, `date`, `ip_add`, `emp_time_id` FROM `emp_approval` WHERE `id` = :p0';
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
            $obj = new EmpApproval();
            $obj->hydrate($row);
            EmpApprovalPeer::addInstanceToPool($obj, (string) $key);
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
     * @return EmpApproval|EmpApproval[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|EmpApproval[]|mixed the list of results, formatted by the current formatter
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
     * @return EmpApprovalQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(EmpApprovalPeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return EmpApprovalQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(EmpApprovalPeer::ID, $keys, Criteria::IN);
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
     * @return EmpApprovalQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(EmpApprovalPeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(EmpApprovalPeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EmpApprovalPeer::ID, $id, $comparison);
    }

    /**
     * Filter the query on the request column
     *
     * Example usage:
     * <code>
     * $query->filterByRequest('fooValue');   // WHERE request = 'fooValue'
     * $query->filterByRequest('%fooValue%'); // WHERE request LIKE '%fooValue%'
     * </code>
     *
     * @param     string $request The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return EmpApprovalQuery The current query, for fluid interface
     */
    public function filterByRequest($request = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($request)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $request)) {
                $request = str_replace('*', '%', $request);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(EmpApprovalPeer::REQUEST, $request, $comparison);
    }

    /**
     * Filter the query on the status column
     *
     * Example usage:
     * <code>
     * $query->filterByStatus('fooValue');   // WHERE status = 'fooValue'
     * $query->filterByStatus('%fooValue%'); // WHERE status LIKE '%fooValue%'
     * </code>
     *
     * @param     string $status The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return EmpApprovalQuery The current query, for fluid interface
     */
    public function filterByStatus($status = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($status)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $status)) {
                $status = str_replace('*', '%', $status);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(EmpApprovalPeer::STATUS, $status, $comparison);
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
     * @return EmpApprovalQuery The current query, for fluid interface
     */
    public function filterByDate($date = null, $comparison = null)
    {
        if (is_array($date)) {
            $useMinMax = false;
            if (isset($date['min'])) {
                $this->addUsingAlias(EmpApprovalPeer::DATE, $date['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($date['max'])) {
                $this->addUsingAlias(EmpApprovalPeer::DATE, $date['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EmpApprovalPeer::DATE, $date, $comparison);
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
     * @return EmpApprovalQuery The current query, for fluid interface
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

        return $this->addUsingAlias(EmpApprovalPeer::IP_ADD, $ipAdd, $comparison);
    }

    /**
     * Filter the query on the emp_time_id column
     *
     * Example usage:
     * <code>
     * $query->filterByEmpTimeId(1234); // WHERE emp_time_id = 1234
     * $query->filterByEmpTimeId(array(12, 34)); // WHERE emp_time_id IN (12, 34)
     * $query->filterByEmpTimeId(array('min' => 12)); // WHERE emp_time_id >= 12
     * $query->filterByEmpTimeId(array('max' => 12)); // WHERE emp_time_id <= 12
     * </code>
     *
     * @see       filterByEmpTime()
     *
     * @param     mixed $empTimeId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return EmpApprovalQuery The current query, for fluid interface
     */
    public function filterByEmpTimeId($empTimeId = null, $comparison = null)
    {
        if (is_array($empTimeId)) {
            $useMinMax = false;
            if (isset($empTimeId['min'])) {
                $this->addUsingAlias(EmpApprovalPeer::EMP_TIME_ID, $empTimeId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($empTimeId['max'])) {
                $this->addUsingAlias(EmpApprovalPeer::EMP_TIME_ID, $empTimeId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EmpApprovalPeer::EMP_TIME_ID, $empTimeId, $comparison);
    }

    /**
     * Filter the query by a related EmpTime object
     *
     * @param   EmpTime|PropelObjectCollection $empTime The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 EmpApprovalQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByEmpTime($empTime, $comparison = null)
    {
        if ($empTime instanceof EmpTime) {
            return $this
                ->addUsingAlias(EmpApprovalPeer::EMP_TIME_ID, $empTime->getId(), $comparison);
        } elseif ($empTime instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(EmpApprovalPeer::EMP_TIME_ID, $empTime->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByEmpTime() only accepts arguments of type EmpTime or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the EmpTime relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return EmpApprovalQuery The current query, for fluid interface
     */
    public function joinEmpTime($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('EmpTime');

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
            $this->addJoinObject($join, 'EmpTime');
        }

        return $this;
    }

    /**
     * Use the EmpTime relation EmpTime object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \CoreBundle\Model\EmpTimeQuery A secondary query class using the current class as primary query
     */
    public function useEmpTimeQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinEmpTime($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'EmpTime', '\CoreBundle\Model\EmpTimeQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   EmpApproval $empApproval Object to remove from the list of results
     *
     * @return EmpApprovalQuery The current query, for fluid interface
     */
    public function prune($empApproval = null)
    {
        if ($empApproval) {
            $this->addUsingAlias(EmpApprovalPeer::ID, $empApproval->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

}
