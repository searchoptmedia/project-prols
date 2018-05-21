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
use CoreBundle\Model\EventAttachment;
use CoreBundle\Model\EventNotes;
use CoreBundle\Model\EventTaggedPersons;
use CoreBundle\Model\ListEvents;
use CoreBundle\Model\ListEventsPeer;
use CoreBundle\Model\ListEventsQuery;
use CoreBundle\Model\ListEventsType;

/**
 * @method ListEventsQuery orderById($order = Criteria::ASC) Order by the id column
 * @method ListEventsQuery orderByCreatedBy($order = Criteria::ASC) Order by the created_by column
 * @method ListEventsQuery orderByDateCreated($order = Criteria::ASC) Order by the date_created column
 * @method ListEventsQuery orderByFromDate($order = Criteria::ASC) Order by the from_date column
 * @method ListEventsQuery orderByToDate($order = Criteria::ASC) Order by the to_date column
 * @method ListEventsQuery orderByEventName($order = Criteria::ASC) Order by the event_name column
 * @method ListEventsQuery orderByEventVenue($order = Criteria::ASC) Order by the event_venue column
 * @method ListEventsQuery orderByEventDescription($order = Criteria::ASC) Order by the event_desc column
 * @method ListEventsQuery orderByEventType($order = Criteria::ASC) Order by the event_type column
 * @method ListEventsQuery orderByStatus($order = Criteria::ASC) Order by the status column
 * @method ListEventsQuery orderByIsGoing($order = Criteria::ASC) Order by the is_going column
 * @method ListEventsQuery orderByIsGoingNote($order = Criteria::ASC) Order by the is_going_note column
 * @method ListEventsQuery orderBySmsResponse($order = Criteria::ASC) Order by the sms_response column
 *
 * @method ListEventsQuery groupById() Group by the id column
 * @method ListEventsQuery groupByCreatedBy() Group by the created_by column
 * @method ListEventsQuery groupByDateCreated() Group by the date_created column
 * @method ListEventsQuery groupByFromDate() Group by the from_date column
 * @method ListEventsQuery groupByToDate() Group by the to_date column
 * @method ListEventsQuery groupByEventName() Group by the event_name column
 * @method ListEventsQuery groupByEventVenue() Group by the event_venue column
 * @method ListEventsQuery groupByEventDescription() Group by the event_desc column
 * @method ListEventsQuery groupByEventType() Group by the event_type column
 * @method ListEventsQuery groupByStatus() Group by the status column
 * @method ListEventsQuery groupByIsGoing() Group by the is_going column
 * @method ListEventsQuery groupByIsGoingNote() Group by the is_going_note column
 * @method ListEventsQuery groupBySmsResponse() Group by the sms_response column
 *
 * @method ListEventsQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method ListEventsQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method ListEventsQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method ListEventsQuery leftJoinListEventsType($relationAlias = null) Adds a LEFT JOIN clause to the query using the ListEventsType relation
 * @method ListEventsQuery rightJoinListEventsType($relationAlias = null) Adds a RIGHT JOIN clause to the query using the ListEventsType relation
 * @method ListEventsQuery innerJoinListEventsType($relationAlias = null) Adds a INNER JOIN clause to the query using the ListEventsType relation
 *
 * @method ListEventsQuery leftJoinEmpAcc($relationAlias = null) Adds a LEFT JOIN clause to the query using the EmpAcc relation
 * @method ListEventsQuery rightJoinEmpAcc($relationAlias = null) Adds a RIGHT JOIN clause to the query using the EmpAcc relation
 * @method ListEventsQuery innerJoinEmpAcc($relationAlias = null) Adds a INNER JOIN clause to the query using the EmpAcc relation
 *
 * @method ListEventsQuery leftJoinEventNotes($relationAlias = null) Adds a LEFT JOIN clause to the query using the EventNotes relation
 * @method ListEventsQuery rightJoinEventNotes($relationAlias = null) Adds a RIGHT JOIN clause to the query using the EventNotes relation
 * @method ListEventsQuery innerJoinEventNotes($relationAlias = null) Adds a INNER JOIN clause to the query using the EventNotes relation
 *
 * @method ListEventsQuery leftJoinEventTaggedPersons($relationAlias = null) Adds a LEFT JOIN clause to the query using the EventTaggedPersons relation
 * @method ListEventsQuery rightJoinEventTaggedPersons($relationAlias = null) Adds a RIGHT JOIN clause to the query using the EventTaggedPersons relation
 * @method ListEventsQuery innerJoinEventTaggedPersons($relationAlias = null) Adds a INNER JOIN clause to the query using the EventTaggedPersons relation
 *
 * @method ListEventsQuery leftJoinEventAttachment($relationAlias = null) Adds a LEFT JOIN clause to the query using the EventAttachment relation
 * @method ListEventsQuery rightJoinEventAttachment($relationAlias = null) Adds a RIGHT JOIN clause to the query using the EventAttachment relation
 * @method ListEventsQuery innerJoinEventAttachment($relationAlias = null) Adds a INNER JOIN clause to the query using the EventAttachment relation
 *
 * @method ListEvents findOne(PropelPDO $con = null) Return the first ListEvents matching the query
 * @method ListEvents findOneOrCreate(PropelPDO $con = null) Return the first ListEvents matching the query, or a new ListEvents object populated from the query conditions when no match is found
 *
 * @method ListEvents findOneByCreatedBy(int $created_by) Return the first ListEvents filtered by the created_by column
 * @method ListEvents findOneByDateCreated(string $date_created) Return the first ListEvents filtered by the date_created column
 * @method ListEvents findOneByFromDate(string $from_date) Return the first ListEvents filtered by the from_date column
 * @method ListEvents findOneByToDate(string $to_date) Return the first ListEvents filtered by the to_date column
 * @method ListEvents findOneByEventName(string $event_name) Return the first ListEvents filtered by the event_name column
 * @method ListEvents findOneByEventVenue(string $event_venue) Return the first ListEvents filtered by the event_venue column
 * @method ListEvents findOneByEventDescription(string $event_desc) Return the first ListEvents filtered by the event_desc column
 * @method ListEvents findOneByEventType(int $event_type) Return the first ListEvents filtered by the event_type column
 * @method ListEvents findOneByStatus(int $status) Return the first ListEvents filtered by the status column
 * @method ListEvents findOneByIsGoing(int $is_going) Return the first ListEvents filtered by the is_going column
 * @method ListEvents findOneByIsGoingNote(string $is_going_note) Return the first ListEvents filtered by the is_going_note column
 * @method ListEvents findOneBySmsResponse(string $sms_response) Return the first ListEvents filtered by the sms_response column
 *
 * @method array findById(int $id) Return ListEvents objects filtered by the id column
 * @method array findByCreatedBy(int $created_by) Return ListEvents objects filtered by the created_by column
 * @method array findByDateCreated(string $date_created) Return ListEvents objects filtered by the date_created column
 * @method array findByFromDate(string $from_date) Return ListEvents objects filtered by the from_date column
 * @method array findByToDate(string $to_date) Return ListEvents objects filtered by the to_date column
 * @method array findByEventName(string $event_name) Return ListEvents objects filtered by the event_name column
 * @method array findByEventVenue(string $event_venue) Return ListEvents objects filtered by the event_venue column
 * @method array findByEventDescription(string $event_desc) Return ListEvents objects filtered by the event_desc column
 * @method array findByEventType(int $event_type) Return ListEvents objects filtered by the event_type column
 * @method array findByStatus(int $status) Return ListEvents objects filtered by the status column
 * @method array findByIsGoing(int $is_going) Return ListEvents objects filtered by the is_going column
 * @method array findByIsGoingNote(string $is_going_note) Return ListEvents objects filtered by the is_going_note column
 * @method array findBySmsResponse(string $sms_response) Return ListEvents objects filtered by the sms_response column
 */
