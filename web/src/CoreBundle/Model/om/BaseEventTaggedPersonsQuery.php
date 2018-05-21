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
use CoreBundle\Model\EventTagHistory;
use CoreBundle\Model\EventTaggedPersons;
use CoreBundle\Model\EventTaggedPersonsPeer;
use CoreBundle\Model\EventTaggedPersonsQuery;
use CoreBundle\Model\ListEvents;

/**
 * @method EventTaggedPersonsQuery orderById($order = Criteria::ASC) Order by the id column
 * @method EventTaggedPersonsQuery orderByEventId($order = Criteria::ASC) Order by the event_id column
 * @method EventTaggedPersonsQuery orderByEmpId($order = Criteria::ASC) Order by the emp_id column
 * @method EventTaggedPersonsQuery orderByStatus($order = Criteria::ASC) Order by the status column
 * @method EventTaggedPersonsQuery orderByReason($order = Criteria::ASC) Order by the reason column
 *
 * @method EventTaggedPersonsQuery groupById() Group by the id column
 * @method EventTaggedPersonsQuery groupByEventId() Group by the event_id column
 * @method EventTaggedPersonsQuery groupByEmpId() Group by the emp_id column
 * @method EventTaggedPersonsQuery groupByStatus() Group by the status column
 * @method EventTaggedPersonsQuery groupByReason() Group by the reason column
 *
 * @method EventTaggedPersonsQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method EventTaggedPersonsQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method EventTaggedPersonsQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method EventTaggedPersonsQuery leftJoinEmpAcc($relationAlias = null) Adds a LEFT JOIN clause to the query using the EmpAcc relation
 * @method EventTaggedPersonsQuery rightJoinEmpAcc($relationAlias = null) Adds a RIGHT JOIN clause to the query using the EmpAcc relation
 * @method EventTaggedPersonsQuery innerJoinEmpAcc($relationAlias = null) Adds a INNER JOIN clause to the query using the EmpAcc relation
 *
 * @method EventTaggedPersonsQuery leftJoinListEvents($relationAlias = null) Adds a LEFT JOIN clause to the query using the ListEvents relation
 * @method EventTaggedPersonsQuery rightJoinListEvents($relationAlias = null) Adds a RIGHT JOIN clause to the query using the ListEvents relation
 * @method EventTaggedPersonsQuery innerJoinListEvents($relationAlias = null) Adds a INNER JOIN clause to the query using the ListEvents relation
 *
 * @method EventTaggedPersonsQuery leftJoinEventTagHistory($relationAlias = null) Adds a LEFT JOIN clause to the query using the EventTagHistory relation
 * @method EventTaggedPersonsQuery rightJoinEventTagHistory($relationAlias = null) Adds a RIGHT JOIN clause to the query using the EventTagHistory relation
 * @method EventTaggedPersonsQuery innerJoinEventTagHistory($relationAlias = null) Adds a INNER JOIN clause to the query using the EventTagHistory relation
 *
 * @method EventTaggedPersons findOne(PropelPDO $con = null) Return the first EventTaggedPersons matching the query
 * @method EventTaggedPersons findOneOrCreate(PropelPDO $con = null) Return the first EventTaggedPersons matching the query, or a new EventTaggedPersons object populated from the query conditions when no match is found
 *
 * @method EventTaggedPersons findOneByEventId(int $event_id) Return the first EventTaggedPersons filtered by the event_id column
 * @method EventTaggedPersons findOneByEmpId(int $emp_id) Return the first EventTaggedPersons filtered by the emp_id column
 * @method EventTaggedPersons findOneByStatus(int $status) Return the first EventTaggedPersons filtered by the status column
 * @method EventTaggedPersons findOneByReason(string $reason) Return the first EventTaggedPersons filtered by the reason column
 *
 * @method array findById(int $id) Return EventTaggedPersons objects filtered by the id column
 * @method array findByEventId(int $event_id) Return EventTaggedPersons objects filtered by the event_id column
 * @method array findByEmpId(int $emp_id) Return EventTaggedPersons objects filtered by the emp_id column
 * @method array findByStatus(int $status) Return EventTaggedPersons objects filtered by the status column
 * @method array findByReason(string $reason) Return EventTaggedPersons objects filtered by the reason column
 */
abstract class BaseEventTaggedPersonsQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseEventTaggedPersonsQuery object.
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
            $modelName = 'CoreBundle\\Model\\EventTaggedPersons';
        }
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new EventTaggedPersonsQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   EventTaggedPersonsQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return EventTaggedPersonsQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof EventTaggedPersonsQuery) {
            return $criteria;
        }
        $query = new EventTaggedPersonsQuery(null, null, $modelAlias);

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
     * @return   EventTaggedPersons|EventTaggedPersons[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = EventTaggedPersonsPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(EventTaggedPersonsPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 EventTaggedPersons A model object, or null if the key is not found
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
     * @return                 EventTaggedPersons A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id`, `event_id`, `emp_id`, `status`, `reason` FROM `event_tagged_persons` WHERE `id` = :p0';
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
            $obj = new EventTaggedPersons();
            $obj->hydrate($row);
            EventTaggedPersonsPeer::addInstanceToPool($obj, (string) $key);
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
     * @return EventTaggedPersons|EventTaggedPersons[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|EventTaggedPersons[]|mixed the list of results, formatted by the current formatter
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
     * @return EventTaggedPersonsQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(EventTaggedPersonsPeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return EventTaggedPersonsQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(EventTaggedPersonsPeer::ID, $keys, Criteria::IN);
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
     * @return EventTaggedPersonsQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(EventTaggedPersonsPeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(EventTaggedPersonsPeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EventTaggedPersonsPeer::ID, $id, $comparison);
    }

    /**
     * Filter the query on the event_id column
     *
     * Example usage:
     * <code>
     * $query->filterByEventId(1234); // WHERE event_id = 1234
     * $query->filterByEventId(array(12, 34)); // WHERE event_id IN (12, 34)
     * $query->filterByEventId(array('min' => 12)); // WHERE event_id >= 12
     * $query->filterByEventId(array('max' => 12)); // WHERE event_id <= 12
     * </code>
     *
     * @see       filterByListEvents()
     *
     * @param     mixed $eventId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return EventTaggedPersonsQuery The current query, for fluid interface
     */
    public function filterByEventId($eventId = null, $comparison = null)
    {
        if (is_array($eventId)) {
            $useMinMax = false;
            if (isset($eventId['min'])) {
                $this->addUsingAlias(EventTaggedPersonsPeer::EVENT_ID, $eventId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($eventId['max'])) {
                $this->addUsingAlias(EventTaggedPersonsPeer::EVENT_ID, $eventId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EventTaggedPersonsPeer::EVENT_ID, $eventId, $comparison);
    }

    /**
     * Filter the query on the emp_id column
     *
     * Example usage:
     * <code>
     * $query->filterByEmpId(1234); // WHERE emp_id = 1234
     * $query->filterByEmpId(array(12, 34)); // WHERE emp_id IN (12, 34)
     * $query->filterByEmpId(array('min' => 12)); // WHERE emp_id >= 12
     * $query->filterByEmpId(array('max' => 12)); // WHERE emp_id <= 12
     * </code>
     *
     * @see       filterByEmpAcc()
     *
     * @param     mixed $empId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return EventTaggedPersonsQuery The current query, for fluid interface
     */
    public function filterByEmpId($empId = null, $comparison = null)
    {
        if (is_array($empId)) {
            $useMinMax = false;
            if (isset($empId['min'])) {
                $this->addUsingAlias(EventTaggedPersonsPeer::EMP_ID, $empId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($empId['max'])) {
                $this->addUsingAlias(EventTaggedPersonsPeer::EMP_ID, $empId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EventTaggedPersonsPeer::EMP_ID, $empId, $comparison);
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
     * @return EventTaggedPersonsQuery The current query, for fluid interface
     */
    public function filterByStatus($status = null, $comparison = null)
    {
        if (is_array($status)) {
            $useMinMax = false;
            if (isset($status['min'])) {
                $this->addUsingAlias(EventTaggedPersonsPeer::STATUS, $status['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($status['max'])) {
                $this->addUsingAlias(EventTaggedPersonsPeer::STATUS, $status['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EventTaggedPersonsPeer::STATUS, $status, $comparison);
    }

    /**
     * Filter the query on the reason column
     *
     * Example usage:
     * <code>
     * $query->filterByReason('fooValue');   // WHERE reason = 'fooValue'
     * $query->filterByReason('%fooValue%'); // WHERE reason LIKE '%fooValue%'
     * </code>
     *
     * @param     string $reason The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return EventTaggedPersonsQuery The current query, for fluid interface
     */
    public function filterByReason($reason = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($reason)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $reason)) {
                $reason = str_replace('*', '%', $reason);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(EventTaggedPersonsPeer::REASON, $reason, $comparison);
    }

    /**
     * Filter the query by a related EmpAcc object
     *
     * @param   EmpAcc|PropelObjectCollection $empAcc The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 EventTaggedPersonsQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByEmpAcc($empAcc, $comparison = null)
    {
        if ($empAcc instanceof EmpAcc) {
            return $this
                ->addUsingAlias(EventTaggedPersonsPeer::EMP_ID, $empAcc->getId(), $comparison);
        } elseif ($empAcc instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(EventTaggedPersonsPeer::EMP_ID, $empAcc->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return EventTaggedPersonsQuery The current query, for fluid interface
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
     * Filter the query by a related ListEvents object
     *
     * @param   ListEvents|PropelObjectCollection $listEvents The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 EventTaggedPersonsQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByListEvents($listEvents, $comparison = null)
    {
        if ($listEvents instanceof ListEvents) {
            return $this
                ->addUsingAlias(EventTaggedPersonsPeer::EVENT_ID, $listEvents->getId(), $comparison);
        } elseif ($listEvents instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(EventTaggedPersonsPeer::EVENT_ID, $listEvents->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByListEvents() only accepts arguments of type ListEvents or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the ListEvents relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return EventTaggedPersonsQuery The current query, for fluid interface
     */
    public function joinListEvents($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('ListEvents');

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
            $this->addJoinObject($join, 'ListEvents');
        }

        return $this;
    }

    /**
     * Use the ListEvents relation ListEvents object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \CoreBundle\Model\ListEventsQuery A secondary query class using the current class as primary query
     */
    public function useListEventsQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinListEvents($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'ListEvents', '\CoreBundle\Model\ListEventsQuery');
    }

    /**
     * Filter the query by a related EventTagHistory object
     *
     * @param   EventTagHistory|PropelObjectCollection $eventTagHistory  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 EventTaggedPersonsQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByEventTagHistory($eventTagHistory, $comparison = null)
    {
        if ($eventTagHistory instanceof EventTagHistory) {
            return $this
                ->addUsingAlias(EventTaggedPersonsPeer::ID, $eventTagHistory->getEventTagId(), $comparison);
        } elseif ($eventTagHistory instanceof PropelObjectCollection) {
            return $this
                ->useEventTagHistoryQuery()
                ->filterByPrimaryKeys($eventTagHistory->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByEventTagHistory() only accepts arguments of type EventTagHistory or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the EventTagHistory relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return EventTaggedPersonsQuery The current query, for fluid interface
     */
    public function joinEventTagHistory($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('EventTagHistory');

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
            $this->addJoinObject($join, 'EventTagHistory');
        }

        return $this;
    }

    /**
     * Use the EventTagHistory relation EventTagHistory object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \CoreBundle\Model\EventTagHistoryQuery A secondary query class using the current class as primary query
     */
    public function useEventTagHistoryQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinEventTagHistory($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'EventTagHistory', '\CoreBundle\Model\EventTagHistoryQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   EventTaggedPersons $eventTaggedPersons Object to remove from the list of results
     *
     * @return EventTaggedPersonsQuery The current query, for fluid interface
     */
    public function prune($eventTaggedPersons = null)
    {
        if ($eventTaggedPersons) {
            $this->addUsingAlias(EventTaggedPersonsPeer::ID, $eventTaggedPersons->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

}
