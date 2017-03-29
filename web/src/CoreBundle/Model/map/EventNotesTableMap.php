<?php

namespace CoreBundle\Model\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'event_notes' table.
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
class EventNotesTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.CoreBundle.Model.map.EventNotesTableMap';

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
        $this->setName('event_notes');
        $this->setPhpName('EventNotes');
        $this->setClassname('CoreBundle\\Model\\EventNotes');
        $this->setPackage('src.CoreBundle.Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addForeignKey('event_id', 'EventId', 'INTEGER', 'list_events', 'id', true, null, null);
        $this->addColumn('note', 'Note', 'LONGVARCHAR', true, 32700, null);
        $this->addForeignKey('created_by', 'CreatedBy', 'INTEGER', 'emp_acc', 'id', true, null, null);
        $this->addColumn('date_created', 'DateCreated', 'TIMESTAMP', true, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('EmpAcc', 'CoreBundle\\Model\\EmpAcc', RelationMap::MANY_TO_ONE, array('created_by' => 'id', ), null, null);
        $this->addRelation('ListEvents', 'CoreBundle\\Model\\ListEvents', RelationMap::MANY_TO_ONE, array('event_id' => 'id', ), null, null);
    } // buildRelations()

} // EventNotesTableMap
