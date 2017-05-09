<?php

namespace CoreBundle\Model;

use CoreBundle\Model\om\BaseEventTaggedPersonsQuery;

class EventTaggedPersonsQuery extends BaseEventTaggedPersonsQuery
{
    static function _findById($id)
    {
        return
            self::create()
                ->findPk($id);
    }

    static function _findOneByEventAndEmployee($eventId, $empId)
    {
        return
            self::create()
                ->filterByEventId($eventId)
                ->filterByEmpId($empId)
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
