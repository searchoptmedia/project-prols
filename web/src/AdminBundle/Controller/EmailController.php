<?php
/**
 * Created by PhpStorm.
 * User: Propelrr-AJ
 * Date: 09/08/16
 * Time: 12:48 PM
 */

namespace AdminBundle\Controller;


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

        $subject = 'Request for Access';
        $from    = array('denmark.mago@gmail.com' => 'Denmark');
        $to      = array(
            'christian.fallaria@searchoptmedia.com'  => 'Recipient1 Name',
        );

        $inputMessage = "Hi admin! <br><br>" .
            $data->getFname(). " " .
            $data->getLname() . " has timed in outside the office.<br><br>".
                "<strong>Reason: </strong><br>" . $req->request->get('message');

        $email = self::sendEmail($subject, $from, $to, $inputMessage);

        return $email ? 1: 0;
    }


    static public function sendEmail($subject, $from, $to, $content)
    {
        $env      = InitController::getAppEnv();
        $response = false;

        if($env == 'local'):
            $transport = Swift_SmtpTransport::newInstance('smtp.gmail.com', 465, 'ssl');
            $transport->setUsername('christian.fallaria@searchoptmedia.com');
            $transport->setPassword("baddonk123");
            $swift = Swift_Mailer::newInstance($transport);

            $message = new Swift_Message($subject);
            $message->setFrom($from);
            //$message->setBody($html, 'text/html');
            //$message->addPart($text, 'text/plain');
            $message->setBody($content, 'text/html');
            $message->setTo($to);

            $response = $swift->send($message, $failures);
        else:

        endif;

        return $response;

    }

}