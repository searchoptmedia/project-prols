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
use CoreBundle\Model\EmpContact;
use CoreBundle\Model\EmpContactPeer;
use CoreBundle\Model\EmpContactQuery;
use CoreBundle\Model\EmpProfile;
use CoreBundle\Model\ListContTypes;

/**
 * @method EmpContactQuery orderById($order = Criteria::ASC) Order by the id column
 * @method EmpContactQuery orderByContact($order = Criteria::ASC) Order by the contact column
 * @method EmpContactQuery orderByEmpProfileId($order = Criteria::ASC) Order by the emp_profile_id column
 * @method EmpContactQuery orderByListContTypesId($order = Criteria::ASC) Order by the list_cont_types_id column
 *
 * @method EmpContactQuery groupById() Group by the id column
 * @method EmpContactQuery groupByContact() Group by the contact column
 * @method EmpContactQuery groupByEmpProfileId() Group by the emp_profile_id column
 * @method EmpContactQuery groupByListContTypesId() Group by the list_cont_types_id column
 *
 * @method EmpContactQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method EmpContactQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method EmpContactQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method EmpContactQuery leftJoinEmpProfile($relationAlias = null) Adds a LEFT JOIN clause to the query using the EmpProfile relation
 * @method EmpContactQuery rightJoinEmpProfile($relationAlias = null) Adds a RIGHT JOIN clause to the query using the EmpProfile relation
 * @method EmpContactQuery innerJoinEmpProfile($relationAlias = null) Adds a INNER JOIN clause to the query using the EmpProfile relation
 *
 * @method EmpContactQuery leftJoinListContTypes($relationAlias = null) Adds a LEFT JOIN clause to the query using the ListContTypes relation
 * @method EmpContactQuery rightJoinListContTypes($relationAlias = null) Adds a RIGHT JOIN clause to the query using the ListContTypes relation
 * @method EmpContactQuery innerJoinListContTypes($relationAlias = null) Adds a INNER JOIN clause to the query using the ListContTypes relation
 *
 * @method EmpContact findOne(PropelPDO $con = null) Return the first EmpContact matching the query
 * @method EmpContact findOneOrCreate(PropelPDO $con = null) Return the first EmpContact matching the query, or a new EmpContact object populated from the query conditions when no match is found
 *
 * @method EmpContact findOneByContact(string $contact) Return the first EmpContact filtered by the contact column
 * @method EmpContact findOneByEmpProfileId(int $emp_profile_id) Return the first EmpContact filtered by the emp_profile_id column
 * @method EmpContact findOneByListContTypesId(int $list_cont_types_id) Return the first EmpContact filtered by the list_cont_types_id column
 *
 * @method array findById(int $id) Return EmpContact objects filtered by the id column
 * @method array findByContact(string $contact) Return EmpContact objects filtered by the contact column
 * @method array findByEmpProfileId(int $emp_profile_id) Return EmpContact objects filtered by the emp_profile_id column
 * @method array findByListContTypesId(int $list_cont_types_id) Return EmpContact objects filtered by the list_cont_types_id column
 */
