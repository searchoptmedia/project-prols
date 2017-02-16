<?php

namespace CoreBundle\Model;

use CoreBundle\Model\om\BaseEmpCapabilitiesPeer;
use \Criteria;

class EmpCapabilitiesPeer extends BaseEmpCapabilitiesPeer
{
    public static function getEmpCapabilities($id, Criteria $c = null){
        if (is_null($c)) {
            $c = new Criteria();
        }

        $c->add(self::EMPID, $id, Criteria::EQUAL);


        $_self = self::doSelect($c);

        return $_self ? $_self : array();
    }

    public static function getAllEmpCapabilities(Criteria $c = null)
    {
        if(is_null($c)){
            $c = new Criteria();
        }
        $_self = self::doSelect($c);

        return $_self ? $_self : array();
    }
}
