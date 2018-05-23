<?php

namespace CoreBundle\Model\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'biometric_processed_logs' table.
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
class BiometricProcessedLogsTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.CoreBundle.Model.map.BiometricProcessedLogsTableMap';

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
        $this->setName('biometric_processed_logs');
        $this->setPhpName('BiometricProcessedLogs');
        $this->setClassname('CoreBundle\\Model\\BiometricProcessedLogs');
        $this->setPackage('src.CoreBundle.Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'id', 'INTEGER', true, null, null);
        $this->addColumn('C_Date', 'C_Date', 'VARCHAR', false, 8, null);
        $this->addColumn('C_Time', 'C_Time', 'VARCHAR', false, 8, null);
        $this->addColumn('L_TID', 'L_TID', 'INTEGER', false, null, null);
        $this->addColumn('L_UID', 'L_UID', 'INTEGER', false, null, null);
        $this->addForeignKey('emp_time_id', 'emp_time_id', 'INTEGER', 'emp_time', 'id', false, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('EmpTime', 'CoreBundle\\Model\\EmpTime', RelationMap::MANY_TO_ONE, array('emp_time_id' => 'id', ), null, null);
    } // buildRelations()

} // BiometricProcessedLogsTableMap