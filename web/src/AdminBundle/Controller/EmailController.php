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


class EmailController extends Controller
{

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
        $param  = $req->request->all();
        $empid  = $param['empId'];
        $reqName =   $param['requestname'];
        $user   = $class->getUser();
        $id     = $user->getId();

        $employee = EmpAccPeer::retrieveByPK($empid);

        if(! empty($employee))
        {
            $empemail = $employee->getEmail();
            $empinfo = EmpProfilePeer::getInformation($employee->getId());
            $empname = $empinfo->getFname() . " " .$empinfo->getLname();

            //admin profile information
            $data = EmpProfilePeer::getInformation($id);
            $name = $data->getFname(). " " .$data->getLname();

            $subject = $req->request->get('requestname') . " " . " Request Accepted";
            $from    = array('no-reply@searchoptmedia.com', 'PROLS');
            $to      = array($empemail);

            $inputMessage = "Hi " . $empname . "!<br><br>Your <b>" . $reqName .
                "</b> request was accepted by <b>". $name .
                "</b><br><br><b>Request Info: </b><br>Date started: " . $req->request->get('datestart') .
                "<br>Date ended: ". $req->request->get('dateend');

            $email = self::sendEmail($class, $subject, $from, $to, $inputMessage);

        }
        else
        {

        }
        return $email ? 1: 0;
    }
    public function RequestMeetingEmail($req, $class)
    {
        $user = $class->getUser();
        $id   = $user->getId();
        $empemail = $req->request->get('taggedemail');
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
    //notify user that he/she request for meeting
    public function notifyEmployeeRequest($req, $class)
    {
        $user = $class->getUser();
        $id = $user->getId();

        $empinfo = EmpProfilePeer::getInformation($id);
        $empname = $empinfo->getFname() . " " . $empinfo->getLname();


    }
    public function requestTypeEmail($req, $class)
    {
        $user = $class->getUser();
        $id   = $user->getId();

        
        $empinfo = EmpProfilePeer::getInformation($id);
        $empname = $empinfo->getFname() . " " . $empinfo->getLname();


        if(empty($req->request->get('typeleave'))){
            $requestlist = ListRequestTypePeer::retrieveByPK(4);
        }else{
            $requestlist = ListRequestTypePeer::retrieveByPK($req->request->get('typeleave'));

        }
        $requesttype = $requestlist->getRequestType();

        $taggedemail = $req->request->get('taggedemail');

        $admins = EmpAccPeer::getAdminInfo();
        $adminemails = array();
        foreach ($admins as $admin){
            $adminemails[] = $admin->getEmail();
        }

        $subject = $requesttype . " Request";
        $from    = array('no-reply@searchoptmedia.com', 'PROLS');
        $to      = array($adminemails,$taggedemail);


        $inputMessage = "<strong>Hi admin!" . "<br><b></strong>" . $empname . "</b> has requested for a <b>" . $requesttype . "</b>." .
        "<br><br>" . "Click the link below to view the pending request" . "<br>http://login.propelrr.com/requests";
        
        $email = self::sendEmail($class, $subject, $from, $to, $inputMessage);

        return $email ? 1: 0;
    }
    
    public function addEmployeeEmail($req, $class){
        $user = $class->getUser();
        $id   = $user->getId();

        $employeeemail = $req->request->get('email');
        $empname = $req->request->get('fname') . " " . $req->request->get('lname');
        $empusername = $req->request->get('username');
        $emppassword = $req->request->get('password');


        $subject = "PROLS Account";
        $from    = array('no-reply@searchoptmedia.com', 'PROLS');
        $to      = array($employeeemail);

        $inputMessage = "Hi ". $empname . "!<br> Your account was successfully created.<br><br> Username: <b>" . $empusername . "</b>
        <br>Password: <b>" . $emppassword . "</b><br>You can change your password in your profile page once you log in.<br><br>
        <a href='http://login.propelrr.com/'>Login Here</a>";
        $email = self::sendEmail($class, $subject, $from, $to, $inputMessage);

        return $email ? 1: 0;
    }

    public function adminEditEmployeeProfileEmail($req, $class){
        $user = $class->getUser();
        $id   = $user->getId();

        $adminprofile = EmpProfilePeer::getInformation($id);
        $adminname = $adminprofile->getFname() . " " . $adminprofile->getLname();

        $employeeemail = $req->request->get('email');
        $empname = $req->request->get('fname') . " " . $req->request->get('lname');
        $empusername = $req->request->get('username');
        $emppassword = $req->request->get('password');


        $subject = "PROLS Account Updated";
        $from    = array('no-reply@searchoptmedia.com', 'PROLS');
        $to      = array($employeeemail);

        $inputMessage = "Hi ". $empname . "!<br> Your account was updated by ". $adminname .".<br><br> See changes <a href='http://login.propelrr.com/profile'>here</a>";
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

?>