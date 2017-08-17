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

    static function _findAll($params = array())
    {
        $data = self::create();

        foreach($params as $k=>$d) {
            if($k=='status' && isset($d['data'])) {
                if(isset($d['_or']) && $d['_or'])
                    $data->_or();
                else if(isset($d['_and']) && $d['_and'])
                    $data->_and();

                $data->filterByStatus($d['data'], isset($d['criteria']) ? $d['criteria'] : \Criteria::EQUAL);
            }
        }

        return $data->find();
    }
}