abstract class BaseEmpContactQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseEmpContactQuery object.
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
            $modelName = 'CoreBundle\\Model\\EmpContact';
        }
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new EmpContactQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   EmpContactQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return EmpContactQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof EmpContactQuery) {
            return $criteria;
        }
        $query = new EmpContactQuery(null, null, $modelAlias);

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
     * @return   EmpContact|EmpContact[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = EmpContactPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(EmpContactPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 EmpContact A model object, or null if the key is not found
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
     * @return                 EmpContact A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id`, `contact`, `emp_profile_id`, `list_cont_types_id` FROM `emp_contact` WHERE `id` = :p0';
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
            $obj = new EmpContact();
            $obj->hydrate($row);
            EmpContactPeer::addInstanceToPool($obj, (string) $key);
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
     * @return EmpContact|EmpContact[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|EmpContact[]|mixed the list of results, formatted by the current formatter
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
     * @return EmpContactQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(EmpContactPeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return EmpContactQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(EmpContactPeer::ID, $keys, Criteria::IN);
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
     * @return EmpContactQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(EmpContactPeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(EmpContactPeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EmpContactPeer::ID, $id, $comparison);
    }

    /**
     * Filter the query on the contact column
     *
     * Example usage:
     * <code>
     * $query->filterByContact('fooValue');   // WHERE contact = 'fooValue'
     * $query->filterByContact('%fooValue%'); // WHERE contact LIKE '%fooValue%'
     * </code>
     *
     * @param     string $contact The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return EmpContactQuery The current query, for fluid interface
     */
    public function filterByContact($contact = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($contact)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $contact)) {
                $contact = str_replace('*', '%', $contact);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(EmpContactPeer::CONTACT, $contact, $comparison);
    }

    /**
     * Filter the query on the emp_profile_id column
     *
     * Example usage:
     * <code>
     * $query->filterByEmpProfileId(1234); // WHERE emp_profile_id = 1234
     * $query->filterByEmpProfileId(array(12, 34)); // WHERE emp_profile_id IN (12, 34)
     * $query->filterByEmpProfileId(array('min' => 12)); // WHERE emp_profile_id >= 12
     * $query->filterByEmpProfileId(array('max' => 12)); // WHERE emp_profile_id <= 12
     * </code>
     *
     * @see       filterByEmpProfile()
     *
     * @param     mixed $empProfileId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return EmpContactQuery The current query, for fluid interface
     */
    public function filterByEmpProfileId($empProfileId = null, $comparison = null)
    {
        if (is_array($empProfileId)) {
            $useMinMax = false;
            if (isset($empProfileId['min'])) {
                $this->addUsingAlias(EmpContactPeer::EMP_PROFILE_ID, $empProfileId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($empProfileId['max'])) {
                $this->addUsingAlias(EmpContactPeer::EMP_PROFILE_ID, $empProfileId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EmpContactPeer::EMP_PROFILE_ID, $empProfileId, $comparison);
    }

    /**
     * Filter the query on the list_cont_types_id column
     *
     * Example usage:
     * <code>
     * $query->filterByListContTypesId(1234); // WHERE list_cont_types_id = 1234
     * $query->filterByListContTypesId(array(12, 34)); // WHERE list_cont_types_id IN (12, 34)
     * $query->filterByListContTypesId(array('min' => 12)); // WHERE list_cont_types_id >= 12
     * $query->filterByListContTypesId(array('max' => 12)); // WHERE list_cont_types_id <= 12
     * </code>
     *
     * @see       filterByListContTypes()
     *
     * @param     mixed $listContTypesId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return EmpContactQuery The current query, for fluid interface
     */
    public function filterByListContTypesId($listContTypesId = null, $comparison = null)
    {
        if (is_array($listContTypesId)) {
            $useMinMax = false;
            if (isset($listContTypesId['min'])) {
                $this->addUsingAlias(EmpContactPeer::LIST_CONT_TYPES_ID, $listContTypesId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($listContTypesId['max'])) {
                $this->addUsingAlias(EmpContactPeer::LIST_CONT_TYPES_ID, $listContTypesId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EmpContactPeer::LIST_CONT_TYPES_ID, $listContTypesId, $comparison);
    }

    /**
     * Filter the query by a related EmpProfile object
     *
     * @param   EmpProfile|PropelObjectCollection $empProfile The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 EmpContactQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByEmpProfile($empProfile, $comparison = null)
    {
        if ($empProfile instanceof EmpProfile) {
            return $this
                ->addUsingAlias(EmpContactPeer::EMP_PROFILE_ID, $empProfile->getId(), $comparison);
        } elseif ($empProfile instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(EmpContactPeer::EMP_PROFILE_ID, $empProfile->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return EmpContactQuery The current query, for fluid interface
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
     * Filter the query by a related ListContTypes object
     *
     * @param   ListContTypes|PropelObjectCollection $listContTypes The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 EmpContactQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByListContTypes($listContTypes, $comparison = null)
    {
        if ($listContTypes instanceof ListContTypes) {
            return $this
                ->addUsingAlias(EmpContactPeer::LIST_CONT_TYPES_ID, $listContTypes->getId(), $comparison);
        } elseif ($listContTypes instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(EmpContactPeer::LIST_CONT_TYPES_ID, $listContTypes->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByListContTypes() only accepts arguments of type ListContTypes or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the ListContTypes relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return EmpContactQuery The current query, for fluid interface
     */
    public function joinListContTypes($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('ListContTypes');

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
            $this->addJoinObject($join, 'ListContTypes');
        }

        return $this;
    }

    /**
     * Use the ListContTypes relation ListContTypes object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \CoreBundle\Model\ListContTypesQuery A secondary query class using the current class as primary query
     */
    public function useListContTypesQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinListContTypes($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'ListContTypes', '\CoreBundle\Model\ListContTypesQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   EmpContact $empContact Object to remove from the list of results
     *
     * @return EmpContactQuery The current query, for fluid interface
     */
    public function prune($empContact = null)
    {
        if ($empContact) {
            $this->addUsingAlias(EmpContactPeer::ID, $empContact->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

}
