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
use CoreBundle\Model\EmpRequest;
use CoreBundle\Model\RequestMeetingTags;
use CoreBundle\Model\RequestMeetingTagsPeer;
use CoreBundle\Model\RequestMeetingTagsQuery;

/**
 * @method RequestMeetingTagsQuery orderById($order = Criteria::ASC) Order by the id column
 * @method RequestMeetingTagsQuery orderByRequestId($order = Criteria::ASC) Order by the request_id column
 * @method RequestMeetingTagsQuery orderByStatus($order = Criteria::ASC) Order by the status column
 * @method RequestMeetingTagsQuery orderByEmpAccId($order = Criteria::ASC) Order by the emp_acc_id column
 *
 * @method RequestMeetingTagsQuery groupById() Group by the id column
 * @method RequestMeetingTagsQuery groupByRequestId() Group by the request_id column
 * @method RequestMeetingTagsQuery groupByStatus() Group by the status column
 * @method RequestMeetingTagsQuery groupByEmpAccId() Group by the emp_acc_id column
 *
 * @method RequestMeetingTagsQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method RequestMeetingTagsQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method RequestMeetingTagsQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method RequestMeetingTagsQuery leftJoinEmpRequest($relationAlias = null) Adds a LEFT JOIN clause to the query using the EmpRequest relation
 * @method RequestMeetingTagsQuery rightJoinEmpRequest($relationAlias = null) Adds a RIGHT JOIN clause to the query using the EmpRequest relation
 * @method RequestMeetingTagsQuery innerJoinEmpRequest($relationAlias = null) Adds a INNER JOIN clause to the query using the EmpRequest relation
 *
 * @method RequestMeetingTagsQuery leftJoinEmpAcc($relationAlias = null) Adds a LEFT JOIN clause to the query using the EmpAcc relation
 * @method RequestMeetingTagsQuery rightJoinEmpAcc($relationAlias = null) Adds a RIGHT JOIN clause to the query using the EmpAcc relation
 * @method RequestMeetingTagsQuery innerJoinEmpAcc($relationAlias = null) Adds a INNER JOIN clause to the query using the EmpAcc relation
 *
 * @method RequestMeetingTags findOne(PropelPDO $con = null) Return the first RequestMeetingTags matching the query
 * @method RequestMeetingTags findOneOrCreate(PropelPDO $con = null) Return the first RequestMeetingTags matching the query, or a new RequestMeetingTags object populated from the query conditions when no match is found
 *
 * @method RequestMeetingTags findOneByRequestId(int $request_id) Return the first RequestMeetingTags filtered by the request_id column
 * @method RequestMeetingTags findOneByStatus(int $status) Return the first RequestMeetingTags filtered by the status column
 * @method RequestMeetingTags findOneByEmpAccId(int $emp_acc_id) Return the first RequestMeetingTags filtered by the emp_acc_id column
 *
 * @method array findById(int $id) Return RequestMeetingTags objects filtered by the id column
 * @method array findByRequestId(int $request_id) Return RequestMeetingTags objects filtered by the request_id column
 * @method array findByStatus(int $status) Return RequestMeetingTags objects filtered by the status column
 * @method array findByEmpAccId(int $emp_acc_id) Return RequestMeetingTags objects filtered by the emp_acc_id column
 */
abstract class BaseRequestMeetingTagsQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseRequestMeetingTagsQuery object.
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
            $modelName = 'CoreBundle\\Model\\RequestMeetingTags';
        }
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new RequestMeetingTagsQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   RequestMeetingTagsQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return RequestMeetingTagsQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof RequestMeetingTagsQuery) {
            return $criteria;
        }
        $query = new RequestMeetingTagsQuery(null, null, $modelAlias);

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
     * @return   RequestMeetingTags|RequestMeetingTags[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = RequestMeetingTagsPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(RequestMeetingTagsPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 RequestMeetingTags A model object, or null if the key is not found
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
     * @return                 RequestMeetingTags A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id`, `request_id`, `status`, `emp_acc_id` FROM `request_meeting_tags` WHERE `id` = :p0';
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
            $obj = new RequestMeetingTags();
            $obj->hydrate($row);
            RequestMeetingTagsPeer::addInstanceToPool($obj, (string) $key);
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
     * @return RequestMeetingTags|RequestMeetingTags[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|RequestMeetingTags[]|mixed the list of results, formatted by the current formatter
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
     * @return RequestMeetingTagsQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(RequestMeetingTagsPeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return RequestMeetingTagsQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(RequestMeetingTagsPeer::ID, $keys, Criteria::IN);
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
     * @return RequestMeetingTagsQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(RequestMeetingTagsPeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(RequestMeetingTagsPeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(RequestMeetingTagsPeer::ID, $id, $comparison);
    }

    /**
     * Filter the query on the request_id column
     *
     * Example usage:
     * <code>
     * $query->filterByRequestId(1234); // WHERE request_id = 1234
     * $query->filterByRequestId(array(12, 34)); // WHERE request_id IN (12, 34)
     * $query->filterByRequestId(array('min' => 12)); // WHERE request_id >= 12
     * $query->filterByRequestId(array('max' => 12)); // WHERE request_id <= 12
     * </code>
     *
     * @see       filterByEmpRequest()
     *
     * @param     mixed $requestId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return RequestMeetingTagsQuery The current query, for fluid interface
     */
    public function filterByRequestId($requestId = null, $comparison = null)
    {
        if (is_array($requestId)) {
            $useMinMax = false;
            if (isset($requestId['min'])) {
                $this->addUsingAlias(RequestMeetingTagsPeer::REQUEST_ID, $requestId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($requestId['max'])) {
                $this->addUsingAlias(RequestMeetingTagsPeer::REQUEST_ID, $requestId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(RequestMeetingTagsPeer::REQUEST_ID, $requestId, $comparison);
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
     * @return RequestMeetingTagsQuery The current query, for fluid interface
     */
    public function filterByStatus($status = null, $comparison = null)
    {
        if (is_array($status)) {
            $useMinMax = false;
            if (isset($status['min'])) {
                $this->addUsingAlias(RequestMeetingTagsPeer::STATUS, $status['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($status['max'])) {
                $this->addUsingAlias(RequestMeetingTagsPeer::STATUS, $status['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(RequestMeetingTagsPeer::STATUS, $status, $comparison);
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
     * @see       filterByEmpAcc()
     *
     * @param     mixed $empAccId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return RequestMeetingTagsQuery The current query, for fluid interface
     */
    public function filterByEmpAccId($empAccId = null, $comparison = null)
    {
        if (is_array($empAccId)) {
            $useMinMax = false;
            if (isset($empAccId['min'])) {
                $this->addUsingAlias(RequestMeetingTagsPeer::EMP_ACC_ID, $empAccId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($empAccId['max'])) {
                $this->addUsingAlias(RequestMeetingTagsPeer::EMP_ACC_ID, $empAccId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(RequestMeetingTagsPeer::EMP_ACC_ID, $empAccId, $comparison);
    }

    /**
     * Filter the query by a related EmpRequest object
     *
     * @param   EmpRequest|PropelObjectCollection $empRequest The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 RequestMeetingTagsQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByEmpRequest($empRequest, $comparison = null)
    {
        if ($empRequest instanceof EmpRequest) {
            return $this
                ->addUsingAlias(RequestMeetingTagsPeer::REQUEST_ID, $empRequest->getId(), $comparison);
        } elseif ($empRequest instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(RequestMeetingTagsPeer::REQUEST_ID, $empRequest->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByEmpRequest() only accepts arguments of type EmpRequest or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the EmpRequest relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return RequestMeetingTagsQuery The current query, for fluid interface
     */
    public function joinEmpRequest($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('EmpRequest');

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
            $this->addJoinObject($join, 'EmpRequest');
        }

        return $this;
    }

    /**
     * Use the EmpRequest relation EmpRequest object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \CoreBundle\Model\EmpRequestQuery A secondary query class using the current class as primary query
     */
    public function useEmpRequestQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinEmpRequest($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'EmpRequest', '\CoreBundle\Model\EmpRequestQuery');
    }

    /**
     * Filter the query by a related EmpAcc object
     *
     * @param   EmpAcc|PropelObjectCollection $empAcc The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 RequestMeetingTagsQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByEmpAcc($empAcc, $comparison = null)
    {
        if ($empAcc instanceof EmpAcc) {
            return $this
                ->addUsingAlias(RequestMeetingTagsPeer::EMP_ACC_ID, $empAcc->getId(), $comparison);
        } elseif ($empAcc instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(RequestMeetingTagsPeer::EMP_ACC_ID, $empAcc->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return RequestMeetingTagsQuery The current query, for fluid interface
     */
    public function joinEmpAcc($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
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
    public function useEmpAccQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinEmpAcc($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'EmpAcc', '\CoreBundle\Model\EmpAccQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   RequestMeetingTags $requestMeetingTags Object to remove from the list of results
     *
     * @return RequestMeetingTagsQuery The current query, for fluid interface
     */
    public function prune($requestMeetingTags = null)
    {
        if ($requestMeetingTags) {
            $this->addUsingAlias(RequestMeetingTagsPeer::ID, $requestMeetingTags->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

}
