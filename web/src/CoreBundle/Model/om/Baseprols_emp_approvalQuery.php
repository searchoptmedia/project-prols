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
use CoreBundle\Model\prols_emp_approval;
use CoreBundle\Model\prols_emp_approvalPeer;
use CoreBundle\Model\prols_emp_approvalQuery;

/**
 * @method prols_emp_approvalQuery orderById($order = Criteria::ASC) Order by the id column
 * @method prols_emp_approvalQuery orderByRequest($order = Criteria::ASC) Order by the request column
 * @method prols_emp_approvalQuery orderByStatus($order = Criteria::ASC) Order by the status column
 *
 * @method prols_emp_approvalQuery groupById() Group by the id column
 * @method prols_emp_approvalQuery groupByRequest() Group by the request column
 * @method prols_emp_approvalQuery groupByStatus() Group by the status column
 *
 * @method prols_emp_approvalQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method prols_emp_approvalQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method prols_emp_approvalQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method prols_emp_approval findOne(PropelPDO $con = null) Return the first prols_emp_approval matching the query
 * @method prols_emp_approval findOneOrCreate(PropelPDO $con = null) Return the first prols_emp_approval matching the query, or a new prols_emp_approval object populated from the query conditions when no match is found
 *
 * @method prols_emp_approval findOneByRequest(string $request) Return the first prols_emp_approval filtered by the request column
 * @method prols_emp_approval findOneByStatus(string $status) Return the first prols_emp_approval filtered by the status column
 *
 * @method array findById(int $id) Return prols_emp_approval objects filtered by the id column
 * @method array findByRequest(string $request) Return prols_emp_approval objects filtered by the request column
 * @method array findByStatus(string $status) Return prols_emp_approval objects filtered by the status column
 */
abstract class Baseprols_emp_approvalQuery extends ModelCriteria
{
    /**
     * Initializes internal state of Baseprols_emp_approvalQuery object.
     *
     * @param     string $dbName The dabase name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = null, $modelName = null, $modelAlias = null)
    {
        if (null === $dbName) {
            $dbName = 'prols';
        }
        if (null === $modelName) {
            $modelName = 'CoreBundle\\Model\\prols_emp_approval';
        }
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new prols_emp_approvalQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   prols_emp_approvalQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return prols_emp_approvalQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof prols_emp_approvalQuery) {
            return $criteria;
        }
        $query = new prols_emp_approvalQuery(null, null, $modelAlias);

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
     * @return   prols_emp_approval|prols_emp_approval[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = prols_emp_approvalPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(prols_emp_approvalPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 prols_emp_approval A model object, or null if the key is not found
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
     * @return                 prols_emp_approval A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id`, `request`, `status` FROM `emp_approval` WHERE `id` = :p0';
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
            $obj = new prols_emp_approval();
            $obj->hydrate($row);
            prols_emp_approvalPeer::addInstanceToPool($obj, (string) $key);
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
     * @return prols_emp_approval|prols_emp_approval[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|prols_emp_approval[]|mixed the list of results, formatted by the current formatter
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
     * @return prols_emp_approvalQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(prols_emp_approvalPeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return prols_emp_approvalQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(prols_emp_approvalPeer::ID, $keys, Criteria::IN);
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
     * @return prols_emp_approvalQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(prols_emp_approvalPeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(prols_emp_approvalPeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(prols_emp_approvalPeer::ID, $id, $comparison);
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
     * @return prols_emp_approvalQuery The current query, for fluid interface
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

        return $this->addUsingAlias(prols_emp_approvalPeer::REQUEST, $request, $comparison);
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
     * @return prols_emp_approvalQuery The current query, for fluid interface
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

        return $this->addUsingAlias(prols_emp_approvalPeer::STATUS, $status, $comparison);
    }

    /**
     * Exclude object from result
     *
     * @param   prols_emp_approval $prols_emp_approval Object to remove from the list of results
     *
     * @return prols_emp_approvalQuery The current query, for fluid interface
     */
    public function prune($prols_emp_approval = null)
    {
        if ($prols_emp_approval) {
            $this->addUsingAlias(prols_emp_approvalPeer::ID, $prols_emp_approval->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

}
