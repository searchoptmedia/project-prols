<?php

namespace CoreBundle\Model;

use CoreBundle\Model\om\BaseHistoryQuery;

class HistoryQuery extends BaseHistoryQuery
{
    static function _findModuleAction($module, $action)
    {
        return
            self::create()
                ->filterByModule($module)
                ->filterByAction($action)
            ->findOne();
    }
}
