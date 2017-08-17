<?php

namespace CoreBundle\Model;

use CoreBundle\Model\om\BaseEventTagHistoryQuery;

class EventTagHistoryQuery extends BaseEventTagHistoryQuery
{
    static function _find($params = array())
    {
        $data = self::create()
            ->joinHistory();

        if(isset($params['TagID'])) {
            if(isset($params['TagID']['value'])) {
                $data->filterByEventTagId($params['TagID']['value'], (isset($params['TagID']['criteria']) ? $params['TagID']['criteria'] : \Criteria::EQUAL));
            }
        }

        if(isset($params['order'])) {
            $data->orderBy($params['order']['value'], $params['order']['criteria']);
        }

        return $data->find();
    }

    static function _findByActionAndTag($tagId, $action, $module = 'event')
    {
        return
            self::create()
                ->filterByEventTagId($tagId)
                ->useHistoryQuery()
                    ->filterByAction($action)
                    ->filterByModule($module)
                ->endUse()
            ->findOne();
    }
}
