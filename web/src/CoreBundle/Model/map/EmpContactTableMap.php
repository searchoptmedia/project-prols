<?php

namespace CoreBundle\Model\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'emp_contact' table.
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
class EmpContactTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.CoreBundle.Model.map.EmpContactTableMap';

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
        $this->setName('emp_contact');
        $this->setPhpName('EmpContact');
        $this->setClassname('CoreBundle\\Model\\EmpContact');
        $this->setPackage('src.CoreBundle.Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('contact', 'Contact', 'VARCHAR', true, 45, null);
        $this->addForeignKey('emp_profile_id', 'EmpProfileId', 'INTEGER', 'emp_profile', 'id', true, null, null);
        $this->addForeignKey('list_cont_types_id', 'ListContTypesId', 'INTEGER', 'list_cont_types', 'id', true, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('EmpProfile', 'CoreBundle\\Model\\EmpProfile', RelationMap::MANY_TO_ONE, array('emp_profile_id' => 'id', ), null, null);
        $this->addRelation('ListContTypes', 'CoreBundle\\Model\\ListContTypes', RelationMap::MANY_TO_ONE, array('list_cont_types_id' => 'id', ), null, null);
    } // buildRelations()

} // EmpContactTableMap
