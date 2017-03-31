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
use CoreBundle\Model\EmpAccPeer;
use CoreBundle\Model\EmpAccQuery;
use CoreBundle\Model\EmpCapabilities;
use CoreBundle\Model\EmpProfile;
use CoreBundle\Model\EmpRequest;
use CoreBundle\Model\EmpTime;
use CoreBundle\Model\EventNotes;
use CoreBundle\Model\EventTaggedPersons;
use CoreBundle\Model\ListEvents;

/**
 * @method EmpAccQuery orderById($order = Criteria::ASC) Order by the id column
 * @method EmpAccQuery orderByUsername($order = Criteria::ASC) Order by the username column
 * @method EmpAccQuery orderByPassword($order = Criteria::ASC) Order by the password column
 * @method EmpAccQuery orderByTimestamp($order = Criteria::ASC) Order by the timestamp column
 * @method EmpAccQuery orderByIpAdd($order = Criteria::ASC) Order by the ip_add column
 * @method EmpAccQuery orderByStatus($order = Criteria::ASC) Order by the status column
 * @method EmpAccQuery orderByEmail($order = Criteria::ASC) Order by the email column
 * @method EmpAccQuery orderByRole($order = Criteria::ASC) Order by the role column
 * @method EmpAccQuery orderByKey($order = Criteria::ASC) Order by the key column
 * @method EmpAccQuery orderByCreatedBy($order = Criteria::ASC) Order by the created_by column
 * @method EmpAccQuery orderByLastUpdatedBy($order = Criteria::ASC) Order by the last_updated_by column
 *
 * @method EmpAccQuery groupById() Group by the id column
 * @method EmpAccQuery groupByUsername() Group by the username column
 * @method EmpAccQuery groupByPassword() Group by the password column
 * @method EmpAccQuery groupByTimestamp() Group by the timestamp column
 * @method EmpAccQuery groupByIpAdd() Group by the ip_add column
 * @method EmpAccQuery groupByStatus() Group by the status column
 * @method EmpAccQuery groupByEmail() Group by the email column
 * @method EmpAccQuery groupByRole() Group by the role column
 * @method EmpAccQuery groupByKey() Group by the key column
 * @method EmpAccQuery groupByCreatedBy() Group by the created_by column
 * @method EmpAccQuery groupByLastUpdatedBy() Group by the last_updated_by column
 *
 * @method EmpAccQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method EmpAccQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method EmpAccQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method EmpAccQuery leftJoinEmpRequestRelatedByEmpAccId($relationAlias = null) Adds a LEFT JOIN clause to the query using the EmpRequestRelatedByEmpAccId relation
 * @method EmpAccQuery rightJoinEmpRequestRelatedByEmpAccId($relationAlias = null) Adds a RIGHT JOIN clause to the query using the EmpRequestRelatedByEmpAccId relation
 * @method EmpAccQuery innerJoinEmpRequestRelatedByEmpAccId($relationAlias = null) Adds a INNER JOIN clause to the query using the EmpRequestRelatedByEmpAccId relation
 *
 * @method EmpAccQuery leftJoinEmpRequestRelatedByAdminId($relationAlias = null) Adds a LEFT JOIN clause to the query using the EmpRequestRelatedByAdminId relation
 * @method EmpAccQuery rightJoinEmpRequestRelatedByAdminId($relationAlias = null) Adds a RIGHT JOIN clause to the query using the EmpRequestRelatedByAdminId relation
 * @method EmpAccQuery innerJoinEmpRequestRelatedByAdminId($relationAlias = null) Adds a INNER JOIN clause to the query using the EmpRequestRelatedByAdminId relation
 *
 * @method EmpAccQuery leftJoinEmpProfile($relationAlias = null) Adds a LEFT JOIN clause to the query using the EmpProfile relation
 * @method EmpAccQuery rightJoinEmpProfile($relationAlias = null) Adds a RIGHT JOIN clause to the query using the EmpProfile relation
 * @method EmpAccQuery innerJoinEmpProfile($relationAlias = null) Adds a INNER JOIN clause to the query using the EmpProfile relation
 *
 * @method EmpAccQuery leftJoinEmpTime($relationAlias = null) Adds a LEFT JOIN clause to the query using the EmpTime relation
 * @method EmpAccQuery rightJoinEmpTime($relationAlias = null) Adds a RIGHT JOIN clause to the query using the EmpTime relation
 * @method EmpAccQuery innerJoinEmpTime($relationAlias = null) Adds a INNER JOIN clause to the query using the EmpTime relation
 *
 * @method EmpAccQuery leftJoinEmpCapabilities($relationAlias = null) Adds a LEFT JOIN clause to the query using the EmpCapabilities relation
 * @method EmpAccQuery rightJoinEmpCapabilities($relationAlias = null) Adds a RIGHT JOIN clause to the query using the EmpCapabilities relation
 * @method EmpAccQuery innerJoinEmpCapabilities($relationAlias = null) Adds a INNER JOIN clause to the query using the EmpCapabilities relation
 *
 * @method EmpAccQuery leftJoinListEvents($relationAlias = null) Adds a LEFT JOIN clause to the query using the ListEvents relation
 * @method EmpAccQuery rightJoinListEvents($relationAlias = null) Adds a RIGHT JOIN clause to the query using the ListEvents relation
 * @method EmpAccQuery innerJoinListEvents($relationAlias = null) Adds a INNER JOIN clause to the query using the ListEvents relation
 *
 * @method EmpAccQuery leftJoinEventNotes($relationAlias = null) Adds a LEFT JOIN clause to the query using the EventNotes relation
 * @method EmpAccQuery rightJoinEventNotes($relationAlias = null) Adds a RIGHT JOIN clause to the query using the EventNotes relation
 * @method EmpAccQuery innerJoinEventNotes($relationAlias = null) Adds a INNER JOIN clause to the query using the EventNotes relation
 *
 * @method EmpAccQuery leftJoinEventTaggedPersons($relationAlias = null) Adds a LEFT JOIN clause to the query using the EventTaggedPersons relation
 * @method EmpAccQuery rightJoinEventTaggedPersons($relationAlias = null) Adds a RIGHT JOIN clause to the query using the EventTaggedPersons relation
 * @method EmpAccQuery innerJoinEventTaggedPersons($relationAlias = null) Adds a INNER JOIN clause to the query using the EventTaggedPersons relation
 *
 * @method EmpAcc findOne(PropelPDO $con = null) Return the first EmpAcc matching the query
 * @method EmpAcc findOneOrCreate(PropelPDO $con = null) Return the first EmpAcc matching the query, or a new EmpAcc object populated from the query conditions when no match is found
 *
 * @method EmpAcc findOneByUsername(string $username) Return the first EmpAcc filtered by the username column
 * @method EmpAcc findOneByPassword(string $password) Return the first EmpAcc filtered by the password column
 * @method EmpAcc findOneByTimestamp(string $timestamp) Return the first EmpAcc filtered by the timestamp column
 * @method EmpAcc findOneByIpAdd(string $ip_add) Return the first EmpAcc filtered by the ip_add column
 * @method EmpAcc findOneByStatus(int $status) Return the first EmpAcc filtered by the status column
 * @method EmpAcc findOneByEmail(string $email) Return the first EmpAcc filtered by the email column
 * @method EmpAcc findOneByRole(string $role) Return the first EmpAcc filtered by the role column
 * @method EmpAcc findOneByKey(string $key) Return the first EmpAcc filtered by the key column
 * @method EmpAcc findOneByCreatedBy(int $created_by) Return the first EmpAcc filtered by the created_by column
 * @method EmpAcc findOneByLastUpdatedBy(int $last_updated_by) Return the first EmpAcc filtered by the last_updated_by column
 *
 * @method array findById(int $id) Return EmpAcc objects filtered by the id column
 * @method array findByUsername(string $username) Return EmpAcc objects filtered by the username column
 * @method array findByPassword(string $password) Return EmpAcc objects filtered by the password column
 * @method array findByTimestamp(string $timestamp) Return EmpAcc objects filtered by the timestamp column
 * @method array findByIpAdd(string $ip_add) Return EmpAcc objects filtered by the ip_add column
 * @method array findByStatus(int $status) Return EmpAcc objects filtered by the status column
 * @method array findByEmail(string $email) Return EmpAcc objects filtered by the email column
 * @method array findByRole(string $role) Return EmpAcc objects filtered by the role column
 * @method array findByKey(string $key) Return EmpAcc objects filtered by the key column
 * @method array findByCreatedBy(int $created_by) Return EmpAcc objects filtered by the created_by column
 * @method array findByLastUpdatedBy(int $last_updated_by) Return EmpAcc objects filtered by the last_updated_by column
 */
