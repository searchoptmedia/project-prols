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
use CoreBundle\Model\EventTagHistory;
use CoreBundle\Model\EventTagHistoryPeer;
use CoreBundle\Model\EventTagHistoryQuery;
use CoreBundle\Model\EventTaggedPersons;
use CoreBundle\Model\History;

/**
 * @method EventTagHistoryQuery orderById($order = Criteria::ASC) Order by the id column
 * @method EventTagHistoryQuery orderByHistoryId($order = Criteria::ASC) Order by the history_id column
 * @method EventTagHistoryQuery orderByEventTagId($order = Criteria::ASC) Order by the event_tag_id column
 * @method EventTagHistoryQuery orderByStatus($order = Criteria::ASC) Order by the status column
 * @method EventTagHistoryQuery orderByMessage($order = Criteria::ASC) Order by the message column
 * @method EventTagHistoryQuery orderByDateCreated($order = Criteria::ASC) Order by the date_created column
 *
 * @method EventTagHistoryQuery groupById() Group by the id column
 * @method EventTagHistoryQuery groupByHistoryId() Group by the history_id column
 * @method EventTagHistoryQuery groupByEventTagId() Group by the event_tag_id column
 * @method EventTagHistoryQuery groupByStatus() Group by the status column
 * @method EventTagHistoryQuery groupByMessage() Group by the message column
 * @method EventTagHistoryQuery groupByDateCreated() Group by the date_created column
 *
 * @method EventTagHistoryQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method EventTagHistoryQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method EventTagHistoryQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method EventTagHistoryQuery leftJoinHistory($relationAlias = null) Adds a LEFT JOIN clause to the query using the History relation
 * @method EventTagHistoryQuery rightJoinHistory($relationAlias = null) Adds a RIGHT JOIN clause to the query using the History relation
 * @method EventTagHistoryQuery innerJoinHistory($relationAlias = null) Adds a INNER JOIN clause to the query using the History relation
 *
 * @method EventTagHistoryQuery leftJoinEventTaggedPersons($relationAlias = null) Adds a LEFT JOIN clause to the query using the EventTaggedPersons relation
 * @method EventTagHistoryQuery rightJoinEventTaggedPersons($relationAlias = null) Adds a RIGHT JOIN clause to the query using the EventTaggedPersons relation
 * @method EventTagHistoryQuery innerJoinEventTaggedPersons($relationAlias = null) Adds a INNER JOIN clause to the query using the EventTaggedPersons relation
 *
 * @method EventTagHistory findOne(PropelPDO $con = null) Return the first EventTagHistory matching the query
 * @method EventTagHistory findOneOrCreate(PropelPDO $con = null) Return the first EventTagHistory matching the query, or a new EventTagHistory object populated from the query conditions when no match is found
 *
 * @method EventTagHistory findOneByHistoryId(int $history_id) Return the first EventTagHistory filtered by the history_id column
 * @method EventTagHistory findOneByEventTagId(int $event_tag_id) Return the first EventTagHistory filtered by the event_tag_id column
 * @method EventTagHistory findOneByStatus(int $status) Return the first EventTagHistory filtered by the status column
 * @method EventTagHistory findOneByMessage(string $message) Return the first EventTagHistory filtered by the message column
 * @method EventTagHistory findOneByDateCreated(string $date_created) Return the first EventTagHistory filtered by the date_created column
 *
 * @method array findById(int $id) Return EventTagHistory objects filtered by the id column
 * @method array findByHistoryId(int $history_id) Return EventTagHistory objects filtered by the history_id column
 * @method array findByEventTagId(int $event_tag_id) Return EventTagHistory objects filtered by the event_tag_id column
 * @method array findByStatus(int $status) Return EventTagHistory objects filtered by the status column
 * @method array findByMessage(string $message) Return EventTagHistory objects filtered by the message column
 * @method array findByDateCreated(string $date_created) Return EventTagHistory objects filtered by the date_created column
 */
