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
        $subject = 'PROLS » Request for Access';
        $from    = array('no-reply@searchoptmedia.com', 'PROLS');
        $message = $req->request->get('message');

        foreach ($admins as $admin){
            $adminemails[] = $admin->getEmail();
        }
        $to      = array($adminemails);
        $empName = $data->getFname() . ' ' . $data->getLname();
        $inputMessage = "
            <h3>Hi Admin!</h3> $empName has timed in outside the office.<br><br>
            <strong>Reason: </strong><br> $message <br><br><br>
            <a style='text-decoration:none;border:0px; padding: 15px 30px; background:#3498DB;color:#fff;font-weight:bold;font-size:14px;display:inline-block;' href='http://login.propelrr.com/requests'>View Request</a>";

        $email = self::sendEmail($class, $subject, $from, $to,
            $class->renderView('AdminBundle:Templates/Email:email-template.html.twig', array('message' => $inputMessage)));

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

        $subject = "PROLS » " . $req->request->get('requestname') . " " . 'Request Declined';
        $from    = array('no-reply@searchoptmedia.com', 'PROLS');
        $to      = array($empemail);

        $inputMessage = "<h3>Hi " . $empname . "!</h3>Your <b>" . $req->request->get('requestname') .
            "</b> request was declined by <b>" .$name . "</b>.".
            "<br><br><b>Request Info: </b><br>Date started: " . $req->request->get('datestart') .
            "<br>Date ended: ". $req->request->get('dateend').
            "<br><br><strong>Reason: </strong><br>" . $req->request->get('content');
        $email = self::sendEmail($class, $subject, $from, $to,
            $class->renderView('AdminBundle:Templates/Email:email-template.html.twig', array('message' => $inputMessage)));

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

            $subject = "PROLS » " . $req->request->get('requestname') . " " . " Request Accepted";
            $from    = array('no-reply@searchoptmedia.com', 'PROLS');
            $to      = array($empemail);

            $inputMessage = "<h2>Hi " . $empname . "!</h2><br><br>Your <b>" . $reqName .
                "</b> request was accepted by <b>". $name .
                "</b><br><br><b>Request Info: </b><br>Date started: " . $req->request->get('datestart') .
                "<br>Date ended: ". $req->request->get('dateend');

            $email = self::sendEmail($class, $subject, $from, $to,
                $class->renderView('AdminBundle:Templates/Email:email-template.html.twig', array('message' => $inputMessage)));

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

        $inputMessage = "<h2>Hi " . $empname . "!</h2><br><br>Your <b>" . $req->request->get('requestname') .
            "</b> request was accepted by <b>". $name .
            "</b><br><br><b>Request Info: </b><br>Date started: " . $req->request->get('datestart') .
            "<br>Date ended: ". $req->request->get('dateend');

        $email = self::sendEmail($class, $subject, $from, $to,
            $class->renderView('AdminBundle:Templates/Email:email-template.html.twig', array('message' => $inputMessage)));

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


        if(empty($req->request->get('typeleave')))
        {
            $requestlist = ListRequestTypePeer::retrieveByPK(4);
        }else
        {
            $requestlist = ListRequestTypePeer::retrieveByPK($req->request->get('typeleave'));
        }
        $requesttype = $requestlist->getRequestType();

        $admins = EmpAccPeer::getAdminInfo();
        $adminemails = array();
        $subject = "PROLS » " . $requesttype . " Request";
        $from    = array('no-reply@searchoptmedia.com', 'PROLS');
        foreach ($admins as $admin){
            $to = array($admin->getEmail());

            $inputMessage = "<h2>Hi admin!" . "</h2><b>" . $empname . "</b> has requested for a <b>" . $requesttype . "</b>." .
                "<br><br><br><a style='text-decoration:none;border:0px; padding: 15px 30px; background:#3498DB;color:#fff;font-weight:bold;font-size:14px;display:inline-block;' href='http://login.propelrr.com/requests'>View Request</a>" . "<br>";

            $email = self::sendEmail($class, $subject, $from, $to,
                $class->renderView('AdminBundle:Templates/Email:email-template.html.twig', array('message' => $inputMessage)));
        }




        return $email ? 1: 0;
    }
//    public function requestEmployeeTagEmail($req, $class)
//    {
//        $empinfo = EmpProfilePeer::getInformation($id);
//        $empname = $empinfo->getFname() . " " . $empinfo->getLname();
//
//    }
    public function addEmployeeEmail($req, $class){
        $user = $class->getUser();
        $id   = $user->getId();

        $employeeemail = $req->request->get('email');
        $empname = $req->request->get('fname') . " " . $req->request->get('lname');
        $empusername = $req->request->get('username');
        $emppassword = $req->request->get('password');


        $subject = "PROLS » Your Account Was Created";
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

        $response = $class->get('mailer')->send($message);

        return $response;

    }
    public function sendEmailMeetingRequest($req, $email, $class, $param = array())
    {


        $taggedemail = $req->request->get('taggedemail');
        $employee = EmpAccPeer::getUserInfo($email);

        $employee_info = EmpProfilePeer::getInformation($employee->getId());
        $employee_name = $employee_info->getFname() . " " . $employee_info->getLname();


        $from_user = $class->getUser()->getId();

        $arrlength = count($param);
        $type = $param['type'];



        $subject = "Request Meeting";
        $from    = array('no-reply@searchoptmedia.com', 'PROLS');
        $to      = array($email);
        if($type == 1) {
            $inputMessage = "Hi " . $employee_name . "!<br> You requested for  Meeting " . ".<br><br> Wait for Admin to accept/decline <a href='http://login.propelrr.com/profile'>here</a>";
            $email = self::sendEmail($class, $subject, $from, $to, $inputMessage);
        }
        if($type == 2){
            $name = $param["names"];
            $inputMessage = "Hi  " . $employee_name . "!<br> You requested for  Meeting with  ". $name .".<br><br> Wait for Admin to accept/decline <a href='http://login.propelrr.com/profile'>here</a>";
            $email = self::sendEmail($class, $subject, $from, $to, $inputMessage);
        }
        return $email ? 1: 0;
    }


}

?>