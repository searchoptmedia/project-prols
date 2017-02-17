<?php

namespace CoreBundle\Model\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'emp_status_type' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 * @package    propel.generator.src.CoreBundle.Model.map
 */
class EmpStatusTypeTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.CoreBundle.Model.map.EmpStatusTypeTableMap';

    /**
     * Initialize the table attributes, columns and validators
     * Relations are not initialized by this method since they are lazy loaded
     *
     * @return void
     * @throws PropelException
     */
    public function initialize()
    {
        // attributes
        $this->setName('emp_status_type');
        $this->setPhpName('EmpStatusType');
        $this->setClassname('CoreBundle\\Model\\EmpStatusType');
        $this->setPackage('src.CoreBundle.Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('name', 'Name', 'VARCHAR', true, 45, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('EmpProfile', 'CoreBundle\\Model\\EmpProfile', RelationMap::ONE_TO_MANY, array('id' => 'status', ), null, null, 'EmpProfiles');
    } // buildRelations()

} // EmpStatusTypeTableMap
