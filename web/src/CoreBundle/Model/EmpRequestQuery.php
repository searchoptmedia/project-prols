<?php

namespace CoreBundle\Model;

use CoreBundle\Model\om\BaseEmpRequestQuery;
use CoreBundle\Utilities\Constant as C;

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

    static function _findAll($params = array(), $find = 'all') {

        $data = self::create();

        if(!empty($params['status']['data'])) {
            $data->filterByStatus($params['status']['data'], isset($params['status']['criteria']) ? $params['status']['criteria'] : \Criteria::EQUAL );
        }

        if(!empty($params['request_type']['data'])) {
            $data->filterByListRequestTypeId($params['request_type']['data'], isset($params['request_type']['criteria']) ? $params['request_type']['criteria'] : \Criteria::EQUAL );
        }

        if(!empty($params['date_started']['data'])) {
            $data->filterByDateStarted($params['date_started']['data'], isset($params['date_started']['criteria']) ? $params['date_started']['criteria'] : \Criteria::GREATER_EQUAL );
        }

        if(!empty($params['date_ended']['data'])) {
            $data->filterByDateEnded($params['date_ended']['data'], isset($params['date_ended']['criteria']) ? $params['date_ended']['criteria'] : \Criteria::LESS_EQUAL );
        }

        if($find=='one')
            return $data->findOne();
        else if($find=='count')
            return $data->count();
        return
            $data->find();
    }

}
