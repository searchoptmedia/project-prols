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
use CoreBundle\Model\EmpLeave;
use CoreBundle\Model\EmpLeavePeer;
use CoreBundle\Model\EmpLeaveQuery;
use CoreBundle\Model\ListLeaveType;

/**
 * @method EmpLeaveQuery orderById($order = Criteria::ASC) Order by the id column
 * @method EmpLeaveQuery orderByRequest($order = Criteria::ASC) Order by the request column
 * @method EmpLeaveQuery orderByStatus($order = Criteria::ASC) Order by the status column
 * @method EmpLeaveQuery orderByDateStarted($order = Criteria::ASC) Order by the date_started column
 * @method EmpLeaveQuery orderByDateEnded($order = Criteria::ASC) Order by the date_ended column
 * @method EmpLeaveQuery orderByEmpAccId($order = Criteria::ASC) Order by the emp_acc_id column
 * @method EmpLeaveQuery orderByListLeaveTypeId($order = Criteria::ASC) Order by the list_leave_type_id column
 * @method EmpLeaveQuery orderByAdminId($order = Criteria::ASC) Order by the admin_id column
 *
 * @method EmpLeaveQuery groupById() Group by the id column
 * @method EmpLeaveQuery groupByRequest() Group by the request column
 * @method EmpLeaveQuery groupByStatus() Group by the status column
 * @method EmpLeaveQuery groupByDateStarted() Group by the date_started column
 * @method EmpLeaveQuery groupByDateEnded() Group by the date_ended column
 * @method EmpLeaveQuery groupByEmpAccId() Group by the emp_acc_id column
 * @method EmpLeaveQuery groupByListLeaveTypeId() Group by the list_leave_type_id column
 * @method EmpLeaveQuery groupByAdminId() Group by the admin_id column
 *
 * @method EmpLeaveQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method EmpLeaveQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method EmpLeaveQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method EmpLeaveQuery leftJoinEmpAccRelatedByEmpAccId($relationAlias = null) Adds a LEFT JOIN clause to the query using the EmpAccRelatedByEmpAccId relation
 * @method EmpLeaveQuery rightJoinEmpAccRelatedByEmpAccId($relationAlias = null) Adds a RIGHT JOIN clause to the query using the EmpAccRelatedByEmpAccId relation
 * @method EmpLeaveQuery innerJoinEmpAccRelatedByEmpAccId($relationAlias = null) Adds a INNER JOIN clause to the query using the EmpAccRelatedByEmpAccId relation
 *
 * @method EmpLeaveQuery leftJoinListLeaveType($relationAlias = null) Adds a LEFT JOIN clause to the query using the ListLeaveType relation
 * @method EmpLeaveQuery rightJoinListLeaveType($relationAlias = null) Adds a RIGHT JOIN clause to the query using the ListLeaveType relation
 * @method EmpLeaveQuery innerJoinListLeaveType($relationAlias = null) Adds a INNER JOIN clause to the query using the ListLeaveType relation
 *
 * @method EmpLeaveQuery leftJoinEmpAccRelatedByAdminId($relationAlias = null) Adds a LEFT JOIN clause to the query using the EmpAccRelatedByAdminId relation
 * @method EmpLeaveQuery rightJoinEmpAccRelatedByAdminId($relationAlias = null) Adds a RIGHT JOIN clause to the query using the EmpAccRelatedByAdminId relation
 * @method EmpLeaveQuery innerJoinEmpAccRelatedByAdminId($relationAlias = null) Adds a INNER JOIN clause to the query using the EmpAccRelatedByAdminId relation
 *
 * @method EmpLeave findOne(PropelPDO $con = null) Return the first EmpLeave matching the query
 * @method EmpLeave findOneOrCreate(PropelPDO $con = null) Return the first EmpLeave matching the query, or a new EmpLeave object populated from the query conditions when no match is found
 *
 * @method EmpLeave findOneByRequest(string $request) Return the first EmpLeave filtered by the request column
 * @method EmpLeave findOneByStatus(string $status) Return the first EmpLeave filtered by the status column
 * @method EmpLeave findOneByDateStarted(string $date_started) Return the first EmpLeave filtered by the date_started column
 * @method EmpLeave findOneByDateEnded(string $date_ended) Return the first EmpLeave filtered by the date_ended column
 * @method EmpLeave findOneByEmpAccId(int $emp_acc_id) Return the first EmpLeave filtered by the emp_acc_id column
 * @method EmpLeave findOneByListLeaveTypeId(int $list_leave_type_id) Return the first EmpLeave filtered by the list_leave_type_id column
 * @method EmpLeave findOneByAdminId(int $admin_id) Return the first EmpLeave filtered by the admin_id column
 *
 * @method array findById(int $id) Return EmpLeave objects filtered by the id column
 * @method array findByRequest(string $request) Return EmpLeave objects filtered by the request column
 * @method array findByStatus(string $status) Return EmpLeave objects filtered by the status column
 * @method array findByDateStarted(string $date_started) Return EmpLeave objects filtered by the date_started column
 * @method array findByDateEnded(string $date_ended) Return EmpLeave objects filtered by the date_ended column
 * @method array findByEmpAccId(int $emp_acc_id) Return EmpLeave objects filtered by the emp_acc_id column
 * @method array findByListLeaveTypeId(int $list_leave_type_id) Return EmpLeave objects filtered by the list_leave_type_id column
 * @method array findByAdminId(int $admin_id) Return EmpLeave objects filtered by the admin_id column
 */
