<?php

namespace CoreBundle\Model;

use CoreBundle\Model\om\BaseEmpRequestPeer;
use \Criteria;
class EmpRequestPeer extends BaseEmpRequestPeer
{
    public static function getAllRequest($id, Criteria $c = null)
    {
        if(is_null($c)){
            $c = new Criteria();
        }

        $c->addDescendingOrderByColumn(self::ID);

        $c->add(self::STATUS, -1, Criteria::NOT_EQUAL);

        $_self = self::doSelect($c);

        return $_self ? $_self : array();
    }

    public static function getIndividualRequest($id ,Criteria $c = null)
    {
        if (is_null($c)) {
            $c = new Criteria();
        }

        $c->add(self::EMP_ACC_ID, $id, Criteria::EQUAL);
        $c->addDescendingOrderByColumn(self::ID);
        $c->add(self::STATUS, -1, Criteria::NOT_EQUAL);
        $_self = self::doSelect($c);

        return $_self ? $_self : array();
    }
    
    public static function getAllAcceptedRequest(Criteria $c = null)
    {
        if(is_null($c)){
            $c = new Criteria();
        }

        $c->add(self::STATUS, 3, Criteria::EQUAL);
        $c->addDescendingOrderByColumn(self::ID);

        $_self = self::doSelect($c);

        return $_self ? $_self : array();
    }

    public static function getEmpByTime($time, $empId, $status = 3, Criteria $c = null)
    {
        if(is_null($c)){
            $c = new Criteria();
        }

        $c->add(self::STATUS, $status);
        $c->add(self::EMP_ACC_ID, $empId);
        $c->add(self::DATE_STARTED, $time, Criteria::GREATER_EQUAL);
        $c->add(self::DATE_ENDED, $time, Criteria::LESS_EQUAL);

        $data = self::doSelectOne($c);

        return $data? $data : array();
    }
}
