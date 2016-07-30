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
use CoreBundle\Model\EmpWork;
use CoreBundle\Model\EmpWorkPeer;
use CoreBundle\Model\EmpWorkQuery;
use CoreBundle\Model\ListDept;
use CoreBundle\Model\ListPos;

/**
 * @method EmpWorkQuery orderById($order = Criteria::ASC) Order by the id column
 * @method EmpWorkQuery orderByStatus($order = Criteria::ASC) Order by the status column
 * @method EmpWorkQuery orderByListDeptDeptId($order = Criteria::ASC) Order by the list_dept_dept_id column
 * @method EmpWorkQuery orderByListPosPosId($order = Criteria::ASC) Order by the list_pos_pos_id column
 * @method EmpWorkQuery orderByEmpAccAccId($order = Criteria::ASC) Order by the emp_acc_acc_id column
 *
 * @method EmpWorkQuery groupById() Group by the id column
 * @method EmpWorkQuery groupByStatus() Group by the status column
 * @method EmpWorkQuery groupByListDeptDeptId() Group by the list_dept_dept_id column
 * @method EmpWorkQuery groupByListPosPosId() Group by the list_pos_pos_id column
 * @method EmpWorkQuery groupByEmpAccAccId() Group by the emp_acc_acc_id column
 *
 * @method EmpWorkQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method EmpWorkQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method EmpWorkQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method EmpWorkQuery leftJoinEmpAcc($relationAlias = null) Adds a LEFT JOIN clause to the query using the EmpAcc relation
 * @method EmpWorkQuery rightJoinEmpAcc($relationAlias = null) Adds a RIGHT JOIN clause to the query using the EmpAcc relation
 * @method EmpWorkQuery innerJoinEmpAcc($relationAlias = null) Adds a INNER JOIN clause to the query using the EmpAcc relation
 *
 * @method EmpWorkQuery leftJoinListDept($relationAlias = null) Adds a LEFT JOIN clause to the query using the ListDept relation
 * @method EmpWorkQuery rightJoinListDept($relationAlias = null) Adds a RIGHT JOIN clause to the query using the ListDept relation
 * @method EmpWorkQuery innerJoinListDept($relationAlias = null) Adds a INNER JOIN clause to the query using the ListDept relation
 *
 * @method EmpWorkQuery leftJoinListPos($relationAlias = null) Adds a LEFT JOIN clause to the query using the ListPos relation
 * @method EmpWorkQuery rightJoinListPos($relationAlias = null) Adds a RIGHT JOIN clause to the query using the ListPos relation
 * @method EmpWorkQuery innerJoinListPos($relationAlias = null) Adds a INNER JOIN clause to the query using the ListPos relation
 *
 * @method EmpWork findOne(PropelPDO $con = null) Return the first EmpWork matching the query
 * @method EmpWork findOneOrCreate(PropelPDO $con = null) Return the first EmpWork matching the query, or a new EmpWork object populated from the query conditions when no match is found
 *
 * @method EmpWork findOneByStatus(string $status) Return the first EmpWork filtered by the status column
 * @method EmpWork findOneByListDeptDeptId(int $list_dept_dept_id) Return the first EmpWork filtered by the list_dept_dept_id column
 * @method EmpWork findOneByListPosPosId(int $list_pos_pos_id) Return the first EmpWork filtered by the list_pos_pos_id column
 * @method EmpWork findOneByEmpAccAccId(int $emp_acc_acc_id) Return the first EmpWork filtered by the emp_acc_acc_id column
 *
 * @method array findById(int $id) Return EmpWork objects filtered by the id column
 * @method array findByStatus(string $status) Return EmpWork objects filtered by the status column
 * @method array findByListDeptDeptId(int $list_dept_dept_id) Return EmpWork objects filtered by the list_dept_dept_id column
 * @method array findByListPosPosId(int $list_pos_pos_id) Return EmpWork objects filtered by the list_pos_pos_id column
 * @method array findByEmpAccAccId(int $emp_acc_acc_id) Return EmpWork objects filtered by the emp_acc_acc_id column
 */
