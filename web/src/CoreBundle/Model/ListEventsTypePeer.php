<?php

namespace CoreBundle\Model;

use CoreBundle\Model\om\BaseListEventsTypePeer;
use \Criteria;

class ListEventsTypePeer extends BaseListEventsTypePeer
{
    public static function getEventType($id, Criteria $c = null){
        if (is_null($c)) {
            $c = new Criteria();
        }

        $c->add(self::ID, $id, Criteria::EQUAL);

        $_self = self::doSelectOne($c);

        return $_self ? $_self : null;

    }

    public static function getAllEventType(Criteria $c = null)
    {
        if(is_null($c)){
            $c = new Criteria();
        }

        $_self = self::doSelect($c);

        return $_self ? $_self : array();
    }
}
