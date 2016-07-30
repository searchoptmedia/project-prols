<?php

namespace CoreBundle\Model\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'emp_leave' table.
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
class EmpLeaveTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.CoreBundle.Model.map.EmpLeaveTableMap';

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
        $this->setName('emp_leave');
        $this->setPhpName('EmpLeave');
        $this->setClassname('CoreBundle\\Model\\EmpLeave');
        $this->setPackage('src.CoreBundle.Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('request', 'Request', 'VARCHAR', true, 100, null);
        $this->addColumn('status', 'Status', 'VARCHAR', true, 45, null);
        $this->addColumn('date_started', 'DateStarted', 'TIMESTAMP', true, null, null);
        $this->addColumn('date_ended', 'DateEnded', 'TIMESTAMP', true, null, null);
        $this->addForeignKey('emp_acc_id', 'EmpAccId', 'INTEGER', 'emp_acc', 'id', true, null, null);
        $this->addForeignKey('list_leave_type_id', 'ListLeaveTypeId', 'INTEGER', 'list_leave_type', 'id', true, null, null);
        $this->addForeignKey('admin_id', 'AdminId', 'INTEGER', 'emp_acc', 'id', true, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('EmpAccRelatedByEmpAccId', 'CoreBundle\\Model\\EmpAcc', RelationMap::MANY_TO_ONE, array('emp_acc_id' => 'id', ), null, null);
        $this->addRelation('ListLeaveType', 'CoreBundle\\Model\\ListLeaveType', RelationMap::MANY_TO_ONE, array('list_leave_type_id' => 'id', ), null, null);
        $this->addRelation('EmpAccRelatedByAdminId', 'CoreBundle\\Model\\EmpAcc', RelationMap::MANY_TO_ONE, array('admin_id' => 'id', ), null, null);
    } // buildRelations()

} // EmpLeaveTableMap
