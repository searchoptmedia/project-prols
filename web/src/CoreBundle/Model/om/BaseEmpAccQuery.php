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
use CoreBundle\Model\EmpLeave;
use CoreBundle\Model\EmpProfile;
use CoreBundle\Model\EmpTime;

/**
 * @method EmpAccQuery orderById($order = Criteria::ASC) Order by the id column
 * @method EmpAccQuery orderByUsername($order = Criteria::ASC) Order by the username column
 * @method EmpAccQuery orderByPassword($order = Criteria::ASC) Order by the password column
 * @method EmpAccQuery orderByTimestamp($order = Criteria::ASC) Order by the timestamp column
 * @method EmpAccQuery orderByIpAdd($order = Criteria::ASC) Order by the ip_add column
 * @method EmpAccQuery orderByStatus($order = Criteria::ASC) Order by the status column
 * @method EmpAccQuery orderByEmail($order = Criteria::ASC) Order by the email column
 * @method EmpAccQuery orderByRole($order = Criteria::ASC) Order by the role column
 *
 * @method EmpAccQuery groupById() Group by the id column
 * @method EmpAccQuery groupByUsername() Group by the username column
 * @method EmpAccQuery groupByPassword() Group by the password column
 * @method EmpAccQuery groupByTimestamp() Group by the timestamp column
 * @method EmpAccQuery groupByIpAdd() Group by the ip_add column
 * @method EmpAccQuery groupByStatus() Group by the status column
 * @method EmpAccQuery groupByEmail() Group by the email column
 * @method EmpAccQuery groupByRole() Group by the role column
 *
 * @method EmpAccQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method EmpAccQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method EmpAccQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method EmpAccQuery leftJoinEmpLeaveRelatedByEmpAccId($relationAlias = null) Adds a LEFT JOIN clause to the query using the EmpLeaveRelatedByEmpAccId relation
 * @method EmpAccQuery rightJoinEmpLeaveRelatedByEmpAccId($relationAlias = null) Adds a RIGHT JOIN clause to the query using the EmpLeaveRelatedByEmpAccId relation
 * @method EmpAccQuery innerJoinEmpLeaveRelatedByEmpAccId($relationAlias = null) Adds a INNER JOIN clause to the query using the EmpLeaveRelatedByEmpAccId relation
 *
 * @method EmpAccQuery leftJoinEmpLeaveRelatedByAdminId($relationAlias = null) Adds a LEFT JOIN clause to the query using the EmpLeaveRelatedByAdminId relation
 * @method EmpAccQuery rightJoinEmpLeaveRelatedByAdminId($relationAlias = null) Adds a RIGHT JOIN clause to the query using the EmpLeaveRelatedByAdminId relation
 * @method EmpAccQuery innerJoinEmpLeaveRelatedByAdminId($relationAlias = null) Adds a INNER JOIN clause to the query using the EmpLeaveRelatedByAdminId relation
 *
 * @method EmpAccQuery leftJoinEmpProfile($relationAlias = null) Adds a LEFT JOIN clause to the query using the EmpProfile relation
 * @method EmpAccQuery rightJoinEmpProfile($relationAlias = null) Adds a RIGHT JOIN clause to the query using the EmpProfile relation
 * @method EmpAccQuery innerJoinEmpProfile($relationAlias = null) Adds a INNER JOIN clause to the query using the EmpProfile relation
 *
 * @method EmpAccQuery leftJoinEmpTime($relationAlias = null) Adds a LEFT JOIN clause to the query using the EmpTime relation
 * @method EmpAccQuery rightJoinEmpTime($relationAlias = null) Adds a RIGHT JOIN clause to the query using the EmpTime relation
 * @method EmpAccQuery innerJoinEmpTime($relationAlias = null) Adds a INNER JOIN clause to the query using the EmpTime relation
 *
 * @method EmpAcc findOne(PropelPDO $con = null) Return the first EmpAcc matching the query
 * @method EmpAcc findOneOrCreate(PropelPDO $con = null) Return the first EmpAcc matching the query, or a new EmpAcc object populated from the query conditions when no match is found
 *
 * @method EmpAcc findOneByUsername(string $username) Return the first EmpAcc filtered by the username column
 * @method EmpAcc findOneByPassword(string $password) Return the first EmpAcc filtered by the password column
 * @method EmpAcc findOneByTimestamp(string $timestamp) Return the first EmpAcc filtered by the timestamp column
 * @method EmpAcc findOneByIpAdd(string $ip_add) Return the first EmpAcc filtered by the ip_add column
 * @method EmpAcc findOneByStatus(string $status) Return the first EmpAcc filtered by the status column
 * @method EmpAcc findOneByEmail(string $email) Return the first EmpAcc filtered by the email column
 * @method EmpAcc findOneByRole(string $role) Return the first EmpAcc filtered by the role column
 *
 * @method array findById(int $id) Return EmpAcc objects filtered by the id column
 * @method array findByUsername(string $username) Return EmpAcc objects filtered by the username column
 * @method array findByPassword(string $password) Return EmpAcc objects filtered by the password column
 * @method array findByTimestamp(string $timestamp) Return EmpAcc objects filtered by the timestamp column
 * @method array findByIpAdd(string $ip_add) Return EmpAcc objects filtered by the ip_add column
 * @method array findByStatus(string $status) Return EmpAcc objects filtered by the status column
 * @method array findByEmail(string $email) Return EmpAcc objects filtered by the email column
 * @method array findByRole(string $role) Return EmpAcc objects filtered by the role column
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
        $sql = 'SELECT `id`, `username`, `password`, `timestamp`, `ip_add`, `status`, `email`, `role` FROM `emp_acc` WHERE `id` = :p0';
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
     * $query->filterByStatus('fooValue');   // WHERE status = 'fooValue'
     * $query->filterByStatus('%fooValue%'); // WHERE status LIKE '%fooValue%'
     * </code>
     *
     * @param     string $status The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return EmpAccQuery The current query, for fluid interface
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
     * Filter the query by a related EmpLeave object
     *
     * @param   EmpLeave|PropelObjectCollection $empLeave  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 EmpAccQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByEmpLeaveRelatedByEmpAccId($empLeave, $comparison = null)
    {
        if ($empLeave instanceof EmpLeave) {
            return $this
                ->addUsingAlias(EmpAccPeer::ID, $empLeave->getEmpAccId(), $comparison);
        } elseif ($empLeave instanceof PropelObjectCollection) {
            return $this
                ->useEmpLeaveRelatedByEmpAccIdQuery()
                ->filterByPrimaryKeys($empLeave->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByEmpLeaveRelatedByEmpAccId() only accepts arguments of type EmpLeave or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the EmpLeaveRelatedByEmpAccId relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return EmpAccQuery The current query, for fluid interface
     */
    public function joinEmpLeaveRelatedByEmpAccId($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('EmpLeaveRelatedByEmpAccId');

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
            $this->addJoinObject($join, 'EmpLeaveRelatedByEmpAccId');
        }

        return $this;
    }

    /**
     * Use the EmpLeaveRelatedByEmpAccId relation EmpLeave object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \CoreBundle\Model\EmpLeaveQuery A secondary query class using the current class as primary query
     */
    public function useEmpLeaveRelatedByEmpAccIdQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinEmpLeaveRelatedByEmpAccId($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'EmpLeaveRelatedByEmpAccId', '\CoreBundle\Model\EmpLeaveQuery');
    }

    /**
     * Filter the query by a related EmpLeave object
     *
     * @param   EmpLeave|PropelObjectCollection $empLeave  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 EmpAccQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByEmpLeaveRelatedByAdminId($empLeave, $comparison = null)
    {
        if ($empLeave instanceof EmpLeave) {
            return $this
                ->addUsingAlias(EmpAccPeer::ID, $empLeave->getAdminId(), $comparison);
        } elseif ($empLeave instanceof PropelObjectCollection) {
            return $this
                ->useEmpLeaveRelatedByAdminIdQuery()
                ->filterByPrimaryKeys($empLeave->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByEmpLeaveRelatedByAdminId() only accepts arguments of type EmpLeave or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the EmpLeaveRelatedByAdminId relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return EmpAccQuery The current query, for fluid interface
     */
    public function joinEmpLeaveRelatedByAdminId($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('EmpLeaveRelatedByAdminId');

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
            $this->addJoinObject($join, 'EmpLeaveRelatedByAdminId');
        }

        return $this;
    }

    /**
     * Use the EmpLeaveRelatedByAdminId relation EmpLeave object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \CoreBundle\Model\EmpLeaveQuery A secondary query class using the current class as primary query
     */
    public function useEmpLeaveRelatedByAdminIdQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinEmpLeaveRelatedByAdminId($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'EmpLeaveRelatedByAdminId', '\CoreBundle\Model\EmpLeaveQuery');
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
