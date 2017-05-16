<?php

namespace CoreBundle\Model;

use CoreBundle\Model\om\BaseEmpProfileQuery;

class EmpProfileQuery extends BaseEmpProfileQuery
{
    static function _findByAccId($accId)
    {
        return
            self::create()
                ->filterByEmpAccAccId($accId)
            ->findOne();
    }

}
