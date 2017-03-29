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
use CoreBundle\Model\ListIp;
use CoreBundle\Model\ListIpPeer;
use CoreBundle\Model\ListIpQuery;

/**
 * @method ListIpQuery orderById($order = Criteria::ASC) Order by the id column
 * @method ListIpQuery orderByAllowedIp($order = Criteria::ASC) Order by the allowed_ip column
 * @method ListIpQuery orderByStatus($order = Criteria::ASC) Order by the status column
 *
 * @method ListIpQuery groupById() Group by the id column
 * @method ListIpQuery groupByAllowedIp() Group by the allowed_ip column
 * @method ListIpQuery groupByStatus() Group by the status column
 *
 * @method ListIpQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method ListIpQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method ListIpQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method ListIp findOne(PropelPDO $con = null) Return the first ListIp matching the query
 * @method ListIp findOneOrCreate(PropelPDO $con = null) Return the first ListIp matching the query, or a new ListIp object populated from the query conditions when no match is found
 *
 * @method ListIp findOneByAllowedIp(string $allowed_ip) Return the first ListIp filtered by the allowed_ip column
 * @method ListIp findOneByStatus(int $status) Return the first ListIp filtered by the status column
 *
 * @method array findById(int $id) Return ListIp objects filtered by the id column
 * @method array findByAllowedIp(string $allowed_ip) Return ListIp objects filtered by the allowed_ip column
 * @method array findByStatus(int $status) Return ListIp objects filtered by the status column
 */
abstract class BaseListIpQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseListIpQuery object.
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
            $modelName = 'CoreBundle\\Model\\ListIp';
        }
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ListIpQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   ListIpQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return ListIpQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof ListIpQuery) {
            return $criteria;
        }
        $query = new ListIpQuery(null, null, $modelAlias);

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
     * @return   ListIp|ListIp[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = ListIpPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(ListIpPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 ListIp A model object, or null if the key is not found
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
     * @return                 ListIp A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id`, `allowed_ip`, `status` FROM `list_ip` WHERE `id` = :p0';
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
            $obj = new ListIp();
            $obj->hydrate($row);
            ListIpPeer::addInstanceToPool($obj, (string) $key);
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
     * @return ListIp|ListIp[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|ListIp[]|mixed the list of results, formatted by the current formatter
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
     * @return ListIpQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(ListIpPeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return ListIpQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(ListIpPeer::ID, $keys, Criteria::IN);
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
     * @return ListIpQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(ListIpPeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(ListIpPeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ListIpPeer::ID, $id, $comparison);
    }

    /**
     * Filter the query on the allowed_ip column
     *
     * Example usage:
     * <code>
     * $query->filterByAllowedIp('fooValue');   // WHERE allowed_ip = 'fooValue'
     * $query->filterByAllowedIp('%fooValue%'); // WHERE allowed_ip LIKE '%fooValue%'
     * </code>
     *
     * @param     string $allowedIp The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ListIpQuery The current query, for fluid interface
     */
    public function filterByAllowedIp($allowedIp = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($allowedIp)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $allowedIp)) {
                $allowedIp = str_replace('*', '%', $allowedIp);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(ListIpPeer::ALLOWED_IP, $allowedIp, $comparison);
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
     * @return ListIpQuery The current query, for fluid interface
     */
    public function filterByStatus($status = null, $comparison = null)
    {
        if (is_array($status)) {
            $useMinMax = false;
            if (isset($status['min'])) {
                $this->addUsingAlias(ListIpPeer::STATUS, $status['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($status['max'])) {
                $this->addUsingAlias(ListIpPeer::STATUS, $status['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ListIpPeer::STATUS, $status, $comparison);
    }

    /**
     * Exclude object from result
     *
     * @param   ListIp $listIp Object to remove from the list of results
     *
     * @return ListIpQuery The current query, for fluid interface
     */
    public function prune($listIp = null)
    {
        if ($listIp) {
            $this->addUsingAlias(ListIpPeer::ID, $listIp->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

}
