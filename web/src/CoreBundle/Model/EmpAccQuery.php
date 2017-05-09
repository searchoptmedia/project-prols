<?php

namespace CoreBundle\Model;

use CoreBundle\Model\om\BaseEmpAccQuery;
use CoreBundle\Utilities\Constant;

class EmpAccQuery extends BaseEmpAccQuery
{
    static function _findById($userId)
    {
        return
            self::create()
                ->findPk($userId);
    }

    static function _findByEmail($email, $status = Constant::STATUS_ACTIVE)
    {
        return
            self::create()
                ->filterByEmail($email)
                ->filterByStatus($status)
            ->findOne();
    }

    static function _findAll($status = Constant::STATUS_ACTIVE)
    {
        return
            self::create()
                ->filterByStatus($status)
            ->find();
    }
}