abstract class BaseEmpAccQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseEmpAccQuery object.
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
            $modelName = 'CoreBundle\\Model\\EmpAcc';
        }
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new EmpAccQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   EmpAccQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return EmpAccQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof EmpAccQuery) {
            return $criteria;
        }
        $query = new EmpAccQuery(null, null, $modelAlias);

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
     * @return   EmpAcc|EmpAcc[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = EmpAccPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(EmpAccPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 EmpAcc A model object, or null if the key is not found
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
     * @return                 EmpAcc A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id`, `username`, `password`, `timestamp`, `ip_add`, `status`, `email`, `role`, `key`, `created_by`, `last_updated_by` FROM `emp_acc` WHERE `id` = :p0';
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
            $obj = new EmpAcc();
            $obj->hydrate($row);
            EmpAccPeer::addInstanceToPool($obj, (string) $key);
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
     * @return EmpAcc|EmpAcc[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|EmpAcc[]|mixed the list of results, formatted by the current formatter
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
     * @return EmpAccQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(EmpAccPeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return EmpAccQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(EmpAccPeer::ID, $keys, Criteria::IN);
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
     * @return EmpAccQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(EmpAccPeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(EmpAccPeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EmpAccPeer::ID, $id, $comparison);
    }

    /**
     * Filter the query on the username column
     *
     * Example usage:
     * <code>
     * $query->filterByUsername('fooValue');   // WHERE username = 'fooValue'
     * $query->filterByUsername('%fooValue%'); // WHERE username LIKE '%fooValue%'
     * </code>
     *
     * @param     string $username The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return EmpAccQuery The current query, for fluid interface
     */
    public function filterByUsername($username = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($username)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $username)) {
                $username = str_replace('*', '%', $username);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(EmpAccPeer::USERNAME, $username, $comparison);
    }

    /**
     * Filter the query on the password column
     *
     * Example usage:
     * <code>
     * $query->filterByPassword('fooValue');   // WHERE password = 'fooValue'
     * $query->filterByPassword('%fooValue%'); // WHERE password LIKE '%fooValue%'
     * </code>
     *
     * @param     string $password The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return EmpAccQuery The current query, for fluid interface
     */
    public function filterByPassword($password = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($password)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $password)) {
                $password = str_replace('*', '%', $password);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(EmpAccPeer::PASSWORD, $password, $comparison);
    }

    /**
     * Filter the query on the timestamp column
     *
     * Example usage:
     * <code>
     * $query->filterByTimestamp('2011-03-14'); // WHERE timestamp = '2011-03-14'
     * $query->filterByTimestamp('now'); // WHERE timestamp = '2011-03-14'
     * $query->filterByTimestamp(array('max' => 'yesterday')); // WHERE timestamp < '2011-03-13'
     * </code>
     *
     * @param     mixed $timestamp The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return EmpAccQuery The current query, for fluid interface
     */
    public function filterByTimestamp($timestamp = null, $comparison = null)
    {
        if (is_array($timestamp)) {
            $useMinMax = false;
            if (isset($timestamp['min'])) {
                $this->addUsingAlias(EmpAccPeer::TIMESTAMP, $timestamp['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($timestamp['max'])) {
                $this->addUsingAlias(EmpAccPeer::TIMESTAMP, $timestamp['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EmpAccPeer::TIMESTAMP, $timestamp, $comparison);
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
     * @return EmpAccQuery The current query, for fluid interface
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

        return $this->addUsingAlias(EmpAccPeer::IP_ADD, $ipAdd, $comparison);
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
     * @return EmpAccQuery The current query, for fluid interface
     */
    public function filterByStatus($status = null, $comparison = null)
    {
        if (is_array($status)) {
            $useMinMax = false;
            if (isset($status['min'])) {
                $this->addUsingAlias(EmpAccPeer::STATUS, $status['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($status['max'])) {
                $this->addUsingAlias(EmpAccPeer::STATUS, $status['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EmpAccPeer::STATUS, $status, $comparison);
    }

    /**
     * Filter the query on the email column
     *
     * Example usage:
     * <code>
     * $query->filterByEmail('fooValue');   // WHERE email = 'fooValue'
     * $query->filterByEmail('%fooValue%'); // WHERE email LIKE '%fooValue%'
     * </code>
     *
     * @param     string $email The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return EmpAccQuery The current query, for fluid interface
     */
    public function filterByEmail($email = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($email)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $email)) {
                $email = str_replace('*', '%', $email);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(EmpAccPeer::EMAIL, $email, $comparison);
    }

    /**
     * Filter the query on the role column
     *
     * Example usage:
     * <code>
     * $query->filterByRole('fooValue');   // WHERE role = 'fooValue'
     * $query->filterByRole('%fooValue%'); // WHERE role LIKE '%fooValue%'
     * </code>
     *
     * @param     string $role The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return EmpAccQuery The current query, for fluid interface
     */
    public function filterByRole($role = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($role)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $role)) {
                $role = str_replace('*', '%', $role);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(EmpAccPeer::ROLE, $role, $comparison);
    }

    /**
     * Filter the query on the key column
     *
     * Example usage:
     * <code>
     * $query->filterByKey('fooValue');   // WHERE key = 'fooValue'
     * $query->filterByKey('%fooValue%'); // WHERE key LIKE '%fooValue%'
     * </code>
     *
     * @param     string $key The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return EmpAccQuery The current query, for fluid interface
     */
    public function filterByKey($key = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($key)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $key)) {
                $key = str_replace('*', '%', $key);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(EmpAccPeer::KEY, $key, $comparison);
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
     * @param     mixed $createdBy The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return EmpAccQuery The current query, for fluid interface
     */
    public function filterByCreatedBy($createdBy = null, $comparison = null)
    {
        if (is_array($createdBy)) {
            $useMinMax = false;
            if (isset($createdBy['min'])) {
                $this->addUsingAlias(EmpAccPeer::CREATED_BY, $createdBy['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdBy['max'])) {
                $this->addUsingAlias(EmpAccPeer::CREATED_BY, $createdBy['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EmpAccPeer::CREATED_BY, $createdBy, $comparison);
    }

    /**
     * Filter the query on the last_updated_by column
     *
     * Example usage:
     * <code>
     * $query->filterByLastUpdatedBy(1234); // WHERE last_updated_by = 1234
     * $query->filterByLastUpdatedBy(array(12, 34)); // WHERE last_updated_by IN (12, 34)
     * $query->filterByLastUpdatedBy(array('min' => 12)); // WHERE last_updated_by >= 12
     * $query->filterByLastUpdatedBy(array('max' => 12)); // WHERE last_updated_by <= 12
     * </code>
     *
     * @param     mixed $lastUpdatedBy The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return EmpAccQuery The current query, for fluid interface
     */
    public function filterByLastUpdatedBy($lastUpdatedBy = null, $comparison = null)
    {
        if (is_array($lastUpdatedBy)) {
            $useMinMax = false;
            if (isset($lastUpdatedBy['min'])) {
                $this->addUsingAlias(EmpAccPeer::LAST_UPDATED_BY, $lastUpdatedBy['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($lastUpdatedBy['max'])) {
                $this->addUsingAlias(EmpAccPeer::LAST_UPDATED_BY, $lastUpdatedBy['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EmpAccPeer::LAST_UPDATED_BY, $lastUpdatedBy, $comparison);
    }

    /**
     * Filter the query by a related EmpRequest object
     *
     * @param   EmpRequest|PropelObjectCollection $empRequest  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 EmpAccQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByEmpRequestRelatedByEmpAccId($empRequest, $comparison = null)
    {
        if ($empRequest instanceof EmpRequest) {
            return $this
                ->addUsingAlias(EmpAccPeer::ID, $empRequest->getEmpAccId(), $comparison);
        } elseif ($empRequest instanceof PropelObjectCollection) {
            return $this
                ->useEmpRequestRelatedByEmpAccIdQuery()
                ->filterByPrimaryKeys($empRequest->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByEmpRequestRelatedByEmpAccId() only accepts arguments of type EmpRequest or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the EmpRequestRelatedByEmpAccId relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return EmpAccQuery The current query, for fluid interface
     */
    public function joinEmpRequestRelatedByEmpAccId($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('EmpRequestRelatedByEmpAccId');

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
            $this->addJoinObject($join, 'EmpRequestRelatedByEmpAccId');
        }

        return $this;
    }

    /**
     * Use the EmpRequestRelatedByEmpAccId relation EmpRequest object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \CoreBundle\Model\EmpRequestQuery A secondary query class using the current class as primary query
     */
    public function useEmpRequestRelatedByEmpAccIdQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinEmpRequestRelatedByEmpAccId($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'EmpRequestRelatedByEmpAccId', '\CoreBundle\Model\EmpRequestQuery');
    }

    /**
     * Filter the query by a related EmpRequest object
     *
     * @param   EmpRequest|PropelObjectCollection $empRequest  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 EmpAccQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByEmpRequestRelatedByAdminId($empRequest, $comparison = null)
    {
        if ($empRequest instanceof EmpRequest) {
            return $this
                ->addUsingAlias(EmpAccPeer::ID, $empRequest->getAdminId(), $comparison);
        } elseif ($empRequest instanceof PropelObjectCollection) {
            return $this
                ->useEmpRequestRelatedByAdminIdQuery()
                ->filterByPrimaryKeys($empRequest->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByEmpRequestRelatedByAdminId() only accepts arguments of type EmpRequest or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the EmpRequestRelatedByAdminId relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return EmpAccQuery The current query, for fluid interface
     */
    public function joinEmpRequestRelatedByAdminId($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('EmpRequestRelatedByAdminId');

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
            $this->addJoinObject($join, 'EmpRequestRelatedByAdminId');
        }

        return $this;
    }

    /**
     * Use the EmpRequestRelatedByAdminId relation EmpRequest object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \CoreBundle\Model\EmpRequestQuery A secondary query class using the current class as primary query
     */
    public function useEmpRequestRelatedByAdminIdQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinEmpRequestRelatedByAdminId($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'EmpRequestRelatedByAdminId', '\CoreBundle\Model\EmpRequestQuery');
    }

    /**
     * Filter the query by a related EmpProfile object
     *
     * @param   EmpProfile|PropelObjectCollection $empProfile  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 EmpAccQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByEmpProfile($empProfile, $comparison = null)
    {
        if ($empProfile instanceof EmpProfile) {
            return $this
                ->addUsingAlias(EmpAccPeer::ID, $empProfile->getEmpAccAccId(), $comparison);
        } elseif ($empProfile instanceof PropelObjectCollection) {
            return $this
                ->useEmpProfileQuery()
                ->filterByPrimaryKeys($empProfile->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByEmpProfile() only accepts arguments of type EmpProfile or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the EmpProfile relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return EmpAccQuery The current query, for fluid interface
     */
    public function joinEmpProfile($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('EmpProfile');

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
            $this->addJoinObject($join, 'EmpProfile');
        }

        return $this;
    }

    /**
     * Use the EmpProfile relation EmpProfile object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \CoreBundle\Model\EmpProfileQuery A secondary query class using the current class as primary query
     */
    public function useEmpProfileQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinEmpProfile($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'EmpProfile', '\CoreBundle\Model\EmpProfileQuery');
    }

    /**
     * Filter the query by a related EmpTime object
     *
     * @param   EmpTime|PropelObjectCollection $empTime  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 EmpAccQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByEmpTime($empTime, $comparison = null)
    {
        if ($empTime instanceof EmpTime) {
            return $this
                ->addUsingAlias(EmpAccPeer::ID, $empTime->getEmpAccAccId(), $comparison);
        } elseif ($empTime instanceof PropelObjectCollection) {
            return $this
                ->useEmpTimeQuery()
                ->filterByPrimaryKeys($empTime->getPrimaryKeys())
                ->endUse();
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
     * @return EmpAccQuery The current query, for fluid interface
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
     * Filter the query by a related EmpCapabilities object
     *
     * @param   EmpCapabilities|PropelObjectCollection $empCapabilities  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 EmpAccQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByEmpCapabilities($empCapabilities, $comparison = null)
    {
        if ($empCapabilities instanceof EmpCapabilities) {
            return $this
                ->addUsingAlias(EmpAccPeer::ID, $empCapabilities->getEmpId(), $comparison);
        } elseif ($empCapabilities instanceof PropelObjectCollection) {
            return $this
                ->useEmpCapabilitiesQuery()
                ->filterByPrimaryKeys($empCapabilities->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByEmpCapabilities() only accepts arguments of type EmpCapabilities or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the EmpCapabilities relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return EmpAccQuery The current query, for fluid interface
     */
    public function joinEmpCapabilities($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('EmpCapabilities');

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
            $this->addJoinObject($join, 'EmpCapabilities');
        }

        return $this;
    }

    /**
     * Use the EmpCapabilities relation EmpCapabilities object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \CoreBundle\Model\EmpCapabilitiesQuery A secondary query class using the current class as primary query
     */
    public function useEmpCapabilitiesQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinEmpCapabilities($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'EmpCapabilities', '\CoreBundle\Model\EmpCapabilitiesQuery');
    }

    /**
     * Filter the query by a related ListEvents object
     *
     * @param   ListEvents|PropelObjectCollection $listEvents  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 EmpAccQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByListEvents($listEvents, $comparison = null)
    {
        if ($listEvents instanceof ListEvents) {
            return $this
                ->addUsingAlias(EmpAccPeer::ID, $listEvents->getCreatedBy(), $comparison);
        } elseif ($listEvents instanceof PropelObjectCollection) {
            return $this
                ->useListEventsQuery()
                ->filterByPrimaryKeys($listEvents->getPrimaryKeys())
                ->endUse();
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
     * @return EmpAccQuery The current query, for fluid interface
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
     * Filter the query by a related EventNotes object
     *
     * @param   EventNotes|PropelObjectCollection $eventNotes  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 EmpAccQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByEventNotes($eventNotes, $comparison = null)
    {
        if ($eventNotes instanceof EventNotes) {
            return $this
                ->addUsingAlias(EmpAccPeer::ID, $eventNotes->getCreatedBy(), $comparison);
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
     * @return EmpAccQuery The current query, for fluid interface
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
     * @return                 EmpAccQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByEventTaggedPersons($eventTaggedPersons, $comparison = null)
    {
        if ($eventTaggedPersons instanceof EventTaggedPersons) {
            return $this
                ->addUsingAlias(EmpAccPeer::ID, $eventTaggedPersons->getEmpId(), $comparison);
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
     * @return EmpAccQuery The current query, for fluid interface
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
     * @param   EmpAcc $empAcc Object to remove from the list of results
     *
     * @return EmpAccQuery The current query, for fluid interface
     */
    public function prune($empAcc = null)
    {
        if ($empAcc) {
            $this->addUsingAlias(EmpAccPeer::ID, $empAcc->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

}