abstract class BaseEventTagHistoryQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseEventTagHistoryQuery object.
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
            $modelName = 'CoreBundle\\Model\\EventTagHistory';
        }
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new EventTagHistoryQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   EventTagHistoryQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return EventTagHistoryQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof EventTagHistoryQuery) {
            return $criteria;
        }
        $query = new EventTagHistoryQuery(null, null, $modelAlias);

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
     * @return   EventTagHistory|EventTagHistory[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = EventTagHistoryPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(EventTagHistoryPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 EventTagHistory A model object, or null if the key is not found
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
     * @return                 EventTagHistory A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id`, `history_id`, `event_tag_id`, `status`, `message`, `date_created` FROM `event_tag_history` WHERE `id` = :p0';
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
            $obj = new EventTagHistory();
            $obj->hydrate($row);
            EventTagHistoryPeer::addInstanceToPool($obj, (string) $key);
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
     * @return EventTagHistory|EventTagHistory[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|EventTagHistory[]|mixed the list of results, formatted by the current formatter
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
     * @return EventTagHistoryQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(EventTagHistoryPeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return EventTagHistoryQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(EventTagHistoryPeer::ID, $keys, Criteria::IN);
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
     * @return EventTagHistoryQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(EventTagHistoryPeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(EventTagHistoryPeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EventTagHistoryPeer::ID, $id, $comparison);
    }

    /**
     * Filter the query on the history_id column
     *
     * Example usage:
     * <code>
     * $query->filterByHistoryId(1234); // WHERE history_id = 1234
     * $query->filterByHistoryId(array(12, 34)); // WHERE history_id IN (12, 34)
     * $query->filterByHistoryId(array('min' => 12)); // WHERE history_id >= 12
     * $query->filterByHistoryId(array('max' => 12)); // WHERE history_id <= 12
     * </code>
     *
     * @see       filterByHistory()
     *
     * @param     mixed $historyId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return EventTagHistoryQuery The current query, for fluid interface
     */
    public function filterByHistoryId($historyId = null, $comparison = null)
    {
        if (is_array($historyId)) {
            $useMinMax = false;
            if (isset($historyId['min'])) {
                $this->addUsingAlias(EventTagHistoryPeer::HISTORY_ID, $historyId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($historyId['max'])) {
                $this->addUsingAlias(EventTagHistoryPeer::HISTORY_ID, $historyId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EventTagHistoryPeer::HISTORY_ID, $historyId, $comparison);
    }

    /**
     * Filter the query on the event_tag_id column
     *
     * Example usage:
     * <code>
     * $query->filterByEventTagId(1234); // WHERE event_tag_id = 1234
     * $query->filterByEventTagId(array(12, 34)); // WHERE event_tag_id IN (12, 34)
     * $query->filterByEventTagId(array('min' => 12)); // WHERE event_tag_id >= 12
     * $query->filterByEventTagId(array('max' => 12)); // WHERE event_tag_id <= 12
     * </code>
     *
     * @see       filterByEventTaggedPersons()
     *
     * @param     mixed $eventTagId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return EventTagHistoryQuery The current query, for fluid interface
     */
    public function filterByEventTagId($eventTagId = null, $comparison = null)
    {
        if (is_array($eventTagId)) {
            $useMinMax = false;
            if (isset($eventTagId['min'])) {
                $this->addUsingAlias(EventTagHistoryPeer::EVENT_TAG_ID, $eventTagId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($eventTagId['max'])) {
                $this->addUsingAlias(EventTagHistoryPeer::EVENT_TAG_ID, $eventTagId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EventTagHistoryPeer::EVENT_TAG_ID, $eventTagId, $comparison);
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
     * @return EventTagHistoryQuery The current query, for fluid interface
     */
    public function filterByStatus($status = null, $comparison = null)
    {
        if (is_array($status)) {
            $useMinMax = false;
            if (isset($status['min'])) {
                $this->addUsingAlias(EventTagHistoryPeer::STATUS, $status['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($status['max'])) {
                $this->addUsingAlias(EventTagHistoryPeer::STATUS, $status['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EventTagHistoryPeer::STATUS, $status, $comparison);
    }

    /**
     * Filter the query on the message column
     *
     * Example usage:
     * <code>
     * $query->filterByMessage('fooValue');   // WHERE message = 'fooValue'
     * $query->filterByMessage('%fooValue%'); // WHERE message LIKE '%fooValue%'
     * </code>
     *
     * @param     string $message The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return EventTagHistoryQuery The current query, for fluid interface
     */
    public function filterByMessage($message = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($message)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $message)) {
                $message = str_replace('*', '%', $message);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(EventTagHistoryPeer::MESSAGE, $message, $comparison);
    }

    /**
     * Filter the query on the date_created column
     *
     * Example usage:
     * <code>
     * $query->filterByDateCreated('2011-03-14'); // WHERE date_created = '2011-03-14'
     * $query->filterByDateCreated('now'); // WHERE date_created = '2011-03-14'
     * $query->filterByDateCreated(array('max' => 'yesterday')); // WHERE date_created < '2011-03-13'
     * </code>
     *
     * @param     mixed $dateCreated The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return EventTagHistoryQuery The current query, for fluid interface
     */
    public function filterByDateCreated($dateCreated = null, $comparison = null)
    {
        if (is_array($dateCreated)) {
            $useMinMax = false;
            if (isset($dateCreated['min'])) {
                $this->addUsingAlias(EventTagHistoryPeer::DATE_CREATED, $dateCreated['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($dateCreated['max'])) {
                $this->addUsingAlias(EventTagHistoryPeer::DATE_CREATED, $dateCreated['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EventTagHistoryPeer::DATE_CREATED, $dateCreated, $comparison);
    }

    /**
     * Filter the query by a related History object
     *
     * @param   History|PropelObjectCollection $history The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 EventTagHistoryQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByHistory($history, $comparison = null)
    {
        if ($history instanceof History) {
            return $this
                ->addUsingAlias(EventTagHistoryPeer::HISTORY_ID, $history->getId(), $comparison);
        } elseif ($history instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(EventTagHistoryPeer::HISTORY_ID, $history->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByHistory() only accepts arguments of type History or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the History relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return EventTagHistoryQuery The current query, for fluid interface
     */
    public function joinHistory($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('History');

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
            $this->addJoinObject($join, 'History');
        }

        return $this;
    }

    /**
     * Use the History relation History object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \CoreBundle\Model\HistoryQuery A secondary query class using the current class as primary query
     */
    public function useHistoryQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinHistory($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'History', '\CoreBundle\Model\HistoryQuery');
    }

    /**
     * Filter the query by a related EventTaggedPersons object
     *
     * @param   EventTaggedPersons|PropelObjectCollection $eventTaggedPersons The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 EventTagHistoryQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByEventTaggedPersons($eventTaggedPersons, $comparison = null)
    {
        if ($eventTaggedPersons instanceof EventTaggedPersons) {
            return $this
                ->addUsingAlias(EventTagHistoryPeer::EVENT_TAG_ID, $eventTaggedPersons->getId(), $comparison);
        } elseif ($eventTaggedPersons instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(EventTagHistoryPeer::EVENT_TAG_ID, $eventTaggedPersons->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByEventTaggedPersons() only accepts arguments of type EventTaggedPersons or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the EventTaggedPersons relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return EventTagHistoryQuery The current query, for fluid interface
     */
    public function joinEventTaggedPersons($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('EventTaggedPersons');

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
            $this->addJoinObject($join, 'EventTaggedPersons');
        }

        return $this;
    }

    /**
     * Use the EventTaggedPersons relation EventTaggedPersons object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \CoreBundle\Model\EventTaggedPersonsQuery A secondary query class using the current class as primary query
     */
    public function useEventTaggedPersonsQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinEventTaggedPersons($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'EventTaggedPersons', '\CoreBundle\Model\EventTaggedPersonsQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   EventTagHistory $eventTagHistory Object to remove from the list of results
     *
     * @return EventTagHistoryQuery The current query, for fluid interface
     */
    public function prune($eventTagHistory = null)
    {
        if ($eventTagHistory) {
            $this->addUsingAlias(EventTagHistoryPeer::ID, $eventTagHistory->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

}
