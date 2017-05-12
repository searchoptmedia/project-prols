<?php

namespace CoreBundle\Model;

use CoreBundle\Model\om\BaseEventTaggedPersonsQuery;
use CoreBundle\Utilities\Constant as C;

class EventTaggedPersonsQuery extends BaseEventTaggedPersonsQuery
{
    static function _findById($id)
    {
        return
            self::create()
                ->findPk($id);
    }

    static function _findOneByEventAndEmployee($eventId, $empId, $status = null, $criteria = \Criteria::NOT_EQUAL)
    {
        return
            self::create()
                ->filterByEventId($eventId)
                ->filterByEmpId($empId)
                ->filterByStatus($status, $criteria)
            ->findOne();
    }

    static function _findAllByEvent($eventId)
    {
        return
            self::create()
                ->filterByEventId($eventId)
                ->find();
    }
}
