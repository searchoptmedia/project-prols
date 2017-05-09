<?php

namespace CoreBundle\Model;

use CoreBundle\Model\om\BaseEventTaggedPersons;

class EventTaggedPersons extends BaseEventTaggedPersons
{
    function _save($data = array(), $class = null)
    {
        $classQry = $class;

        if(! $class)
            $class = $this;

        if(isset($data['event_id'])) $class->setEventId($data['event_id']);
        if(isset($data['emp_id'])) $class->setEmpId($data['emp_id']);
        if(isset($data['status'])) $class->setStatus($data['status']);

        if($class->save())
            return $class;
        else if($classQry)
            return $classQry;
        else
            return null;
    }
}
