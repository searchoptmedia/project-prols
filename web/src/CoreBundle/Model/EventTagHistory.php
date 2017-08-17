<?php

namespace CoreBundle\Model;

use CoreBundle\Model\om\BaseEventTagHistory;

class EventTagHistory extends BaseEventTagHistory
{
    function _save($data = array(), $class = null)
    {
        $classQry = $class;

        if(!$class)
            $class = $this;

        if(isset($data['history_id'])) $class->setHistoryId($data['history_id']);
        if(isset($data['event_tag_id'])) $class->setEventTagId($data['event_tag_id']);
        if(isset($data['status'])) $class->setStatus($data['status']);
        if(isset($data['message'])) $class->setMessage($data['message']);
        if(isset($data['date_created'])) $class->setDateCreated($data['date_created']);

        if($class->save())
            return $class;
        else if($classQry)
            return $classQry;
        else
            return null;
    }
}
