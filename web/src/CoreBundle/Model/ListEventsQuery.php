<?php

namespace CoreBundle\Model;

use CoreBundle\Model\om\BaseListEventsQuery;

class ListEventsQuery extends BaseListEventsQuery
{
    static function _findById($id)
    {
        return
            self::create()
                ->filterById($id)
            ->findOne();
    }

    static function _findAll($params = array())
    {
        $data = self::create();

        if(!empty($params['date_started']['data'])) {
            $data->filterByFromDate($params['date_started']['data'], isset($params['date_started']['criteria']) ? $params['date_started']['criteria'] : \Criteria::GREATER_EQUAL );
        }

        if(!empty($params['date_ended']['data'])) {
            $data->filterByToDate($params['date_ended']['data'], isset($params['date_ended']['criteria']) ? $params['date_ended']['criteria'] : \Criteria::LESS_EQUAL );
        }

        return
            $data->find();
    }
}
