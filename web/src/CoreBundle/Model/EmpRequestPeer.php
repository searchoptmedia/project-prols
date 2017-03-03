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

        $c1 = $c->getNewCriterion(self::EMP_ACC_ID, $id, Criteria::EQUAL);
        $c2 = $c->getNewCriterion(self::LIST_REQUEST_TYPE_ID, 4, Criteria::EQUAL);
        $c1->addAnd($c2);

        $c3 = $c->getNewCriterion(self::LIST_REQUEST_TYPE_ID, 4, Criteria::NOT_EQUAL);
        $c3->addOr($c1);

        $c->add($c3);
        $c->addDescendingOrderByColumn(self::ID);

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
}
