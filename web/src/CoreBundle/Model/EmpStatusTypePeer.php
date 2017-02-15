<?php

namespace CoreBundle\Model;

use CoreBundle\Model\om\BaseEmpStatusTypePeer;

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
}
