<?php

namespace CoreBundle\Model;

use CoreBundle\Model\om\BaseHistory;

class History extends BaseHistory
{
    function _save($data = array(), $class = null)
    {
        $classQry = $class;

        if(!$class)
            $class = $this;

        if(isset($data['module'])) $class->setModule($data['module']);
        if(isset($data['action'])) $class->setAction($data['action']);

        if($class->save())
            return $class;
        else if($classQry)
            return $classQry;
        else
            return null;
    }
}