abstract class BaseListEventsQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseListEventsQuery object.
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
            $modelName = 'CoreBundle\\Model\\ListEvents';
        }
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ListEventsQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   ListEventsQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return ListEventsQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof ListEventsQuery) {
            return $criteria;
        }
        $query = new ListEventsQuery(null, null, $modelAlias);

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
     * @return   ListEvents|ListEvents[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = ListEventsPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(ListEventsPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 ListEvents A model object, or null if the key is not found
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
     * @return                 ListEvents A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id`, `created_by`, `date_created`, `from_date`, `to_date`, `event_name`, `event_venue`, `event_desc`, `event_type`, `status`, `is_going`, `is_going_note`, `sms_response` FROM `list_events` WHERE `id` = :p0';
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
            $obj = new ListEvents();
            $obj->hydrate($row);
            ListEventsPeer::addInstanceToPool($obj, (string) $key);
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
     * @return ListEvents|ListEvents[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|ListEvents[]|mixed the list of results, formatted by the current formatter
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
     * @return ListEventsQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(ListEventsPeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return ListEventsQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(ListEventsPeer::ID, $keys, Criteria::IN);
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
     * @return ListEventsQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(ListEventsPeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(ListEventsPeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ListEventsPeer::ID, $id, $comparison);
    }

    /**
     * Filter the query on the created_by column
     *
     * Example usage:
     * <code>
     * $query->filterByCreatedBy(1234); // WHERE created_by = 1234
     * $query->filterByCreatedBy(array(12, 34)); // WHERE created_by IN (12, 34)
     * $query->filterByCreatedBy(array('min' => 12)); // WHERE created_by >= 12
     * $query->filterByCreatedBy(array('max' => 12)); // WHERE created_by <= 12
     * </code>
     *
     * @see       filterByEmpAcc()
     *
     * @param     mixed $createdBy The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ListEventsQuery The current query, for fluid interface
     */
    public function filterByCreatedBy($createdBy = null, $comparison = null)
    {
        if (is_array($createdBy)) {
            $useMinMax = false;
            if (isset($createdBy['min'])) {
                $this->addUsingAlias(ListEventsPeer::CREATED_BY, $createdBy['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdBy['max'])) {
                $this->addUsingAlias(ListEventsPeer::CREATED_BY, $createdBy['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ListEventsPeer::CREATED_BY, $createdBy, $comparison);
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
     * @return ListEventsQuery The current query, for fluid interface
     */
    public function filterByDateCreated($dateCreated = null, $comparison = null)
    {
        if (is_array($dateCreated)) {
            $useMinMax = false;
            if (isset($dateCreated['min'])) {
                $this->addUsingAlias(ListEventsPeer::DATE_CREATED, $dateCreated['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($dateCreated['max'])) {
                $this->addUsingAlias(ListEventsPeer::DATE_CREATED, $dateCreated['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ListEventsPeer::DATE_CREATED, $dateCreated, $comparison);
    }

    /**
     * Filter the query on the from_date column
     *
     * Example usage:
     * <code>
     * $query->filterByFromDate('2011-03-14'); // WHERE from_date = '2011-03-14'
     * $query->filterByFromDate('now'); // WHERE from_date = '2011-03-14'
     * $query->filterByFromDate(array('max' => 'yesterday')); // WHERE from_date < '2011-03-13'
     * </code>
     *
     * @param     mixed $fromDate The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ListEventsQuery The current query, for fluid interface
     */
    public function filterByFromDate($fromDate = null, $comparison = null)
    {
        if (is_array($fromDate)) {
            $useMinMax = false;
            if (isset($fromDate['min'])) {
                $this->addUsingAlias(ListEventsPeer::FROM_DATE, $fromDate['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($fromDate['max'])) {
                $this->addUsingAlias(ListEventsPeer::FROM_DATE, $fromDate['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ListEventsPeer::FROM_DATE, $fromDate, $comparison);
    }

    /**
     * Filter the query on the to_date column
     *
     * Example usage:
     * <code>
     * $query->filterByToDate('2011-03-14'); // WHERE to_date = '2011-03-14'
     * $query->filterByToDate('now'); // WHERE to_date = '2011-03-14'
     * $query->filterByToDate(array('max' => 'yesterday')); // WHERE to_date < '2011-03-13'
     * </code>
     *
     * @param     mixed $toDate The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ListEventsQuery The current query, for fluid interface
     */
    public function filterByToDate($toDate = null, $comparison = null)
    {
        if (is_array($toDate)) {
            $useMinMax = false;
            if (isset($toDate['min'])) {
                $this->addUsingAlias(ListEventsPeer::TO_DATE, $toDate['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($toDate['max'])) {
                $this->addUsingAlias(ListEventsPeer::TO_DATE, $toDate['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ListEventsPeer::TO_DATE, $toDate, $comparison);
    }

    /**
     * Filter the query on the event_name column
     *
     * Example usage:
     * <code>
     * $query->filterByEventName('fooValue');   // WHERE event_name = 'fooValue'
     * $query->filterByEventName('%fooValue%'); // WHERE event_name LIKE '%fooValue%'
     * </code>
     *
     * @param     string $eventName The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ListEventsQuery The current query, for fluid interface
     */
    public function filterByEventName($eventName = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($eventName)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $eventName)) {
                $eventName = str_replace('*', '%', $eventName);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(ListEventsPeer::EVENT_NAME, $eventName, $comparison);
    }

    /**
     * Filter the query on the event_venue column
     *
     * Example usage:
     * <code>
     * $query->filterByEventVenue('fooValue');   // WHERE event_venue = 'fooValue'
     * $query->filterByEventVenue('%fooValue%'); // WHERE event_venue LIKE '%fooValue%'
     * </code>
     *
     * @param     string $eventVenue The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ListEventsQuery The current query, for fluid interface
     */
    public function filterByEventVenue($eventVenue = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($eventVenue)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $eventVenue)) {
                $eventVenue = str_replace('*', '%', $eventVenue);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(ListEventsPeer::EVENT_VENUE, $eventVenue, $comparison);
    }

    /**
     * Filter the query on the event_desc column
     *
     * Example usage:
     * <code>
     * $query->filterByEventDescription('fooValue');   // WHERE event_desc = 'fooValue'
     * $query->filterByEventDescription('%fooValue%'); // WHERE event_desc LIKE '%fooValue%'
     * </code>
     *
     * @param     string $eventDescription The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ListEventsQuery The current query, for fluid interface
     */
    public function filterByEventDescription($eventDescription = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($eventDescription)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $eventDescription)) {
                $eventDescription = str_replace('*', '%', $eventDescription);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(ListEventsPeer::EVENT_DESC, $eventDescription, $comparison);
    }

    /**
     * Filter the query on the event_type column
     *
     * Example usage:
     * <code>
     * $query->filterByEventType(1234); // WHERE event_type = 1234
     * $query->filterByEventType(array(12, 34)); // WHERE event_type IN (12, 34)
     * $query->filterByEventType(array('min' => 12)); // WHERE event_type >= 12
     * $query->filterByEventType(array('max' => 12)); // WHERE event_type <= 12
     * </code>
     *
     * @see       filterByListEventsType()
     *
     * @param     mixed $eventType The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ListEventsQuery The current query, for fluid interface
     */
    public function filterByEventType($eventType = null, $comparison = null)
    {
        if (is_array($eventType)) {
            $useMinMax = false;
            if (isset($eventType['min'])) {
                $this->addUsingAlias(ListEventsPeer::EVENT_TYPE, $eventType['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($eventType['max'])) {
                $this->addUsingAlias(ListEventsPeer::EVENT_TYPE, $eventType['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ListEventsPeer::EVENT_TYPE, $eventType, $comparison);
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
     * @return ListEventsQuery The current query, for fluid interface
     */
    public function filterByStatus($status = null, $comparison = null)
    {
        if (is_array($status)) {
            $useMinMax = false;
            if (isset($status['min'])) {
                $this->addUsingAlias(ListEventsPeer::STATUS, $status['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($status['max'])) {
                $this->addUsingAlias(ListEventsPeer::STATUS, $status['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ListEventsPeer::STATUS, $status, $comparison);
    }

    /**
     * Filter the query on the is_going column
     *
     * Example usage:
     * <code>
     * $query->filterByIsGoing(1234); // WHERE is_going = 1234
     * $query->filterByIsGoing(array(12, 34)); // WHERE is_going IN (12, 34)
     * $query->filterByIsGoing(array('min' => 12)); // WHERE is_going >= 12
     * $query->filterByIsGoing(array('max' => 12)); // WHERE is_going <= 12
     * </code>
     *
     * @param     mixed $isGoing The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ListEventsQuery The current query, for fluid interface
     */
    public function filterByIsGoing($isGoing = null, $comparison = null)
    {
        if (is_array($isGoing)) {
            $useMinMax = false;
            if (isset($isGoing['min'])) {
                $this->addUsingAlias(ListEventsPeer::IS_GOING, $isGoing['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($isGoing['max'])) {
                $this->addUsingAlias(ListEventsPeer::IS_GOING, $isGoing['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ListEventsPeer::IS_GOING, $isGoing, $comparison);
    }

    /**
     * Filter the query on the is_going_note column
     *
     * Example usage:
     * <code>
     * $query->filterByIsGoingNote('fooValue');   // WHERE is_going_note = 'fooValue'
     * $query->filterByIsGoingNote('%fooValue%'); // WHERE is_going_note LIKE '%fooValue%'
     * </code>
     *
     * @param     string $isGoingNote The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ListEventsQuery The current query, for fluid interface
     */
    public function filterByIsGoingNote($isGoingNote = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($isGoingNote)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $isGoingNote)) {
                $isGoingNote = str_replace('*', '%', $isGoingNote);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(ListEventsPeer::IS_GOING_NOTE, $isGoingNote, $comparison);
    }

    /**
     * Filter the query on the sms_response column
     *
     * Example usage:
     * <code>
     * $query->filterBySmsResponse('fooValue');   // WHERE sms_response = 'fooValue'
     * $query->filterBySmsResponse('%fooValue%'); // WHERE sms_response LIKE '%fooValue%'
     * </code>
     *
     * @param     string $smsResponse The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ListEventsQuery The current query, for fluid interface
     */
    public function filterBySmsResponse($smsResponse = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($smsResponse)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $smsResponse)) {
                $smsResponse = str_replace('*', '%', $smsResponse);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(ListEventsPeer::SMS_RESPONSE, $smsResponse, $comparison);
    }

    /**
     * Filter the query by a related ListEventsType object
     *
     * @param   ListEventsType|PropelObjectCollection $listEventsType The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 ListEventsQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByListEventsType($listEventsType, $comparison = null)
    {
        if ($listEventsType instanceof ListEventsType) {
            return $this
                ->addUsingAlias(ListEventsPeer::EVENT_TYPE, $listEventsType->getId(), $comparison);
        } elseif ($listEventsType instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(ListEventsPeer::EVENT_TYPE, $listEventsType->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByListEventsType() only accepts arguments of type ListEventsType or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the ListEventsType relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return ListEventsQuery The current query, for fluid interface
     */
    public function joinListEventsType($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('ListEventsType');

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
            $this->addJoinObject($join, 'ListEventsType');
        }

        return $this;
    }

    /**
     * Use the ListEventsType relation ListEventsType object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \CoreBundle\Model\ListEventsTypeQuery A secondary query class using the current class as primary query
     */
    public function useListEventsTypeQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinListEventsType($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'ListEventsType', '\CoreBundle\Model\ListEventsTypeQuery');
    }

    /**
     * Filter the query by a related EmpAcc object
     *
     * @param   EmpAcc|PropelObjectCollection $empAcc The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 ListEventsQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByEmpAcc($empAcc, $comparison = null)
    {
        if ($empAcc instanceof EmpAcc) {
            return $this
                ->addUsingAlias(ListEventsPeer::CREATED_BY, $empAcc->getId(), $comparison);
        } elseif ($empAcc instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(ListEventsPeer::CREATED_BY, $empAcc->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return ListEventsQuery The current query, for fluid interface
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
     * Filter the query by a related EventNotes object
     *
     * @param   EventNotes|PropelObjectCollection $eventNotes  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 ListEventsQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByEventNotes($eventNotes, $comparison = null)
    {
        if ($eventNotes instanceof EventNotes) {
            return $this
                ->addUsingAlias(ListEventsPeer::ID, $eventNotes->getEventId(), $comparison);
        } elseif ($eventNotes instanceof PropelObjectCollection) {
            return $this
                ->useEventNotesQuery()
                ->filterByPrimaryKeys($eventNotes->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByEventNotes() only accepts arguments of type EventNotes or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the EventNotes relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return ListEventsQuery The current query, for fluid interface
     */
    public function joinEventNotes($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('EventNotes');

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
            $this->addJoinObject($join, 'EventNotes');
        }

        return $this;
    }

    /**
     * Use the EventNotes relation EventNotes object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \CoreBundle\Model\EventNotesQuery A secondary query class using the current class as primary query
     */
    public function useEventNotesQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinEventNotes($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'EventNotes', '\CoreBundle\Model\EventNotesQuery');
    }

    /**
     * Filter the query by a related EventTaggedPersons object
     *
     * @param   EventTaggedPersons|PropelObjectCollection $eventTaggedPersons  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 ListEventsQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByEventTaggedPersons($eventTaggedPersons, $comparison = null)
    {
        if ($eventTaggedPersons instanceof EventTaggedPersons) {
            return $this
                ->addUsingAlias(ListEventsPeer::ID, $eventTaggedPersons->getEventId(), $comparison);
        } elseif ($eventTaggedPersons instanceof PropelObjectCollection) {
            return $this
                ->useEventTaggedPersonsQuery()
                ->filterByPrimaryKeys($eventTaggedPersons->getPrimaryKeys())
                ->endUse();
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
     * @return ListEventsQuery The current query, for fluid interface
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
     * Filter the query by a related EventAttachment object
     *
     * @param   EventAttachment|PropelObjectCollection $eventAttachment  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 ListEventsQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByEventAttachment($eventAttachment, $comparison = null)
    {
        if ($eventAttachment instanceof EventAttachment) {
            return $this
                ->addUsingAlias(ListEventsPeer::ID, $eventAttachment->getEventId(), $comparison);
        } elseif ($eventAttachment instanceof PropelObjectCollection) {
            return $this
                ->useEventAttachmentQuery()
                ->filterByPrimaryKeys($eventAttachment->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByEventAttachment() only accepts arguments of type EventAttachment or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the EventAttachment relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return ListEventsQuery The current query, for fluid interface
     */
    public function joinEventAttachment($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('EventAttachment');

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
            $this->addJoinObject($join, 'EventAttachment');
        }

        return $this;
    }

    /**
     * Use the EventAttachment relation EventAttachment object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \CoreBundle\Model\EventAttachmentQuery A secondary query class using the current class as primary query
     */
    public function useEventAttachmentQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinEventAttachment($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'EventAttachment', '\CoreBundle\Model\EventAttachmentQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ListEvents $listEvents Object to remove from the list of results
     *
     * @return ListEventsQuery The current query, for fluid interface
     */
    public function prune($listEvents = null)
    {
        if ($listEvents) {
            $this->addUsingAlias(ListEventsPeer::ID, $listEvents->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

}
