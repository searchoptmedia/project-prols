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
            $data->filterByEmpAccAccId($params['employee_id']['data'], isset($params['employee_id']['criteria']) ? $params['employee_id']['criteria'] : \Criteria::EQUAL);
        }
        if(isset($params['status']['data'])) {
            $data->filterByStatus($params['status']['data'], isset($params['status']['criteria']) ? $params['status']['criteria'] : \Criteria::EQUAL);
        }
        if(isset($params['date']['data'])) {
            $data->filterByDate($params['date']['data'], isset($params['date']['criteria']) ? $params['date']['criteria'] : \Criteria::EQUAL);
        }

        if(isset($params['table_sort']['data'])) {
            if($params['table_sort']['criteria']=='DESC') $data->addDescendingOrderByColumn($params['table_sort']['data']);
            else $data->addAscendingOrderByColumn($params['table_sort']['data']);
        }

        if(!empty($params['limit']))
            $data->limit($params['limit']);

        if($find=='count')
            return $data->count();
        if($find=='one')
            return $data->findOne();

        return $data->find();

    }

    static function _findOneById($id)
    {
        return self::create()
            ->findPk($id);
    }
}
