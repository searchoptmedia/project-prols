<?php

namespace CoreBundle\Model\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'event_tagged_persons' table.
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
class EventTaggedPersonsTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.CoreBundle.Model.map.EventTaggedPersonsTableMap';

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
        $this->setName('event_tagged_persons');
        $this->setPhpName('EventTaggedPersons');
        $this->setClassname('CoreBundle\\Model\\EventTaggedPersons');
        $this->setPackage('src.CoreBundle.Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addForeignKey('event_id', 'EventId', 'INTEGER', 'list_events', 'id', true, null, null);
        $this->addForeignKey('emp_id', 'EmpId', 'INTEGER', 'emp_acc', 'id', true, null, null);
        $this->addColumn('status', 'Status', 'INTEGER', true, null, null);
        $this->addColumn('reason', 'Reason', 'LONGVARCHAR', true, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('EmpAcc', 'CoreBundle\\Model\\EmpAcc', RelationMap::MANY_TO_ONE, array('emp_id' => 'id', ), null, null);
        $this->addRelation('ListEvents', 'CoreBundle\\Model\\ListEvents', RelationMap::MANY_TO_ONE, array('event_id' => 'id', ), null, null);
    } // buildRelations()

} // EventTaggedPersonsTableMap
