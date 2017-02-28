<?php

namespace CoreBundle\Model\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'request_meeting_tags' table.
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
class RequestMeetingTagsTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.CoreBundle.Model.map.RequestMeetingTagsTableMap';

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
        $this->setName('request_meeting_tags');
        $this->setPhpName('RequestMeetingTags');
        $this->setClassname('CoreBundle\\Model\\RequestMeetingTags');
        $this->setPackage('src.CoreBundle.Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addForeignKey('request_id', 'RequestId', 'INTEGER', 'emp_request', 'id', false, null, null);
        $this->addColumn('status', 'Status', 'INTEGER', false, null, null);
        $this->addForeignKey('emp_acc_id', 'EmpAccId', 'INTEGER', 'emp_acc', 'id', false, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('EmpRequest', 'CoreBundle\\Model\\EmpRequest', RelationMap::MANY_TO_ONE, array('request_id' => 'id', ), null, null);
        $this->addRelation('EmpAcc', 'CoreBundle\\Model\\EmpAcc', RelationMap::MANY_TO_ONE, array('emp_acc_id' => 'id', ), null, null);
    } // buildRelations()

} // RequestMeetingTagsTableMap
