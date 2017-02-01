<?php

/**
 * Created by PhpStorm.
 * User: admin
 * Date: 8/5/2016
 * Time: 10:02 PM
 */
namespace AdminBundle\Controller;

use CoreBundle\Model\ListIpPeer;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class  InitController extends Controller
{
    /**
     * Get environment - local or live
     *
     * @return string
     */
    static public function getAppEnv()
    {
        $serverAdd = $_SERVER['SERVER_ADDR'];

        if($serverAdd == "127.0.0.1")
            $env = 'local';
        else $env = 'live';
        
        return $env;
    }

    /**
     * Get user IP
     *
     * @param $class
     * @return string
     */
    static public function getUserIP($class, $ip = null)
    {
        if(self::getAppEnv() == 'local')
            $ip = $class->container->get('request')->getClientIp();
        else
            $ip = $class->getRequest()->server->get('HTTP_X_FORWARDED_FOR');

        return $ip;
    }

    /**
     * Check if Out of IP
     *
     * @param string
     * @return int
     */
    static public function checkIP($userip)
    {
        $ip_add = ListIpPeer::getValidIP($userip);

        if(!is_null($ip_add))
            $matchedip = $ip_add->getAllowedIp();
        else
            $matchedip = '';

        return $userip == $matchedip ? 1 : 0;
    }

}