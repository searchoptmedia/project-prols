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
}
