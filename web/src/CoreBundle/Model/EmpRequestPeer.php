<?php

namespace CoreBundle\Model;

use CoreBundle\Model\om\BaseEmpRequestPeer;
use \Criteria;
class EmpRequestPeer extends BaseEmpRequestPeer
{
    public static function getAllRequest(Criteria $c = null)
    {
        if(is_null($c)){
            $c = new Criteria();
        }

        $c->addDescendingOrderByColumn(self::ID);
        $c->setLimit(20);

        $_self = self::doSelect($c);

        return $_self ? $_self : array();
    }
}
