<?php

namespace CoreBundle\Model\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'emp_time' table.
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
class prols_emp_timeTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.CoreBundle.Model.map.prols_emp_timeTableMap';

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
        $this->setName('emp_time');
        $this->setPhpName('prols_emp_time');
        $this->setClassname('CoreBundle\\Model\\prols_emp_time');
        $this->setPackage('src.CoreBundle.Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('time_in1', 'TimeIn1', 'VARCHAR', true, 255, null);
        $this->addColumn('time_out1', 'TimeOut1', 'VARCHAR', true, 255, null);
        $this->addColumn('time_in2', 'TimeIn2', 'VARCHAR', true, 255, null);
        $this->addColumn('time_out2', 'TimeOut2', 'VARCHAR', true, 255, null);
        $this->addColumn('time_in_ot', 'TimeInOt', 'VARCHAR', true, 255, null);
        $this->addColumn('time_out_ot', 'TimeOutOt', 'VARCHAR', true, 255, null);
        $this->addColumn('ip_add', 'IpAdd', 'VARCHAR', true, 45, null);
        $this->addColumn('date', 'Date', 'VARCHAR', true, 255, null);
        $this->addColumn('acc_id', 'AccId', 'INTEGER', true, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
    } // buildRelations()

} // prols_emp_timeTableMap
