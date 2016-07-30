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
use CoreBundle\Model\prols_emp_acc;
use CoreBundle\Model\prols_emp_accPeer;
use CoreBundle\Model\prols_emp_accQuery;

/**
 * @method prols_emp_accQuery orderById($order = Criteria::ASC) Order by the id column
 * @method prols_emp_accQuery orderByEmail($order = Criteria::ASC) Order by the email column
 * @method prols_emp_accQuery orderByUsername($order = Criteria::ASC) Order by the username column
 * @method prols_emp_accQuery orderByPassword($order = Criteria::ASC) Order by the password column
 * @method prols_emp_accQuery orderByAuthkey($order = Criteria::ASC) Order by the authkey column
 * @method prols_emp_accQuery orderByTimestamp($order = Criteria::ASC) Order by the timestamp column
 * @method prols_emp_accQuery orderByRoleId($order = Criteria::ASC) Order by the role_id column
 * @method prols_emp_accQuery orderByIpAdd($order = Criteria::ASC) Order by the ip_add column
 * @method prols_emp_accQuery orderByStatus($order = Criteria::ASC) Order by the status column
 *
 * @method prols_emp_accQuery groupById() Group by the id column
 * @method prols_emp_accQuery groupByEmail() Group by the email column
 * @method prols_emp_accQuery groupByUsername() Group by the username column
 * @method prols_emp_accQuery groupByPassword() Group by the password column
 * @method prols_emp_accQuery groupByAuthkey() Group by the authkey column
 * @method prols_emp_accQuery groupByTimestamp() Group by the timestamp column
 * @method prols_emp_accQuery groupByRoleId() Group by the role_id column
 * @method prols_emp_accQuery groupByIpAdd() Group by the ip_add column
 * @method prols_emp_accQuery groupByStatus() Group by the status column
 *
 * @method prols_emp_accQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method prols_emp_accQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method prols_emp_accQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method prols_emp_acc findOne(PropelPDO $con = null) Return the first prols_emp_acc matching the query
 * @method prols_emp_acc findOneOrCreate(PropelPDO $con = null) Return the first prols_emp_acc matching the query, or a new prols_emp_acc object populated from the query conditions when no match is found
 *
 * @method prols_emp_acc findOneByEmail(string $email) Return the first prols_emp_acc filtered by the email column
 * @method prols_emp_acc findOneByUsername(string $username) Return the first prols_emp_acc filtered by the username column
 * @method prols_emp_acc findOneByPassword(string $password) Return the first prols_emp_acc filtered by the password column
 * @method prols_emp_acc findOneByAuthkey(string $authkey) Return the first prols_emp_acc filtered by the authkey column
 * @method prols_emp_acc findOneByTimestamp(string $timestamp) Return the first prols_emp_acc filtered by the timestamp column
 * @method prols_emp_acc findOneByRoleId(int $role_id) Return the first prols_emp_acc filtered by the role_id column
 * @method prols_emp_acc findOneByIpAdd(string $ip_add) Return the first prols_emp_acc filtered by the ip_add column
 * @method prols_emp_acc findOneByStatus(string $status) Return the first prols_emp_acc filtered by the status column
 *
 * @method array findById(int $id) Return prols_emp_acc objects filtered by the id column
 * @method array findByEmail(string $email) Return prols_emp_acc objects filtered by the email column
 * @method array findByUsername(string $username) Return prols_emp_acc objects filtered by the username column
 * @method array findByPassword(string $password) Return prols_emp_acc objects filtered by the password column
 * @method array findByAuthkey(string $authkey) Return prols_emp_acc objects filtered by the authkey column
 * @method array findByTimestamp(string $timestamp) Return prols_emp_acc objects filtered by the timestamp column
 * @method array findByRoleId(int $role_id) Return prols_emp_acc objects filtered by the role_id column
 * @method array findByIpAdd(string $ip_add) Return prols_emp_acc objects filtered by the ip_add column
 * @method array findByStatus(string $status) Return prols_emp_acc objects filtered by the status column
 */
