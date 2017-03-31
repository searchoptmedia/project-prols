<?php

namespace CoreBundle\Model;

use CoreBundle\Model\om\BaseEmpRequestQuery;

class EmpRequestQuery extends BaseEmpRequestQuery
{
    static function _getByStatusRequest($status)
    {
        return self::create()
            ->filterByStatus($status)
            ->find();
    }

    static function _getTotalByStatusRequest($status)
    {
        return count(self::_getByStatusRequest($status))?:0;
    }

}
