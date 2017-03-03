<?php

namespace CoreBundle\Model;

use CoreBundle\Model\om\BaseListEventsPeer;

use \Criteria;

class ListEventsPeer extends BaseListEventsPeer
{
    public static function getAllEvents(Criteria $c = null)
    {
        if(is_null($c)){
            $c = new Criteria();
        }

        $_self = self::doSelect($c);

        return $_self ? $_self : array();
    }
}