abstract class Baseprols_emp_accQuery extends ModelCriteria
{
    /**
     * Initializes internal state of Baseprols_emp_accQuery object.
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
            $modelName = 'CoreBundle\\Model\\prols_emp_acc';
        }
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new prols_emp_accQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   prols_emp_accQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return prols_emp_accQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof prols_emp_accQuery) {
            return $criteria;
        }
        $query = new prols_emp_accQuery(null, null, $modelAlias);

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
     * @return   prols_emp_acc|prols_emp_acc[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = prols_emp_accPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(prols_emp_accPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 prols_emp_acc A model object, or null if the key is not found
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
     * @return                 prols_emp_acc A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id`, `email`, `username`, `password`, `authkey`, `timestamp`, `role_id`, `ip_add`, `status` FROM `emp_acc` WHERE `id` = :p0';
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
            $obj = new prols_emp_acc();
            $obj->hydrate($row);
            prols_emp_accPeer::addInstanceToPool($obj, (string) $key);
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
     * @return prols_emp_acc|prols_emp_acc[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|prols_emp_acc[]|mixed the list of results, formatted by the current formatter
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
     * @return prols_emp_accQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(prols_emp_accPeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return prols_emp_accQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(prols_emp_accPeer::ID, $keys, Criteria::IN);
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
     * @return prols_emp_accQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(prols_emp_accPeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(prols_emp_accPeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(prols_emp_accPeer::ID, $id, $comparison);
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
     * @return prols_emp_accQuery The current query, for fluid interface
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

        return $this->addUsingAlias(prols_emp_accPeer::EMAIL, $email, $comparison);
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
     * @return prols_emp_accQuery The current query, for fluid interface
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

        return $this->addUsingAlias(prols_emp_accPeer::USERNAME, $username, $comparison);
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
     * @return prols_emp_accQuery The current query, for fluid interface
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

        return $this->addUsingAlias(prols_emp_accPeer::PASSWORD, $password, $comparison);
    }

    /**
     * Filter the query on the authkey column
     *
     * Example usage:
     * <code>
     * $query->filterByAuthkey('fooValue');   // WHERE authkey = 'fooValue'
     * $query->filterByAuthkey('%fooValue%'); // WHERE authkey LIKE '%fooValue%'
     * </code>
     *
     * @param     string $authkey The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return prols_emp_accQuery The current query, for fluid interface
     */
    public function filterByAuthkey($authkey = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($authkey)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $authkey)) {
                $authkey = str_replace('*', '%', $authkey);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(prols_emp_accPeer::AUTHKEY, $authkey, $comparison);
    }

    /**
     * Filter the query on the timestamp column
     *
     * Example usage:
     * <code>
     * $query->filterByTimestamp('fooValue');   // WHERE timestamp = 'fooValue'
     * $query->filterByTimestamp('%fooValue%'); // WHERE timestamp LIKE '%fooValue%'
     * </code>
     *
     * @param     string $timestamp The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return prols_emp_accQuery The current query, for fluid interface
     */
    public function filterByTimestamp($timestamp = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($timestamp)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $timestamp)) {
                $timestamp = str_replace('*', '%', $timestamp);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(prols_emp_accPeer::TIMESTAMP, $timestamp, $comparison);
    }

    /**
     * Filter the query on the role_id column
     *
     * Example usage:
     * <code>
     * $query->filterByRoleId(1234); // WHERE role_id = 1234
     * $query->filterByRoleId(array(12, 34)); // WHERE role_id IN (12, 34)
     * $query->filterByRoleId(array('min' => 12)); // WHERE role_id >= 12
     * $query->filterByRoleId(array('max' => 12)); // WHERE role_id <= 12
     * </code>
     *
     * @param     mixed $roleId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return prols_emp_accQuery The current query, for fluid interface
     */
    public function filterByRoleId($roleId = null, $comparison = null)
    {
        if (is_array($roleId)) {
            $useMinMax = false;
            if (isset($roleId['min'])) {
                $this->addUsingAlias(prols_emp_accPeer::ROLE_ID, $roleId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($roleId['max'])) {
                $this->addUsingAlias(prols_emp_accPeer::ROLE_ID, $roleId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(prols_emp_accPeer::ROLE_ID, $roleId, $comparison);
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
     * @return prols_emp_accQuery The current query, for fluid interface
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

        return $this->addUsingAlias(prols_emp_accPeer::IP_ADD, $ipAdd, $comparison);
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
     * @return prols_emp_accQuery The current query, for fluid interface
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

        return $this->addUsingAlias(prols_emp_accPeer::STATUS, $status, $comparison);
    }

    /**
     * Exclude object from result
     *
     * @param   prols_emp_acc $prols_emp_acc Object to remove from the list of results
     *
     * @return prols_emp_accQuery The current query, for fluid interface
     */
    public function prune($prols_emp_acc = null)
    {
        if ($prols_emp_acc) {
            $this->addUsingAlias(prols_emp_accPeer::ID, $prols_emp_acc->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

}
