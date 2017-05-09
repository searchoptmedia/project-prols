<?php

namespace CoreBundle\Model;

use CoreBundle\Model\om\BaseListEventsPeer;

use \Criteria;

class ListEventsPeer extends BaseListEventsPeer
{

    public static function getAllEvents($userid, $role = 0, Criteria $c = null)
    {
        if(is_null($c)){
            $c = new Criteria();
        }

        $c->addJoin(self::ID, EventTaggedPersonsPeer::EVENT_ID, Criteria::INNER_JOIN);
        $c->setDistinct(self::ID);

        $c1 = $c->getNewCriterion(EventTaggedPersonsPeer::EMP_ID, $userid, Criteria::EQUAL);
        $c2 = $c->getNewCriterion(ListEventsPeer::CREATED_BY, $userid, Criteria::EQUAL);
        $c1->addOr($c2);
        $c->add($c1);

        $c->addDescendingOrderByColumn(self::DATE_CREATED);

        $_self = self::doSelect($c);

        return $_self ? $_self : array();
    }
}
