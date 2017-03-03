<?php

namespace CoreBundle\Model\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'emp_capabilities' table.
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
class EmpCapabilitiesTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.CoreBundle.Model.map.EmpCapabilitiesTableMap';

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
        $this->setName('emp_capabilities');
        $this->setPhpName('EmpCapabilities');
        $this->setClassname('CoreBundle\\Model\\EmpCapabilities');
        $this->setPackage('src.CoreBundle.Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addForeignKey('empid', 'EmpId', 'INTEGER', 'emp_acc', 'id', false, null, null);
        $this->addForeignKey('capid', 'CapId', 'INTEGER', 'capabilities_list', 'id', false, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('EmpAcc', 'CoreBundle\\Model\\EmpAcc', RelationMap::MANY_TO_ONE, array('empid' => 'id', ), null, null);
        $this->addRelation('CapabilitiesList', 'CoreBundle\\Model\\CapabilitiesList', RelationMap::MANY_TO_ONE, array('capid' => 'id', ), null, null);
    } // buildRelations()

} // EmpCapabilitiesTableMap
