<?php

namespace CoreBundle\Model\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'emp_request' table.
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
class EmpRequestTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.CoreBundle.Model.map.EmpRequestTableMap';

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
        $this->setName('emp_request');
        $this->setPhpName('EmpRequest');
        $this->setClassname('CoreBundle\\Model\\EmpRequest');
        $this->setPackage('src.CoreBundle.Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('request', 'Request', 'VARCHAR', false, 100, null);
        $this->addColumn('status', 'Status', 'VARCHAR', false, 45, null);
        $this->addColumn('date_started', 'DateStarted', 'TIMESTAMP', false, null, null);
        $this->addColumn('date_ended', 'DateEnded', 'TIMESTAMP', false, null, null);
        $this->addForeignKey('emp_acc_id', 'EmpAccId', 'INTEGER', 'emp_acc', 'id', false, null, null);
        $this->addForeignKey('list_request_type_id', 'ListRequestTypeId', 'INTEGER', 'list_request_type', 'id', false, null, null);
        $this->addForeignKey('admin_id', 'AdminId', 'INTEGER', 'emp_acc', 'id', false, null, null);
        $this->addColumn('emp_time_id', 'EmpTimeId', 'INTEGER', false, null, null);
        $this->addColumn('meeting_title', 'MeetingTitle', 'VARCHAR', false, 100, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('EmpAccRelatedByEmpAccId', 'CoreBundle\\Model\\EmpAcc', RelationMap::MANY_TO_ONE, array('emp_acc_id' => 'id', ), null, null);
        $this->addRelation('ListRequestType', 'CoreBundle\\Model\\ListRequestType', RelationMap::MANY_TO_ONE, array('list_request_type_id' => 'id', ), null, null);
        $this->addRelation('EmpAccRelatedByAdminId', 'CoreBundle\\Model\\EmpAcc', RelationMap::MANY_TO_ONE, array('admin_id' => 'id', ), null, null);
        $this->addRelation('RequestMeetingTags', 'CoreBundle\\Model\\RequestMeetingTags', RelationMap::ONE_TO_MANY, array('id' => 'request_id', ), null, null, 'RequestMeetingTagss');
    } // buildRelations()

} // EmpRequestTableMap
