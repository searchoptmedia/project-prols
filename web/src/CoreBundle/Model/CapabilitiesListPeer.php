<?php

namespace CoreBundle\Model;

use CoreBundle\Model\om\BaseCapabilitiesListPeer;
use \Criteria;

class CapabilitiesListPeer extends BaseCapabilitiesListPeer
{
    public static function getCapability($id, Criteria $c = null)
    {
        if (is_null($c)) {
            $c = new Criteria();
        }

        $c->add(self::ID, $id, Criteria::EQUAL);

        $_self = self::doSelectOne($c);

        return $_self ? $_self : null;

    }

    public static function getAllCapabilities(Criteria $c = null)
    {
        if(is_null($c)){
            $c = new Criteria();
        }
        $_self = self::doSelect($c);

        return $_self ? $_self : array();
    }
}
