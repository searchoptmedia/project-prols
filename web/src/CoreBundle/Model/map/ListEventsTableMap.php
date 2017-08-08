<?php

namespace CoreBundle\Model\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'list_events' table.
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
class ListEventsTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.CoreBundle.Model.map.ListEventsTableMap';

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
        $this->setName('list_events');
        $this->setPhpName('ListEvents');
        $this->setClassname('CoreBundle\\Model\\ListEvents');
        $this->setPackage('src.CoreBundle.Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addForeignKey('created_by', 'CreatedBy', 'INTEGER', 'emp_acc', 'id', true, null, null);
        $this->addColumn('date_created', 'DateCreated', 'TIMESTAMP', true, null, null);
        $this->addColumn('from_date', 'FromDate', 'TIMESTAMP', true, null, null);
        $this->addColumn('to_date', 'ToDate', 'TIMESTAMP', true, null, null);
        $this->addColumn('event_name', 'EventName', 'VARCHAR', true, 45, null);
        $this->addColumn('event_venue', 'EventVenue', 'VARCHAR', false, 255, null);
        $this->addColumn('event_desc', 'EventDescription', 'LONGVARCHAR', true, 32700, null);
        $this->addForeignKey('event_type', 'EventType', 'INTEGER', 'list_events_type', 'id', true, null, null);
        $this->addColumn('status', 'Status', 'INTEGER', true, null, null);
        $this->addColumn('is_going', 'IsGoing', 'INTEGER', true, null, null);
        $this->addColumn('is_going_note', 'IsGoingNote', 'LONGVARCHAR', false, null, null);
        $this->addColumn('sms_response', 'SmsResponse', 'LONGVARCHAR', false, 32700, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('ListEventsType', 'CoreBundle\\Model\\ListEventsType', RelationMap::MANY_TO_ONE, array('event_type' => 'id', ), null, null);
        $this->addRelation('EmpAcc', 'CoreBundle\\Model\\EmpAcc', RelationMap::MANY_TO_ONE, array('created_by' => 'id', ), null, null);
        $this->addRelation('EventNotes', 'CoreBundle\\Model\\EventNotes', RelationMap::ONE_TO_MANY, array('id' => 'event_id', ), null, null, 'EventNotess');
        $this->addRelation('EventTaggedPersons', 'CoreBundle\\Model\\EventTaggedPersons', RelationMap::ONE_TO_MANY, array('id' => 'event_id', ), null, null, 'EventTaggedPersonss');
        $this->addRelation('EventAttachment', 'CoreBundle\\Model\\EventAttachment', RelationMap::ONE_TO_MANY, array('id' => 'event_id', ), null, null, 'EventAttachments');
    } // buildRelations()

} // ListEventsTableMap
