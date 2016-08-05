<?php

/**
 * Created by PhpStorm.
 * User: admin
 * Date: 8/5/2016
 * Time: 10:02 PM
 */
namespace AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class  extends Controller
{
    static public function getUserIP()
    {
        $host = $this->getRequest()->getHost();

        
    }

}