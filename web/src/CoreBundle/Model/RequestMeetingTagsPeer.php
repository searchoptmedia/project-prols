<?php

namespace CoreBundle\Model;

use CoreBundle\Model\om\BaseRequestMeetingTagsPeer;
use \Criteria;

class RequestMeetingTagsPeer extends BaseRequestMeetingTagsPeer
{
    public static function getTaggedRequest($id ,Criteria $c = null)
    {
        if (is_null($c)) {
            $c = new Criteria();
        }
        $c->addJoin(self::REQUEST_ID, EmpRequestPeer::ID);
        $c->add(self::EMP_ACC_ID, $id, Criteria::EQUAL);

        $_self = self::doSelect($c);

        return $_self ? $_self : array();
    }
}
