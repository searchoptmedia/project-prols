<?php

namespace CoreBundle\Model;

use CoreBundle\Model\om\BaseEmpTimeQuery;

use \Criteria;

class EmpTimeQuery extends BaseEmpTimeQuery
{
    public static function _findAll($params = array(), $find = 'all')
    {
        $data = self::create();

        if(isset($params['employee_id']['data'])) {
            $data->filterByEmpAccAccId($params['employee_id']['data'], isset($params['status']['criteria']) ? $params['status']['criteria'] : \Criteria::EQUAL);
        }

        if(isset($params['table_sort']['data'])) {
            if($params['table_sort']['criteria']=='DESC') $data->addDescendingOrderByColumn($params['table_sort']['data']);
            else $data->addAscendingOrderByColumn($params['table_sort']['data']);
        }

        if($find=='count')
            return $data->count();
        if($find=='one')
            return $data->findOne();

        return $data->find();

    }
}
