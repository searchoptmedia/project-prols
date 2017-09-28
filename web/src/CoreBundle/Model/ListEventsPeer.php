<?php

namespace CoreBundle\Model;

use CoreBundle\Model\om\BaseListEventsPeer;

use CoreBundle\Utilities\Constant;
use \Criteria;

class ListEventsPeer extends BaseListEventsPeer
{

    public static function getAllEvents($userid, $role = 0, Criteria $c = null)
    {
        if(is_null($c)){
            $c = new Criteria();
        }

        $c->addJoin(self::ID, EventTaggedPersonsPeer::EVENT_ID, Criteria::LEFT_JOIN);
        $c->setDistinct(self::ID);

        $c1 = $c->getNewCriterion(EventTaggedPersonsPeer::EMP_ID, $userid, Criteria::EQUAL);
        $c2 = $c->getNewCriterion(ListEventsPeer::CREATED_BY, $userid, Criteria::EQUAL);
        $c3 = $c->getNewCriterion(self::EVENT_TYPE, Constant::EVENT_HOLIDAY_ID);
        $c1->addOr($c2);
        $c3->addOr($c1);
        $c->add($c3);

        $c->addDescendingOrderByColumn(self::DATE_CREATED, Criteria::DESC);

        $_self = self::doSelect($c);

        return $_self ? $_self : array();
    }

    public static function getCalendarEvents(Criteria $c = null)
    {
        if(is_null($c)){
            $c = new Criteria();
        }

        $c->addJoin(self::ID, EventTaggedPersonsPeer::EVENT_ID, Criteria::LEFT_JOIN);
        $c->setDistinct(self::ID);

        $c->addDescendingOrderByColumn(self::DATE_CREATED, Criteria::DESC);

        $_self = self::doSelect($c);

        return $_self ? $_self : array();
    }

    public static function getOneByDate($date, Criteria $c = null)
    {
        if(is_null($c)){
            $c = new Criteria();
        }

        $c->add(self::FROM_DATE, $date, Criteria::LESS_EQUAL);
        $c->add(self::TO_DATE, $date, Criteria::GREATER_EQUAL);

        $_self = self::doSelectOne($c);

        return $_self ? $_self : array();
    }
}
