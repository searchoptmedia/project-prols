<?php

namespace CoreBundle\Model\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'event_tag_history' table.
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
class EventTagHistoryTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.CoreBundle.Model.map.EventTagHistoryTableMap';

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
        $this->setName('event_tag_history');
        $this->setPhpName('EventTagHistory');
        $this->setClassname('CoreBundle\\Model\\EventTagHistory');
        $this->setPackage('src.CoreBundle.Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addForeignKey('history_id', 'HistoryId', 'INTEGER', 'history', 'id', true, null, null);
        $this->addForeignKey('event_tag_id', 'EventTagId', 'INTEGER', 'event_tagged_persons', 'id', true, null, null);
        $this->addColumn('status', 'Status', 'INTEGER', false, null, null);
        $this->addColumn('message', 'Message', 'LONGVARCHAR', false, null, null);
        $this->addColumn('date_created', 'DateCreated', 'TIMESTAMP', true, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('History', 'CoreBundle\\Model\\History', RelationMap::MANY_TO_ONE, array('history_id' => 'id', ), null, null);
        $this->addRelation('EventTaggedPersons', 'CoreBundle\\Model\\EventTaggedPersons', RelationMap::MANY_TO_ONE, array('event_tag_id' => 'id', ), null, null);
    } // buildRelations()

} // EventTagHistoryTableMap
