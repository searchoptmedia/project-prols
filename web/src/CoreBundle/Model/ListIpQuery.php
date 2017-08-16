<?php

namespace CoreBundle\Model;

use CoreBundle\Model\om\BaseListIpQuery;

class ListIpQuery extends BaseListIpQuery
{
    /**
     * @param array $params
     * @param string $find
     * @return array|ListIp|int|mixed|\PropelObjectCollection
     */
    static function _findAll($params = array(), $find = 'all')
    {
        $data = self::create();

        if(!empty($params['status']['data'])) {
            $data->filterByStatus($params['status']['data'], isset($params['status']['criteria']) ? $params['status']['criteria'] : \Criteria::EQUAL);
        }

        if($find=='one')
            return $data->findOne();
        if($find=='count')
            return $data->count();

        return $data->find();
    }
}
