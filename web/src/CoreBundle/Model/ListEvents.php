<?php

namespace CoreBundle\Model;

use CoreBundle\Model\om\BaseListEvents;

class ListEvents extends BaseListEvents
{
    function _save($data = array(), $class = null)
    {
        $classQry = $class;

        if(! $class)
            $class = $this;

        if(isset($data['event_name'])) $class->setEventName($data['event_name']);
        if(isset($data['event_desc'])) $class->setEventDescription($data['event_desc']);
        if(isset($data['event_venue'])) $class->setEventVenue($data['event_venue']);
        if(isset($data['event_type'])) $class->setEventType($data['event_type']);
        if(isset($data['status'])) $class->setStatus($data['status']);
        if(isset($data['created_by'])) $class->setCreatedBy($data['created_by']);
        if(isset($data['date_created'])) $class->setDateCreated($data['date_created']);
        if(isset($data['from_date'])) $class->setFromDate($data['from_date']);
        if(isset($data['to_date'])) $class->setToDate($data['to_date']);

        if($class->save())
            return $class;
        else if($classQry)
            return $classQry;
        else
            return null;
    }
}
