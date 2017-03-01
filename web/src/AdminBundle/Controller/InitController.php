<?php

/**
 * Created by PhpStorm.
 * User: admin
 * Date: 8/5/2016
 * Time: 10:02 PM
 */
namespace AdminBundle\Controller;

use CoreBundle\Model\EmpTimePeer;
use CoreBundle\Model\ListIpPeer;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\Session;

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

    /**
     * @param $token
     */
    static function loginSetTimeSession($token)
    {
        $user       = $token->getUser();
        $id         = $user->getId();
        $session    = new Session();
        $timedata   = EmpTimePeer::getEmpLastTimein($id);

        $session->set('timeLogCase', 'no-record');

        if(!empty($timedata)) {
            $date = date('Y/m/d H:i:s');
            $timein = $timedata->getTimeIn();
            $dateTimeIn = $timein->format('Y/m/d H:i:s');

            $datetime1 = date_create($date);
            $datetime2 = date_create($dateTimeIn);
            $interval = date_diff($datetime1, $datetime2);

            $tmp_inh = intval($interval->format('%h'));
            $tmp_ind = intval($interval->format('%d'));

            $hours = $tmp_inh + ($tmp_ind * 24);

            $timeoutData = $timedata->getTimeOut();


            //check if not yet timed out
            if (empty($timeoutData)) {
                $session->set('timeLogCase', 'timed-in');
                //if currently within 16 hours, check if another day
                if ($hours <= 16) {
                    $timeInDate = $timein->format('Y/m/d');
                    $dateToday = date('Y/m/d');

                    if ($timeInDate == $dateToday)
                        $session->set('isSameDay', 'true');
                } //if more than 16 hours and not yet timed out
                else {
                    //auto-time out by 12am +1day
                    $timedout = $timein->modify('+1 day')->format('Y/m/d 00:00:00');
                    $timedata->setTimeOut($timedout);

                    if ($timedata->save()) {
                        $session->set('timeout', 'true');
                        $session->set('isSameDay', '');
                        $session->set('isAutoTimeOut', 'true');
                        $session->set('autoTimeOutDate', $timedout);
                    }
                }
            } else {
                $session->set('timeLogCase', 'timed-out');

                $timeInDate = $timein->format('Y/m/d');
                $dateToday = date('Y/m/d');

                if ($timeInDate == $dateToday)
                    $session->set('isSameDay', 'true');
            }
        }
    }

}