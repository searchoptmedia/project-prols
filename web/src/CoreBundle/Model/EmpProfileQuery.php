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

    /**
     * @param array $params     filters: status; general info
     * @param string $find      options: all/one/count
     * @return int/obj
     */
    static function _findAll($params = array(), $find = 'all')
    {
        $data = self::create();

        if(!empty($params['status']['data'])) {
            $data
                ->useEmpAccQuery()
                    ->filterByStatus($params['status']['data'], isset($params['status']['criteria']) ? $params['status']['criteria'] : \Criteria::EQUAL)
                ->endUse();
        }

        if($find=='count')
            return $data->count();
        if($find=='one')
            return $data->findOne();

        return $data->find();
    }
}
