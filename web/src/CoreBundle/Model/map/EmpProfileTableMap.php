<?php

namespace CoreBundle\Model\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'emp_profile' table.
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
class EmpProfileTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.CoreBundle.Model.map.EmpProfileTableMap';

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
        $this->setName('emp_profile');
        $this->setPhpName('EmpProfile');
        $this->setClassname('CoreBundle\\Model\\EmpProfile');
        $this->setPackage('src.CoreBundle.Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addForeignKey('emp_acc_acc_id', 'EmpAccAccId', 'INTEGER', 'emp_acc', 'id', true, null, null);
        $this->addColumn('fname', 'Fname', 'VARCHAR', true, 45, null);
        $this->addColumn('lname', 'Lname', 'VARCHAR', true, 45, null);
        $this->addColumn('mname', 'Mname', 'VARCHAR', true, 45, null);
        $this->addColumn('bday', 'Bday', 'TIMESTAMP', true, null, null);
        $this->addColumn('address', 'Address', 'VARCHAR', true, 45, null);
        $this->addColumn('gender', 'Gender', 'VARCHAR', true, 10, null);
        $this->addColumn('img_path', 'ImgPath', 'VARCHAR', true, 45, null);
        $this->addColumn('date_joined', 'DateJoined', 'TIMESTAMP', true, null, null);
        $this->addColumn('emp_num', 'EmployeeNumber', 'VARCHAR', true, 45, null);
        $this->addForeignKey('list_dept_id', 'ListDeptDeptId', 'INTEGER', 'list_dept', 'id', true, null, null);
        $this->addForeignKey('list_pos_id', 'ListPosPosId', 'INTEGER', 'list_pos', 'id', true, null, null);
        $this->addColumn('status', 'Status', 'VARCHAR', true, 45, null);
        $this->addColumn('profile_status', 'ProfileStatus', 'INTEGER', true, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('EmpAcc', 'CoreBundle\\Model\\EmpAcc', RelationMap::MANY_TO_ONE, array('emp_acc_acc_id' => 'id', ), null, null);
        $this->addRelation('ListDept', 'CoreBundle\\Model\\ListDept', RelationMap::MANY_TO_ONE, array('list_dept_id' => 'id', ), null, null);
        $this->addRelation('ListPos', 'CoreBundle\\Model\\ListPos', RelationMap::MANY_TO_ONE, array('list_pos_id' => 'id', ), null, null);
        $this->addRelation('EmpContact', 'CoreBundle\\Model\\EmpContact', RelationMap::ONE_TO_MANY, array('id' => 'emp_profile_id', ), null, null, 'EmpContacts');
    } // buildRelations()

} // EmpProfileTableMap