abstract class BaseEmpLeaveQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseEmpLeaveQuery object.
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
            $modelName = 'CoreBundle\\Model\\EmpLeave';
        }
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new EmpLeaveQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   EmpLeaveQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return EmpLeaveQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof EmpLeaveQuery) {
            return $criteria;
        }
        $query = new EmpLeaveQuery(null, null, $modelAlias);

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
     * @return   EmpLeave|EmpLeave[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = EmpLeavePeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(EmpLeavePeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 EmpLeave A model object, or null if the key is not found
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
     * @return                 EmpLeave A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id`, `request`, `status`, `date_started`, `date_ended`, `emp_acc_id`, `list_leave_type_id`, `admin_id` FROM `emp_leave` WHERE `id` = :p0';
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
            $obj = new EmpLeave();
            $obj->hydrate($row);
            EmpLeavePeer::addInstanceToPool($obj, (string) $key);
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
     * @return EmpLeave|EmpLeave[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|EmpLeave[]|mixed the list of results, formatted by the current formatter
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
     * @return EmpLeaveQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(EmpLeavePeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return EmpLeaveQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(EmpLeavePeer::ID, $keys, Criteria::IN);
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
     * @return EmpLeaveQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(EmpLeavePeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(EmpLeavePeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EmpLeavePeer::ID, $id, $comparison);
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
     * @return EmpLeaveQuery The current query, for fluid interface
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

        return $this->addUsingAlias(EmpLeavePeer::REQUEST, $request, $comparison);
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
     * @return EmpLeaveQuery The current query, for fluid interface
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

        return $this->addUsingAlias(EmpLeavePeer::STATUS, $status, $comparison);
    }

    /**
     * Filter the query on the date_started column
     *
     * Example usage:
     * <code>
     * $query->filterByDateStarted('2011-03-14'); // WHERE date_started = '2011-03-14'
     * $query->filterByDateStarted('now'); // WHERE date_started = '2011-03-14'
     * $query->filterByDateStarted(array('max' => 'yesterday')); // WHERE date_started < '2011-03-13'
     * </code>
     *
     * @param     mixed $dateStarted The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return EmpLeaveQuery The current query, for fluid interface
     */
    public function filterByDateStarted($dateStarted = null, $comparison = null)
    {
        if (is_array($dateStarted)) {
            $useMinMax = false;
            if (isset($dateStarted['min'])) {
                $this->addUsingAlias(EmpLeavePeer::DATE_STARTED, $dateStarted['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($dateStarted['max'])) {
                $this->addUsingAlias(EmpLeavePeer::DATE_STARTED, $dateStarted['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EmpLeavePeer::DATE_STARTED, $dateStarted, $comparison);
    }

    /**
     * Filter the query on the date_ended column
     *
     * Example usage:
     * <code>
     * $query->filterByDateEnded('2011-03-14'); // WHERE date_ended = '2011-03-14'
     * $query->filterByDateEnded('now'); // WHERE date_ended = '2011-03-14'
     * $query->filterByDateEnded(array('max' => 'yesterday')); // WHERE date_ended < '2011-03-13'
     * </code>
     *
     * @param     mixed $dateEnded The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return EmpLeaveQuery The current query, for fluid interface
     */
    public function filterByDateEnded($dateEnded = null, $comparison = null)
    {
        if (is_array($dateEnded)) {
            $useMinMax = false;
            if (isset($dateEnded['min'])) {
                $this->addUsingAlias(EmpLeavePeer::DATE_ENDED, $dateEnded['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($dateEnded['max'])) {
                $this->addUsingAlias(EmpLeavePeer::DATE_ENDED, $dateEnded['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EmpLeavePeer::DATE_ENDED, $dateEnded, $comparison);
    }

    /**
     * Filter the query on the emp_acc_id column
     *
     * Example usage:
     * <code>
     * $query->filterByEmpAccId(1234); // WHERE emp_acc_id = 1234
     * $query->filterByEmpAccId(array(12, 34)); // WHERE emp_acc_id IN (12, 34)
     * $query->filterByEmpAccId(array('min' => 12)); // WHERE emp_acc_id >= 12
     * $query->filterByEmpAccId(array('max' => 12)); // WHERE emp_acc_id <= 12
     * </code>
     *
     * @see       filterByEmpAccRelatedByEmpAccId()
     *
     * @param     mixed $empAccId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return EmpLeaveQuery The current query, for fluid interface
     */
    public function filterByEmpAccId($empAccId = null, $comparison = null)
    {
        if (is_array($empAccId)) {
            $useMinMax = false;
            if (isset($empAccId['min'])) {
                $this->addUsingAlias(EmpLeavePeer::EMP_ACC_ID, $empAccId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($empAccId['max'])) {
                $this->addUsingAlias(EmpLeavePeer::EMP_ACC_ID, $empAccId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EmpLeavePeer::EMP_ACC_ID, $empAccId, $comparison);
    }

    /**
     * Filter the query on the list_leave_type_id column
     *
     * Example usage:
     * <code>
     * $query->filterByListLeaveTypeId(1234); // WHERE list_leave_type_id = 1234
     * $query->filterByListLeaveTypeId(array(12, 34)); // WHERE list_leave_type_id IN (12, 34)
     * $query->filterByListLeaveTypeId(array('min' => 12)); // WHERE list_leave_type_id >= 12
     * $query->filterByListLeaveTypeId(array('max' => 12)); // WHERE list_leave_type_id <= 12
     * </code>
     *
     * @see       filterByListLeaveType()
     *
     * @param     mixed $listLeaveTypeId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return EmpLeaveQuery The current query, for fluid interface
     */
    public function filterByListLeaveTypeId($listLeaveTypeId = null, $comparison = null)
    {
        if (is_array($listLeaveTypeId)) {
            $useMinMax = false;
            if (isset($listLeaveTypeId['min'])) {
                $this->addUsingAlias(EmpLeavePeer::LIST_LEAVE_TYPE_ID, $listLeaveTypeId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($listLeaveTypeId['max'])) {
                $this->addUsingAlias(EmpLeavePeer::LIST_LEAVE_TYPE_ID, $listLeaveTypeId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EmpLeavePeer::LIST_LEAVE_TYPE_ID, $listLeaveTypeId, $comparison);
    }

    /**
     * Filter the query on the admin_id column
     *
     * Example usage:
     * <code>
     * $query->filterByAdminId(1234); // WHERE admin_id = 1234
     * $query->filterByAdminId(array(12, 34)); // WHERE admin_id IN (12, 34)
     * $query->filterByAdminId(array('min' => 12)); // WHERE admin_id >= 12
     * $query->filterByAdminId(array('max' => 12)); // WHERE admin_id <= 12
     * </code>
     *
     * @see       filterByEmpAccRelatedByAdminId()
     *
     * @param     mixed $adminId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return EmpLeaveQuery The current query, for fluid interface
     */
    public function filterByAdminId($adminId = null, $comparison = null)
    {
        if (is_array($adminId)) {
            $useMinMax = false;
            if (isset($adminId['min'])) {
                $this->addUsingAlias(EmpLeavePeer::ADMIN_ID, $adminId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($adminId['max'])) {
                $this->addUsingAlias(EmpLeavePeer::ADMIN_ID, $adminId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EmpLeavePeer::ADMIN_ID, $adminId, $comparison);
    }

    /**
     * Filter the query by a related EmpAcc object
     *
     * @param   EmpAcc|PropelObjectCollection $empAcc The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 EmpLeaveQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByEmpAccRelatedByEmpAccId($empAcc, $comparison = null)
    {
        if ($empAcc instanceof EmpAcc) {
            return $this
                ->addUsingAlias(EmpLeavePeer::EMP_ACC_ID, $empAcc->getId(), $comparison);
        } elseif ($empAcc instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(EmpLeavePeer::EMP_ACC_ID, $empAcc->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByEmpAccRelatedByEmpAccId() only accepts arguments of type EmpAcc or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the EmpAccRelatedByEmpAccId relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return EmpLeaveQuery The current query, for fluid interface
     */
    public function joinEmpAccRelatedByEmpAccId($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('EmpAccRelatedByEmpAccId');

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
            $this->addJoinObject($join, 'EmpAccRelatedByEmpAccId');
        }

        return $this;
    }

    /**
     * Use the EmpAccRelatedByEmpAccId relation EmpAcc object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \CoreBundle\Model\EmpAccQuery A secondary query class using the current class as primary query
     */
    public function useEmpAccRelatedByEmpAccIdQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinEmpAccRelatedByEmpAccId($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'EmpAccRelatedByEmpAccId', '\CoreBundle\Model\EmpAccQuery');
    }

    /**
     * Filter the query by a related ListLeaveType object
     *
     * @param   ListLeaveType|PropelObjectCollection $listLeaveType The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 EmpLeaveQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByListLeaveType($listLeaveType, $comparison = null)
    {
        if ($listLeaveType instanceof ListLeaveType) {
            return $this
                ->addUsingAlias(EmpLeavePeer::LIST_LEAVE_TYPE_ID, $listLeaveType->getId(), $comparison);
        } elseif ($listLeaveType instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(EmpLeavePeer::LIST_LEAVE_TYPE_ID, $listLeaveType->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByListLeaveType() only accepts arguments of type ListLeaveType or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the ListLeaveType relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return EmpLeaveQuery The current query, for fluid interface
     */
    public function joinListLeaveType($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('ListLeaveType');

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
            $this->addJoinObject($join, 'ListLeaveType');
        }

        return $this;
    }

    /**
     * Use the ListLeaveType relation ListLeaveType object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \CoreBundle\Model\ListLeaveTypeQuery A secondary query class using the current class as primary query
     */
    public function useListLeaveTypeQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinListLeaveType($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'ListLeaveType', '\CoreBundle\Model\ListLeaveTypeQuery');
    }

    /**
     * Filter the query by a related EmpAcc object
     *
     * @param   EmpAcc|PropelObjectCollection $empAcc The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 EmpLeaveQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByEmpAccRelatedByAdminId($empAcc, $comparison = null)
    {
        if ($empAcc instanceof EmpAcc) {
            return $this
                ->addUsingAlias(EmpLeavePeer::ADMIN_ID, $empAcc->getId(), $comparison);
        } elseif ($empAcc instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(EmpLeavePeer::ADMIN_ID, $empAcc->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByEmpAccRelatedByAdminId() only accepts arguments of type EmpAcc or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the EmpAccRelatedByAdminId relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return EmpLeaveQuery The current query, for fluid interface
     */
    public function joinEmpAccRelatedByAdminId($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('EmpAccRelatedByAdminId');

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
            $this->addJoinObject($join, 'EmpAccRelatedByAdminId');
        }

        return $this;
    }

    /**
     * Use the EmpAccRelatedByAdminId relation EmpAcc object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \CoreBundle\Model\EmpAccQuery A secondary query class using the current class as primary query
     */
    public function useEmpAccRelatedByAdminIdQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinEmpAccRelatedByAdminId($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'EmpAccRelatedByAdminId', '\CoreBundle\Model\EmpAccQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   EmpLeave $empLeave Object to remove from the list of results
     *
     * @return EmpLeaveQuery The current query, for fluid interface
     */
    public function prune($empLeave = null)
    {
        if ($empLeave) {
            $this->addUsingAlias(EmpLeavePeer::ID, $empLeave->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

}
