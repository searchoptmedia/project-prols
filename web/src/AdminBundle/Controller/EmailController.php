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
        //employee profile information
        $data = EmpProfilePeer::getInformation($id);
        $name = $data->getFname(). " " .$data->getLname();

        $subject = 'Declined Request';
        $from    = array('no-reply@searchoptmedia.com', 'PROLS');
        $to      = array($empemail);

        $inputMessage = "Your request was declined<br><br><strong>Reason: </strong><br>" . $req->request->get('content');

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