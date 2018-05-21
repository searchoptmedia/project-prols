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
use CoreBundle\Model\CapabilitiesList;
use CoreBundle\Model\CapabilitiesListPeer;
use CoreBundle\Model\CapabilitiesListQuery;
use CoreBundle\Model\EmpCapabilities;

/**
 * @method CapabilitiesListQuery orderById($order = Criteria::ASC) Order by the id column
 * @method CapabilitiesListQuery orderByCapability($order = Criteria::ASC) Order by the capability column
 *
 * @method CapabilitiesListQuery groupById() Group by the id column
 * @method CapabilitiesListQuery groupByCapability() Group by the capability column
 *
 * @method CapabilitiesListQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method CapabilitiesListQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method CapabilitiesListQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method CapabilitiesListQuery leftJoinEmpCapabilities($relationAlias = null) Adds a LEFT JOIN clause to the query using the EmpCapabilities relation
 * @method CapabilitiesListQuery rightJoinEmpCapabilities($relationAlias = null) Adds a RIGHT JOIN clause to the query using the EmpCapabilities relation
 * @method CapabilitiesListQuery innerJoinEmpCapabilities($relationAlias = null) Adds a INNER JOIN clause to the query using the EmpCapabilities relation
 *
 * @method CapabilitiesList findOne(PropelPDO $con = null) Return the first CapabilitiesList matching the query
 * @method CapabilitiesList findOneOrCreate(PropelPDO $con = null) Return the first CapabilitiesList matching the query, or a new CapabilitiesList object populated from the query conditions when no match is found
 *
 * @method CapabilitiesList findOneByCapability(string $capability) Return the first CapabilitiesList filtered by the capability column
 *
 * @method array findById(int $id) Return CapabilitiesList objects filtered by the id column
 * @method array findByCapability(string $capability) Return CapabilitiesList objects filtered by the capability column
 */
abstract class BaseCapabilitiesListQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseCapabilitiesListQuery object.
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
            $modelName = 'CoreBundle\\Model\\CapabilitiesList';
        }
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new CapabilitiesListQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   CapabilitiesListQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return CapabilitiesListQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof CapabilitiesListQuery) {
            return $criteria;
        }
        $query = new CapabilitiesListQuery(null, null, $modelAlias);

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
     * @return   CapabilitiesList|CapabilitiesList[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = CapabilitiesListPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(CapabilitiesListPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 CapabilitiesList A model object, or null if the key is not found
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
     * @return                 CapabilitiesList A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id`, `capability` FROM `capabilities_list` WHERE `id` = :p0';
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
            $obj = new CapabilitiesList();
            $obj->hydrate($row);
            CapabilitiesListPeer::addInstanceToPool($obj, (string) $key);
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
     * @return CapabilitiesList|CapabilitiesList[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|CapabilitiesList[]|mixed the list of results, formatted by the current formatter
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
     * @return CapabilitiesListQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(CapabilitiesListPeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return CapabilitiesListQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(CapabilitiesListPeer::ID, $keys, Criteria::IN);
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
     * @return CapabilitiesListQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(CapabilitiesListPeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(CapabilitiesListPeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CapabilitiesListPeer::ID, $id, $comparison);
    }

    /**
     * Filter the query on the capability column
     *
     * Example usage:
     * <code>
     * $query->filterByCapability('fooValue');   // WHERE capability = 'fooValue'
     * $query->filterByCapability('%fooValue%'); // WHERE capability LIKE '%fooValue%'
     * </code>
     *
     * @param     string $capability The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return CapabilitiesListQuery The current query, for fluid interface
     */
    public function filterByCapability($capability = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($capability)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $capability)) {
                $capability = str_replace('*', '%', $capability);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(CapabilitiesListPeer::CAPABILITY, $capability, $comparison);
    }

    /**
     * Filter the query by a related EmpCapabilities object
     *
     * @param   EmpCapabilities|PropelObjectCollection $empCapabilities  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 CapabilitiesListQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByEmpCapabilities($empCapabilities, $comparison = null)
    {
        if ($empCapabilities instanceof EmpCapabilities) {
            return $this
                ->addUsingAlias(CapabilitiesListPeer::ID, $empCapabilities->getCapId(), $comparison);
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
     * @return CapabilitiesListQuery The current query, for fluid interface
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
     * Exclude object from result
     *
     * @param   CapabilitiesList $capabilitiesList Object to remove from the list of results
     *
     * @return CapabilitiesListQuery The current query, for fluid interface
     */
    public function prune($capabilitiesList = null)
    {
        if ($capabilitiesList) {
            $this->addUsingAlias(CapabilitiesListPeer::ID, $capabilitiesList->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

}
