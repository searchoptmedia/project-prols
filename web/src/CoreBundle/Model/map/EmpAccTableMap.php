<?php

namespace CoreBundle\Model\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'emp_acc' table.
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
class EmpAccTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.CoreBundle.Model.map.EmpAccTableMap';

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
        $this->setName('emp_acc');
        $this->setPhpName('EmpAcc');
        $this->setClassname('CoreBundle\\Model\\EmpAcc');
        $this->setPackage('src.CoreBundle.Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('username', 'Username', 'VARCHAR', true, 45, null);
        $this->addColumn('password', 'Password', 'VARCHAR', true, 45, null);
        $this->addColumn('timestamp', 'Timestamp', 'TIMESTAMP', true, null, null);
        $this->addColumn('ip_add', 'IpAdd', 'VARCHAR', true, 45, null);
        $this->addColumn('status', 'Status', 'VARCHAR', true, 45, null);
        $this->addColumn('email', 'Email', 'VARCHAR', true, 45, null);
        $this->addColumn('role', 'Role', 'VARCHAR', true, 45, null);
        $this->addColumn('key', 'Key', 'VARCHAR', false, 255, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('EmpLeaveRelatedByEmpAccId', 'CoreBundle\\Model\\EmpLeave', RelationMap::ONE_TO_MANY, array('id' => 'emp_acc_id', ), null, null, 'EmpLeavesRelatedByEmpAccId');
        $this->addRelation('EmpLeaveRelatedByAdminId', 'CoreBundle\\Model\\EmpLeave', RelationMap::ONE_TO_MANY, array('id' => 'admin_id', ), null, null, 'EmpLeavesRelatedByAdminId');
        $this->addRelation('EmpProfile', 'CoreBundle\\Model\\EmpProfile', RelationMap::ONE_TO_MANY, array('id' => 'emp_acc_acc_id', ), null, null, 'EmpProfiles');
        $this->addRelation('EmpTime', 'CoreBundle\\Model\\EmpTime', RelationMap::ONE_TO_MANY, array('id' => 'emp_acc_acc_id', ), null, null, 'EmpTimes');
    } // buildRelations()

} // EmpAccTableMap