abstract class BaseEmpWorkQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseEmpWorkQuery object.
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
            $modelName = 'CoreBundle\\Model\\EmpWork';
        }
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new EmpWorkQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   EmpWorkQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return EmpWorkQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof EmpWorkQuery) {
            return $criteria;
        }
        $query = new EmpWorkQuery(null, null, $modelAlias);

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
     * @return   EmpWork|EmpWork[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = EmpWorkPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(EmpWorkPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 EmpWork A model object, or null if the key is not found
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
     * @return                 EmpWork A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id`, `status`, `list_dept_dept_id`, `list_pos_pos_id`, `emp_acc_acc_id` FROM `emp_work` WHERE `id` = :p0';
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
            $obj = new EmpWork();
            $obj->hydrate($row);
            EmpWorkPeer::addInstanceToPool($obj, (string) $key);
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
     * @return EmpWork|EmpWork[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|EmpWork[]|mixed the list of results, formatted by the current formatter
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
     * @return EmpWorkQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(EmpWorkPeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return EmpWorkQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(EmpWorkPeer::ID, $keys, Criteria::IN);
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
     * @return EmpWorkQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(EmpWorkPeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(EmpWorkPeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EmpWorkPeer::ID, $id, $comparison);
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
     * @return EmpWorkQuery The current query, for fluid interface
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

        return $this->addUsingAlias(EmpWorkPeer::STATUS, $status, $comparison);
    }

    /**
     * Filter the query on the list_dept_dept_id column
     *
     * Example usage:
     * <code>
     * $query->filterByListDeptDeptId(1234); // WHERE list_dept_dept_id = 1234
     * $query->filterByListDeptDeptId(array(12, 34)); // WHERE list_dept_dept_id IN (12, 34)
     * $query->filterByListDeptDeptId(array('min' => 12)); // WHERE list_dept_dept_id >= 12
     * $query->filterByListDeptDeptId(array('max' => 12)); // WHERE list_dept_dept_id <= 12
     * </code>
     *
     * @see       filterByListDept()
     *
     * @param     mixed $listDeptDeptId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return EmpWorkQuery The current query, for fluid interface
     */
    public function filterByListDeptDeptId($listDeptDeptId = null, $comparison = null)
    {
        if (is_array($listDeptDeptId)) {
            $useMinMax = false;
            if (isset($listDeptDeptId['min'])) {
                $this->addUsingAlias(EmpWorkPeer::LIST_DEPT_DEPT_ID, $listDeptDeptId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($listDeptDeptId['max'])) {
                $this->addUsingAlias(EmpWorkPeer::LIST_DEPT_DEPT_ID, $listDeptDeptId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EmpWorkPeer::LIST_DEPT_DEPT_ID, $listDeptDeptId, $comparison);
    }

    /**
     * Filter the query on the list_pos_pos_id column
     *
     * Example usage:
     * <code>
     * $query->filterByListPosPosId(1234); // WHERE list_pos_pos_id = 1234
     * $query->filterByListPosPosId(array(12, 34)); // WHERE list_pos_pos_id IN (12, 34)
     * $query->filterByListPosPosId(array('min' => 12)); // WHERE list_pos_pos_id >= 12
     * $query->filterByListPosPosId(array('max' => 12)); // WHERE list_pos_pos_id <= 12
     * </code>
     *
     * @see       filterByListPos()
     *
     * @param     mixed $listPosPosId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return EmpWorkQuery The current query, for fluid interface
     */
    public function filterByListPosPosId($listPosPosId = null, $comparison = null)
    {
        if (is_array($listPosPosId)) {
            $useMinMax = false;
            if (isset($listPosPosId['min'])) {
                $this->addUsingAlias(EmpWorkPeer::LIST_POS_POS_ID, $listPosPosId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($listPosPosId['max'])) {
                $this->addUsingAlias(EmpWorkPeer::LIST_POS_POS_ID, $listPosPosId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EmpWorkPeer::LIST_POS_POS_ID, $listPosPosId, $comparison);
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
     * @return EmpWorkQuery The current query, for fluid interface
     */
    public function filterByEmpAccAccId($empAccAccId = null, $comparison = null)
    {
        if (is_array($empAccAccId)) {
            $useMinMax = false;
            if (isset($empAccAccId['min'])) {
                $this->addUsingAlias(EmpWorkPeer::EMP_ACC_ACC_ID, $empAccAccId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($empAccAccId['max'])) {
                $this->addUsingAlias(EmpWorkPeer::EMP_ACC_ACC_ID, $empAccAccId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EmpWorkPeer::EMP_ACC_ACC_ID, $empAccAccId, $comparison);
    }

    /**
     * Filter the query by a related EmpAcc object
     *
     * @param   EmpAcc|PropelObjectCollection $empAcc The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 EmpWorkQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByEmpAcc($empAcc, $comparison = null)
    {
        if ($empAcc instanceof EmpAcc) {
            return $this
                ->addUsingAlias(EmpWorkPeer::EMP_ACC_ACC_ID, $empAcc->getId(), $comparison);
        } elseif ($empAcc instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(EmpWorkPeer::EMP_ACC_ACC_ID, $empAcc->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return EmpWorkQuery The current query, for fluid interface
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
     * Filter the query by a related ListDept object
     *
     * @param   ListDept|PropelObjectCollection $listDept The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 EmpWorkQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByListDept($listDept, $comparison = null)
    {
        if ($listDept instanceof ListDept) {
            return $this
                ->addUsingAlias(EmpWorkPeer::LIST_DEPT_DEPT_ID, $listDept->getId(), $comparison);
        } elseif ($listDept instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(EmpWorkPeer::LIST_DEPT_DEPT_ID, $listDept->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByListDept() only accepts arguments of type ListDept or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the ListDept relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return EmpWorkQuery The current query, for fluid interface
     */
    public function joinListDept($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('ListDept');

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
            $this->addJoinObject($join, 'ListDept');
        }

        return $this;
    }

    /**
     * Use the ListDept relation ListDept object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \CoreBundle\Model\ListDeptQuery A secondary query class using the current class as primary query
     */
    public function useListDeptQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinListDept($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'ListDept', '\CoreBundle\Model\ListDeptQuery');
    }

    /**
     * Filter the query by a related ListPos object
     *
     * @param   ListPos|PropelObjectCollection $listPos The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 EmpWorkQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByListPos($listPos, $comparison = null)
    {
        if ($listPos instanceof ListPos) {
            return $this
                ->addUsingAlias(EmpWorkPeer::LIST_POS_POS_ID, $listPos->getId(), $comparison);
        } elseif ($listPos instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(EmpWorkPeer::LIST_POS_POS_ID, $listPos->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByListPos() only accepts arguments of type ListPos or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the ListPos relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return EmpWorkQuery The current query, for fluid interface
     */
    public function joinListPos($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('ListPos');

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
            $this->addJoinObject($join, 'ListPos');
        }

        return $this;
    }

    /**
     * Use the ListPos relation ListPos object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \CoreBundle\Model\ListPosQuery A secondary query class using the current class as primary query
     */
    public function useListPosQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinListPos($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'ListPos', '\CoreBundle\Model\ListPosQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   EmpWork $empWork Object to remove from the list of results
     *
     * @return EmpWorkQuery The current query, for fluid interface
     */
    public function prune($empWork = null)
    {
        if ($empWork) {
            $this->addUsingAlias(EmpWorkPeer::ID, $empWork->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

}
