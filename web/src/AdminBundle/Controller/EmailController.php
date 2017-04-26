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
use CoreBundle\Model\EmpProfileQuery;
use CoreBundle\Model\ListEventsPeer;
use CoreBundle\Model\ListEventsTypePeer;
use CoreBundle\Model\ListRequestTypePeer;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Swift_Mailer;
use Swift_SmtpTransport;
use Swift_Message;
use Symfony\Component\HttpFoundation\JsonResponse;


class EmailController extends Controller
{

    public function sendTimeInRequest($req, $class, $reqId = null)
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

        $date = date('F d, Y') . ' at ' . date('h:i a') ;
        $requestDates = array(array('start' => $date, 'end' => $date, 'reason' => $message));

        $emailContent = $class->renderView('AdminBundle:Templates/Email:email-template-admin-request.html.twig', array(
            'data' => $requestDates,
            'title' => 'Request for Access',
            'greetings' => 'Hi Admin,',
            'type' => 'access-request',
            'doerName' => $empName,
            'links' => array('View Request' =>  $class->generateUrl('view_request',  array('id' => $reqId), true))
        ));

        $email = self::sendEmail($class, $subject, $from, $to,$emailContent);

        return $email ? 1: 0;
    }

    public function acceptRequestEmail($req, $class)
    {
        $empid  = $req->request->get('empId');
        $reqName =   $req->request->get('requestname');
        $reqId   =   $req->request->get('reqid');
        $user   = $class->getUser();
        $id     = $user->getId();
        $email  = 0;

        $employee = EmpAccPeer::retrieveByPK($empid);
        $changed = $req->request->get('isChanged');
        $note = $req->request->get('comment');
        $status = $req->request->get('status');
        if($status == 3) $status = "Approved";
        else $status = "Declined";
        $reason = $req->request->get('reason');

        if(! empty($employee))
        {
            $empemail = $employee->getEmail();
            $empinfo = EmpProfilePeer::getInformation($employee->getId());
            $empname = $empinfo->getFname() . " " .$empinfo->getLname();

            //admin profile information
            $data = EmpProfilePeer::getInformation($id);
            $name = $data->getFname(). " " .$data->getLname();

            if ($changed == "CHANGED")
                $subject = "PROLS » " . $req->request->get('requestname') . " " . " Request Changed";
            else $subject = "PROLS » " . $req->request->get('requestname') . " " . " Request " . $status;
            $from    = array('no-reply@searchoptmedia.com', 'PROLS');
            $to      = array($empemail);

            $requestDates = array(
                array('start' => date('F d, Y', strtotime($req->request->get('datestart'))), 'end' => date('F d, Y', strtotime($req->request->get('dateend'))), 'reason' => $reason)
            );

            $emailContent = $class->renderView('AdminBundle:Templates/Email:email-template-admin-request-response.html.twig', array(
                'data' => $requestDates,
                'title' => ucwords(strtolower($req->request->get('requestname'))),
                'greetings' => 'Hi '.$empname.',',
                'type' => strtolower($status),
                'links' => array('View Request' =>  $class->generateUrl('view_request',  array('id' => $reqId), true)),
                'approval_date' => date('F d, Y').' at '. date('h:i a'),
                'approval_note' => $note,
                'doerName' => $name
            ));

            $email = self::sendEmail($class, $subject, $from, $to, $emailContent);
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

    public function requestTypeEmail($req, $class, $reqId = null)
    {
        $email = 0;
        $user = $class->getUser();
        $id   = $user->getId();

        //lets get the request details
        $obj = $req->request->get('obj');
        $requestDates = array();

        foreach($obj as $o) {
            $requestDates[] = array('start' => date('F d, Y', strtotime($o["start_date"])), 'end' => date('F d, Y', strtotime($o["end_date"])), 'reason' => $o["reason"]);
        }

        $empinfo = EmpProfilePeer::getInformation($id);
        $empname = $empinfo->getFname() . " " . $empinfo->getLname();

        $typeOfLeave = $req->request->get('typeleave');

        if(empty($typeOfLeave)) {
            $requestlist = ListRequestTypePeer::retrieveByPK(4);
        } else {
            $requestlist = ListRequestTypePeer::retrieveByPK($req->request->get('typeleave'));
        }

        $requesttype = $requestlist->getRequestType();

        $admins = EmpAccPeer::getAdminInfo();
        $subject = "PROLS » " . $requesttype . " Request";
        $from    = array('no-reply@searchoptmedia.com', 'PROLS');
        $adminEmailList = $this->getAdminEmails($admins);

        if(count($adminEmailList)) {
            $to = $adminEmailList;

            $emailContent = $class->renderView('AdminBundle:Templates/Email:email-template-admin-request.html.twig', array(
                'data' => $requestDates,
                'title' => $requesttype,
                'greetings' => 'Hi Admin,',
                'type' => 'leave-request',
                'doerName' => $empname,
                'links' => array(count($requestDates)==1 ? 'View Request':'View All Requests' =>  $class->generateUrl('view_request',  array('id' => count($requestDates)==1? $reqId: ''), true))
            ));

            $response = self::sendEmail($class, $subject, $from, $to, $emailContent);

            if ($response)
                $email++;
        }

        return $email;
    }

    public function addEmployeeEmail($req, $class){
        $user = $class->getUser();
        $id   = $user->getId();
        $profile = EmpProfilePeer::getInformation($id);
        $employeeemail = $req->request->get('emailinput');
        $empname = $req->request->get('fnameinput') . " " . $req->request->get('lnameinput');
        $empusername = $req->request->get('usernameinput');
        $emppassword = $req->request->get('passwordinput');

        $subject = "PROLS » Your Account Was Successfully Created";
        $from    = array('no-reply@searchoptmedia.com', 'PROLS');
        $to      = array($employeeemail);

        $emailContent = $class->renderView('AdminBundle:Templates/Email:email-template-account-create.html.twig', array(
            'title' => 'Propelrr Login System',
            'greetings' => 'Hi '.$empname.',',
            'username' => $empusername,
            'password' => $emppassword,
            'type' => 'employee-email'
        ));

        $email = self::sendEmail($class, $subject, $from, $to, $emailContent);

        //check if doer is an admin
        $admins = EmpAccPeer::getAdminInfo();
        $adminemails = array();
        foreach ($admins as $admin){
            $adminemails[] = $admin->getEmail();
        }

        $currentUserEmail = $class->getUser()->getEmail();

        if(!in_array($currentUserEmail, $adminemails)) {
            $name = $profile->getFname() . ' ' . $profile->getLname();
            $emailContent = $class->renderView('AdminBundle:Templates/Email:email-template-account-create.html.twig', array(
                'title' => 'Propelrr Login System',
                'greetings' => 'Hi Admin,',
                'accountName' =>$empname,
                'doerName' => $name,
                'type' => 'admin-email'
            ));

            $to    = array($adminemails);
            self::sendEmail($class, "PROLS » Account Successfully Created", $from, $to, $emailContent);
        }

        return $email ? 1: 0;
    }

    public function adminEditEmployeeProfileEmail($req, $class, $changes = array()){
        $user = $class->getUser();
        $id   = $user->getId();

        $empId = $req->request->get('empid');

        $adminprofile = EmpProfilePeer::getInformation($id);
        $adminname = $adminprofile->getFname() . " " . $adminprofile->getLname();

        $employeeemail = $req->request->get('emailinput');
        $empname = $req->request->get('fnameinput') . " " . $req->request->get('lnameinput');
        $empusername = $req->request->get('usernameinput');
        $emppassword = $req->request->get('passwordinput');

        $subject = "PROLS » Account Updated";
        $from    = array('no-reply@searchoptmedia.com', 'PROLS');
        $to      = array($employeeemail);
        $email   = 0;

        $admins = EmpAccPeer::getAdminInfo();
        $subject = "PROLS » Employee Profile Updated";
        $adminEmailList = $this->getAdminEmails($admins);

        if(count($adminEmailList) && !in_array($user->getEmail(), $adminEmailList)) {
            $to = $adminEmailList;

            $emailContent = $class->renderView('AdminBundle:Templates/Email:email-template-admin-account-update.html.twig', array(
                'title' => 'Profile Update',
                'greetings' => 'Hi Admin,',
                'name' => $empname,
                'doerName' => $adminname,
                'type' => 'admin-email',
                'data' => $changes
            ));

           $email = self::sendEmail($class, $subject, $from, $to,  $emailContent);
        }

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
        $user = $class->getUser();
        $empinfo = EmpProfilePeer::getInformation($user->getId());
        $empname = $empinfo->getFname() . " " . $empinfo->getLname();

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
            $inputMessage = "Hi " . $employee_name . "!<br> ". $empname ." requested for a meeting with you. " . ".<br><br> Click <a href='http://login.propelrr.com/main/requests'>here</a> to accept/decline.";
            $email = self::sendEmail($class, $subject, $from, $to,  $class->renderView('AdminBundle:Templates/Email:email-template.html.twig',array('message' => $inputMessage)));
        }
        if($type == 2){
            $name = $param["names"];
            $inputMessage = "Hi  " . $employee_name . "!<br> You requested for  Meeting with  ". $name .".<br><br> Click <a href='http://login.propelrr.com/main/requests'>here</a> to view your request.";
            $email = self::sendEmail($class, $subject, $from, $to,  $class->renderView('AdminBundle:Templates/Email:email-template.html.twig',array('message' => $inputMessage)));
        }
        return $email ? 1: 0;
    }

    public function notifyEventEmail($req, $class, $datetimecreated, $param = array()) {
        $user = $class->getUser();
        $id   = $user->getId();
        $userProfile = EmpProfilePeer::getInformation($id);
        $userName = $userProfile->getFname() . " " . $userProfile->getLname();
        $eventType = $req->request->get('event_type');
        $name = $req->request->get('event_name');
        $fromdate = $req->request->get('from_date');
        $todate = $req->request->get('to_date');
        $email  = 0;

        if($eventType == 1) {
            $typelist = "Holiday";

            $users = EmpAccPeer::getAllUser();

            foreach($users as $user) {
                $empinfo = EmpProfilePeer::getInformation($user->getId());
                $empname = $empinfo->getFname() . " " . $empinfo->getLname();

                $subject = "PROLS » New Event";
                $from = array('no-reply@searchoptmedia.com', 'PROLS');
                $to = array($user->getEmail());
                $inputMessage = "<h2>Hi <b>" . $empname . "</b>!" . "</h2> <b>" . $userName . "</b> created a/an <b>" . $typelist . "</b> event. " .
                    "Here are the following details regarding the said event: <br><br><hr><br>";
                    if($fromdate == $todate)
                        $inputMessage .= "<b>Event Date: </b>" . $fromdate. "<br>";
                    else $inputMessage .= "<b>Event Date: </b>" . $fromdate . " to " . $todate . "<br>";

                $inputMessage .= "<b>Event Name: </b>" . $name;

                $email = self::sendEmail($class, $subject, $from, $to,
                    $class->renderView('AdminBundle:Templates/Email:email-template.html.twig', array('message' => $inputMessage)));
            }
        } else {
            $type = $param['type'];
            $emailAdd = $param['guestEmail'];
            $eventDesc = $req->request->get('event_desc');
            $eventList = ListEventsTypePeer::getEventType($eventType);
            $empinfo = EmpProfilePeer::getInformation($user->getId());
            $empname = $empinfo->getFname() . " " . $empinfo->getLname();

            $employee = EmpAccPeer::getUserInfo($emailAdd);
            $employee_info = EmpProfilePeer::getInformation($employee->getId());
            $employee_name = $employee_info->getFname() . " " . $employee_info->getLname();

            $subject = "PROLS » New Event";
            $from    = array('no-reply@searchoptmedia.com', 'PROLS');
            $to      = array($emailAdd);
            if($type == 1) {
                $inputMessage = "<h2>Hi <b>" . $employee_name . "</b>!" . "</h2> <b>" . $empname . "</b> tagged you on an event." .
                    "Here are the following details regarding the said event: <br><br><hr><br>";
                $inputMessage .= "<b>Event Type: </b>" . $eventList->getName();
                $inputMessage .= "<b>Event Name: </b>" . $name;

                if($fromdate == $todate)
                    $inputMessage .= "<b>Event Date: </b>" . $fromdate. "<br>";
                else $inputMessage .= "<b>Event Date: </b>" . $fromdate . " to " . $todate . "<br>";

                $inputMessage .= "<b>Event Description: </b>" . $eventDesc;

                $email = self::sendEmail($class, $subject, $from, $to,  $class->renderView('AdminBundle:Templates/Email:email-template.html.twig',array('message' => $inputMessage)));
            }
            if($type == 2){
                $taggednames = $param["names"];
                $inputMessage = "Hi  " . $employee_name . "!<br> You have created an event with  ". $taggednames .". " .
                    "Here are the following details regarding the said event: <br><br><hr><br>";
                $inputMessage .= "<b>Event Type: </b>" . $eventList->getName() . "<br>";
                $inputMessage .= "<b>Event Name: </b>" . $name . "<br>";

                if($fromdate == $todate)
                    $inputMessage .= "<b>Event Date: </b>" . $fromdate. "<br>";
                else $inputMessage .= "<b>Event Date: </b>" . $fromdate . " to " . $todate . "<br>";

                $inputMessage .= "<b>Event Description: </b>" . $eventDesc;
                $email = self::sendEmail($class, $subject, $from, $to,  $class->renderView('AdminBundle:Templates/Email:email-template.html.twig',array('message' => $inputMessage)));
            }
        }

        return $email ? 1: 0;
    }

    public function notifyRequestEmail($req, $class, $action, $param = null) {
        $user = $class->getUser();
        $id   = $user->getId();

        $empinfo = EmpProfilePeer::getInformation($id);
        $empname = $empinfo->getFname() . " " . $empinfo->getLname();

        $category = $req->request->get('category');

        $admins = EmpAccPeer::getAdminInfo();
        $adminemails = array();
        $emailCtr = 0;

        $subject = "PROLS » " . $action . " " . $category . " Request";
        $from    = array('no-reply@searchoptmedia.com', 'PROLS');

        $adminEmailList = $this->getAdminEmails($admins);

        if(count($adminEmailList)) {
            $to = $adminEmailList;

            if ($action == "UPDATED") {
                $inputMessage = "<b>".$empname . "</b> has ". $action ." his/her <b>" . $category . "</b> Request.";
            } else {
                $inputMessage = "<b>".$empname . "</b> has ". $action ." his/her <b>" . $category . "</b> Request. <br><br>";
            }

            $emailContent = $class->renderView('AdminBundle:Templates/Email:email-template-admin-request-update.html.twig', array(
                'title' => ucwords(strtolower($category)),
                'greetings' => 'Hi Admin,',
                'type' => 'update-request',
                'message' => $inputMessage,
                'link' => $class->generateUrl('view_request',  array('id' => $param->getId()), true),
                'action' => $action
            ));

            $email = self::sendEmail($class, $subject, $from, $to, $emailContent);

            if($email)
                $emailCtr++;
        }

        return $emailCtr;
    }


    public function getAdminEmails($adminList = array())
    {
        $adminEmails = array();

        if($adminList) {
            foreach($adminList as $e) {
                $email = $e->getEmail();
                $name  = $e->getUsername();
                $profile  = EmpProfileQuery::create()->filterByEmpAccAccId($e->getId())->findOne();

                if($profile) $name = $profile->getFname() . " " . $profile->getLname();

                if(! empty($email)) $adminEmails[0][$email] = "$name";
            }
        }

        return $adminEmails;
    }
}

?>