<?php

namespace CoreBundle\Model;

use CoreBundle\Model\om\BaseEmpStatusTypePeer;
use \Criteria;

class EmpStatusTypePeer extends BaseEmpStatusTypePeer
{
    public static function getAllEmpStatus(Criteria $c = null)
    {
        if(is_null($c)){
            $c = new Criteria();
        }

        $_self = self::doSelect($c);

        return $_self ? $_self : array();
    }

    public static function getEmpStatus($id, Criteria $c = null)
    {
        if (is_null($c)) {
            $c = new Criteria();
        }

        $c->add(self::ID, $id, Criteria::EQUAL);

        $_self = self::doSelectOne($c);

        return $_self ? $_self : null;

    }
}
