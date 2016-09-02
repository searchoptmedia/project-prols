<?php
/**
 * Created by PhpStorm.
 * User: Propelrr-AJ
 * Date: 09/08/16
 * Time: 12:48 PM
 */

namespace AdminBundle\Controller;


use CoreBundle\Model\EmpAccPeer;
use CoreBundle\Model\EmpProfilePeer;
use CoreBundle\Model\EmpRequest;
use CoreBundle\Model\EmpRequestPeer;
use CoreBundle\Model\ListRequestTypePeer;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Swift_Mailer;
use Swift_SmtpTransport;
use Swift_Message;
use Symfony\Component\HttpFoundation\JsonResponse;


class EmailController extends Controller {

    public function sendTimeInRequest($req, $class)
    {
        $user = $class->getUser();
        $id   = $user->getId();

        //employee profile information
        $data = EmpProfilePeer::getInformation($id);
        $name = $data->getFname(). " " .$data->getLname();

        $admins = EmpAccPeer::getAdminInfo();
        $adminemails = array();
        foreach ($admins as $admin){
            $adminemails[] = $admin->getEmail();
        }
        $subject = 'Request for Access';
        $from    = array('no-reply@searchoptmedia.com', 'PROLS');
        $to      = array($adminemails);


        $inputMessage = "Hi admin! <br><br>" .
            $data->getFname(). " " .
            $data->getLname() . " has timed in outside the office.<br><br>".
                "<strong>Reason: </strong><br>" . $req->request->get('message');

        $email = self::sendEmail($class, $subject, $from, $to, $inputMessage);

        return $email ? 1: 0;
    }

    public function declinedRequestEmail($req, $class)
    {
        $user = $class->getUser();
        $id   = $user->getId();
        $emp = EmpAccPeer::retrieveByPK($req->request->get('empId'));
        $empemail = $emp->getEmail();
        $empinfo = EmpProfilePeer::getInformation($req->request->get('empId'));
        $empname = $empinfo->getFname() . " " . $empinfo->getLname();
        //admin profile information
        $data = EmpProfilePeer::getInformation($id);
        $name = $data->getFname(). " " .$data->getLname();

        $subject = $req->request->get('requestname') . " " . 'Request Declined';
        $from    = array('no-reply@searchoptmedia.com', 'PROLS');
        $to      = array($empemail);

        $inputMessage = "Hi " . $empname . "!<br><br>Your <b>" . $req->request->get('requestname') .
            "</b> request was declined by <b>" .$name . "</b>.".
            "<br><br><b>Request Info: </b><br>Date started: " . $req->request->get('datestart') .
            "<br>Date ended: ". $req->request->get('dateend').
            "<br><br><strong>Reason: </strong><br>" . $req->request->get('content');
        $email = self::sendEmail($class, $subject, $from, $to, $inputMessage);

        return $email ? 1: 0;
    }

    public function acceptRequestEmail($req, $class)
    {
        $user = $class->getUser();
        $id   = $user->getId();
        $emp = EmpAccPeer::retrieveByPK($req->request->get('empId'));
        $empemail = $emp->getEmail();
        $empinfo = EmpProfilePeer::getInformation($req->request->get('empId'));
        $empname = $empinfo->getFname() . " " .$empinfo->getLname();
        //admin profile information
        $data = EmpProfilePeer::getInformation($id);
        $name = $data->getFname(). " " .$data->getLname();

        $subject = $req->request->get('requestname') . " " . " Request Accepted";
        $from    = array('no-reply@searchoptmedia.com', 'PROLS');
        $to      = array($empemail);

        $inputMessage = "Hi " . $empname . "!<br><br>Your <b>" . $req->request->get('requestname') .
            "</b> request was accepted by <b>". $name .
            "</b><br><br><b>Request Info: </b><br>Date started: " . $req->request->get('datestart') .
            "<br>Date ended: ". $req->request->get('dateend');

        $email = self::sendEmail($class, $subject, $from, $to, $inputMessage);

        return $email ? 1: 0;
    }

    public function requestTypeEmail($req, $class){
        $user = $class->getUser();
        $id   = $user->getId();

        $empinfo = EmpProfilePeer::getInformation($id);
        $empname = $empinfo->getFname() . " " . $empinfo->getLname();

        $requestlist = ListRequestTypePeer::retrieveByPK($req->request->get('typeleave'));
        $requesttype = $requestlist->getRequestType();

        $admins = EmpAccPeer::getAdminInfo();
        $adminemails = array();
        foreach ($admins as $admin){
            $adminemails[] = $admin->getEmail();
        }

        $subject = $requesttype . " Request";
        $from    = array('no-reply@searchoptmedia.com', 'PROLS');
        $to      = array($adminemails);

        $inputMessage = "Hi admin!" . "<br><b>" . $empname . "</b> has requested for a <b>" . $requesttype . "</b>." .
        "<br><br>" . "Click the link below to view the pending request" . "<br>http://login.propelrr.com/requests";
        $email = self::sendEmail($class, $subject, $from, $to, $inputMessage);

        return $email ? 1: 0;
    }

    static public function sendEmail($class, $subject, $from, $to, $content)
    {
        $response = false;

        $message = new Swift_Message($subject);
        $message->setFrom($from[0]);
        $message->setBody($content, 'text/html');
        $message->setTo($to[0]);
    
        $response = $class->get('mailer')->send($message, $failures);

        return $response;

    }

}