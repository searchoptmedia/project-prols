<?php
/**
 * Created by PhpStorm.
 * User: Propelrr-AJ
 * Date: 09/08/16
 * Time: 12:48 PM
 */

namespace AdminBundle\Controller;


use CoreBundle\Model\EmpAccPeer;
use CoreBundle\Model\EmpAccQuery;
use CoreBundle\Model\EmpProfilePeer;
use CoreBundle\Model\EmpProfileQuery;
use CoreBundle\Model\EmpRequest;
use CoreBundle\Model\EventTaggedPersonsQuery;
use CoreBundle\Model\ListEvents;
use CoreBundle\Model\ListEventsPeer;
use CoreBundle\Model\ListEventsQuery;
use CoreBundle\Model\ListEventsTypePeer;
use CoreBundle\Model\ListRequestTypePeer;
use CoreBundle\Utilities\Constant as C;
use CoreBundle\Utilities\Utils as U;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Swift_Mailer;
use Swift_SmtpTransport;
use Swift_Message;
use Symfony\Component\HttpFoundation\JsonResponse;


class EmailController extends Controller
{
    /**
     * Request out of office email
     * @param $req
     * @param $class
     * @param null $reqId
     * @return int
     */
    public function sendTimeInRequest($req, $class, $reqId = null)
    {
        $user = $class->getUser();
        $id = $user->getId();

        //employee profile information
        $data = EmpProfilePeer::getInformation($id);

        $admins = EmpAccPeer::getAdminInfo();
        $empName = $data->getFname() . ' ' . $data->getLname();
        $message = $req->request->get('message');
        $adminemails = array();

        $from = array('no-reply@searchoptmedia.com', 'PROLS');
        $subject = 'PROLS » Request for Access';

        foreach ($admins as $admin){
            $adminemails[] = $admin->getEmail();
        }

        $to = array($adminemails);
        $date = date('F d, Y') . ' at ' . date('h:i a') ;
        $requestDates = array(
            array(
                'start' => $date,
                'end' => $date,
                'reason' => $message
            )
        );

        $emailContent = $class->renderView('AdminBundle:Templates/Email:email-has-table.html.twig', array(
            'data' => $requestDates,
            'title' => 'Request Access',
            'greetings' => 'Hi Admin,',
            'template' => 'request-access',
            'message' => $empName . ' timed in outside the office.',
            'links' => array(
                'Approve' => array('bgColor' => '#4CAF50', 'href' => $class->generateUrl('listener_homepage',  array('id' => $reqId, 'type' => 'approve', 'uid' => $class->getUser()->getId()), true)),
                'Decline' => array('bgColor' => '#F44336', 'href' => $class->generateUrl('listener_homepage',  array('id' => $reqId, 'type' => 'decline', 'uid' => $class->getUser()->getId()), true)),
                'View Request' =>  array('href' => $class->generateUrl('view_request',  array('id' => $reqId), true))
            )
        ));

        $email = self::sendEmail($class, $subject, $from, $to,$emailContent);

        return $email ? 1: 0;
    }

    /**
     * Approve/Decline requests
     * @param $req
     * @param $class
     * @return int
     */
    public function acceptRequestEmail(EmpRequest $empRequest, $params = array(), $class)
    {
        $reqId = $empRequest->getId();
        $email = 0;

        $employee = EmpProfilePeer::getInformation($empRequest->getEmpAccId());
        $note = $params['comment'];
        $status = $params['status'];
        $reason = $empRequest->getRequest();
        $changed = $params['isChanged'];
        $requestName = $empRequest->getListRequestType()->getRequestType();

        if($status == C::STATUS_APPROVED)
            $status = "Approved";
        else
            $status = "Declined";

        if(! empty($employee)) {
            $empName = trim($employee->getFname().' '.$employee->getLname());
            $empEmail = $employee->getEmpAcc()->getEmail();
            $adminName = $params['adminInfo']->getFname() .' '.$params['adminInfo']->getFname();

            if ($changed == "CHANGED")
                $subject = "PROLS » " . ucwords(strtolower($requestName)) . " " . " Request Changed";
            else
                $subject = "PROLS » " . ucwords(strtolower($requestName)) . " " . " Request " . $status;

            $from = array('no-reply@searchoptmedia.com', 'PROLS');
            $to = array($empEmail);

            $requestDates = array(
                array(
                    'start' => $empRequest->getDateStarted()->format('F d, Y'),
                    'end' => $empRequest->getDateEnded()->format('F d, Y'),
                    'reason' => $reason
                )
            );

            $emailContent = $class->renderView('AdminBundle:Templates/Email:email-has-table.html.twig', array(
                'data' => $requestDates,
                'title' => !in_array(strtolower($requestName), array('work out of office', 'work outside office')) ? ucwords(strtolower($requestName)) : 'Request Access',
                'greetings' => 'Hi '.$empName.',',
                'template' => 'approve-decline',
                'message' => "<strong>$adminName</strong> has <strong>".strtolower($status)."</strong> your ".strtolower($requestName).".",
                'links' => array(
                    'View Request' =>  array('href' => $class->generateUrl('view_request',  array('id' => $reqId), true))
                ),
                'approval_reason' => $note,
            ));

            $email = self::sendEmail($class, $subject, $from, $to, $emailContent);
        }

        return $email ? 1: 0;
    }

    /**
     * Adding new employee - send credentials|Notify admin
     * @param $req
     * @param $class
     * @return int
     */
    public function addEmployeeEmail($req, $class){
        $user = $class->getUser();
        $id = $user->getId();

        $profile = EmpProfilePeer::getInformation($id);
        $empname = $req->request->get('fnameinput') . " " . $req->request->get('lnameinput');
        $employeeemail = $req->request->get('emailinput');
        $empusername = $req->request->get('usernameinput');
        $emppassword = $req->request->get('passwordinput');

        $subject = "PROLS » Your Account Was Successfully Created";
        $from = array('no-reply@searchoptmedia.com', 'PROLS');
        $to = array($employeeemail);

        $emailContent = $class->renderView('AdminBundle:Templates/Email:email-has-table.html.twig', array(
            'title' => 'Login System',
            'greetings' => 'Hi '.$empname.',',
            'username' => $empusername,
            'password' => $emppassword,
            'template' => 'account-create-employee',
            'message' => 'Your account was successfully created. Use the following details to login at <a href="'.$class->generateUrl('login', array(), true).'">login.propelrr.com</a>.',
            'links' => array(
                'Login Now!' => $class->generateUrl('login', array(), true)
            )
        ));

        //-------- email employee
        $email = self::sendEmail($class, $subject, $from, $to, $emailContent);

        //check if doer is an admin
        $admins = EmpAccPeer::getAdminInfo();
        $adminemails = array();

        foreach ($admins as $admin){
            $adminemails[] = $admin->getEmail();
        }

        $to = array($adminemails);
        $currentUserEmail = $class->getUser()->getEmail();

        if(!in_array($currentUserEmail, $adminemails)) {
            $name = $profile->getFname() . ' ' . $profile->getLname();
            $emailContent = $class->renderView('AdminBundle:Templates/Email:email-has-table.html.twig', array(
                'title' => 'Login System',
                'greetings' => 'Hi Admin,',
                'message'  => 'Account for <strong>'.$empname.'</strong> was successfully created by <strong>'.$name.'</strong>.',
                'template' => 'account-create-admin',
                'links' => array(
                    'Manage Employees' => $class->generateUrl('manage_employee', array(), true)
                )
            ));

            //-------- email admin
            $email = self::sendEmail($class, "PROLS » Account Successfully Created", $from, $to, $emailContent);
        }

        return $email ? 1: 0;
    }

    /**
     * Update employee details - notify admin
     * @param $req
     * @param $class
     * @param array $changes
     * @return int
     */
    public function adminEditEmployeeProfileEmail($req, $class, $changes = array()){
        $user = $class->getUser();
        $id = $user->getId();

        $userProfile = EmpProfilePeer::getInformation($id);
        $userFullName = $userProfile->getFname() . " " . $userProfile->getLname();
        $employeeName = $req->request->get('fnameinput') . " " . $req->request->get('lnameinput');

        $from = array('no-reply@searchoptmedia.com', 'PROLS');
        $email = 0;

        $admins = EmpAccPeer::getAdminInfo();
        $subject = "PROLS » Employee Profile Updated";
        $adminEmailList = $this->getAdminEmails($admins);

        if(count($adminEmailList) && !in_array($user->getEmail(), $adminEmailList)) {
            $to = $adminEmailList;

            $emailContent = $class->renderView('AdminBundle:Templates/Email:email-has-table.html.twig', array(
                'title' => 'Profile Update',
                'greetings' => 'Hi Admin,',
                'message' => "<strong>$userFullName</strong> has updated <strong>$employeeName</strong>'s profile.",
                'template' => 'profile-update',
                'data' => $changes
            ));

            $email = self::sendEmail($class, $subject, $from, $to,  $emailContent);
        }

        return $email ? 1: 0;
    }

    /**
     * Notify admin if request has been updated/cancelled by employee
     * @param $req
     * @param $class
     * @param $action
     * @param array $param
     * @return int
     */
    public function notifyRequestEmail($req, $class, $action, $param = array()) {
        $user = $class->getUser();
        $id = $user->getId();

        $empinfo = EmpProfilePeer::getInformation($id);
        $empname = $empinfo->getFname() . " " . $empinfo->getLname();
        $gender  = strtolower($empinfo->getGender());
        $genderPref = ($gender=='male') ? 'his' : ($gender=='female' ? 'her' : 'his/her');
        $category = $req->request->get('category');
        $admins = EmpAccPeer::getAdminInfo();
        $emailCtr = 0;

        $subject = "PROLS » " . ucwords(strtolower($action)) . " " . ucwords(strtolower($category)) . " Request";
        $from = array('no-reply@searchoptmedia.com', 'PROLS');

        $adminEmailList = $this->getAdminEmails($admins);

        $newStartDate = $req->request->get('start_date');
        $newEndDate = $req->request->get('end_date');
        $newReason = $req->request->get('reason');
        $oldData = array();

        $data = array(
            array(
                'start' => date('F d, Y', strtotime($newStartDate)),
                'end' => date('F d, Y', strtotime($newEndDate)),
                'reason' => $newReason
            )
        );

        if($action!='CANCELLED') {
            $oldData = $param ? array(
                array(
                    'start' => $param['startDate'],
                    'end' => $param['endDate']
                )
            ) : null;
        } else {
            $data = array(
                array(
                    'start' => $param['startDate'],
                    'end' => $param['endDate'],
                    'reason' => $param['reason']
                )
            );
        }

        //check if changes on date
        if($oldData) {
            foreach($oldData as $d) {
                if($d['start']==date('F d, Y', strtotime($newStartDate)) && $d['end']==date('F d, Y', strtotime($newEndDate))) {
                    $oldData = null;
                }
            }
        }

        if(count($adminEmailList)) {
            $to = $adminEmailList;
            $message = "<strong>".$empname . "</strong> has ". strtolower($action) ." $genderPref <strong>" . strtolower($category) . "</strong>.";

            $emailContent = $class->renderView('AdminBundle:Templates/Email:email-has-table.html.twig', array(
                'title'     => strtolower($category)!='work out of office' ? ucwords(strtolower($category)) : 'Request Access',
                'greetings' => 'Hi Admin,',
                'template'  => 'update-request',
                'message'   => $message,
                'links'     => ($action!='CANCELLED') ? array('View Request' => $class->generateUrl('view_request',  array('id' => $param ? $param['request']->getId() : null), true)) : null,
                'data'      => $data,
                'old_data'  => $oldData
            ));

            $email = self::sendEmail($class, $subject, $from, $to, $emailContent);

            if($email)
                $emailCtr++;
        }

        return $emailCtr;
    }

    public function notifyEmployeeOnEvent($params = array(), Controller $class)
    {
        $ownerId = U::getUserDetails('id', $class);
        $ownerAcc = EmpProfilePeer::getInformation($ownerId);
        $ownerName = trim($ownerAcc->getFname() . " " . $ownerAcc->getLname());

        $empAcc = EmpAccQuery::_findById($params['user_id']);
        $empInfo = EmpProfileQuery::_findByAccId($params['user_id']);
        $employeeName = trim($empInfo->getFname() . ' ' . $empInfo->getLname());

        $title = 'Holiday';
        $message = (isset($params['isNew']) && $params['isNew'] ? "New Holiday was added on Propelrr Calendar" : "Holiday details was updated.")."<br>See the details below.";

        if($params['event_type']!=C::EVENT_TYPE_HOLIDAY) {
            $title = $params['event_type']==C::EVENT_TYPE_MEETING ? 'Meeting Invitation' : 'Event Invitation';
            $action = $params['event_type']==C::EVENT_TYPE_MEETING ? 'a meeting: ' : 'a company event: ';
            $message = "<strong>".$ownerName . "</strong> invited you to join ". strtolower($action) ."<strong>". $params['event_name'] ."</strong>.<br>See the details below.";
        }

        $to = !empty($params['to_list']) ? $params['to_list'] : array(array($empAcc->getEmail() => $employeeName));
        $from = array('no-reply@searchoptmedia.com', 'Propelrr Login System');
        $subject = "PROLS » " .
            ($params['event_type']==C::EVENT_TYPE_HOLIDAY ? (isset($params['isNew']) && $params['isNew'] ? 'New Holiday: '.$params['event_name']:'Holiday: '.$params['event_name'].' Was Updated') :
            ($params['event_type']==C::EVENT_TYPE_MEETING ? 'Meeting Invitation' : 'Event Invitation'));

        if(isset($params['has-update']) && $params['has-update']) {
            $message = "<strong>".$ownerName . "</strong> has updated <strong>". $params['event_name'] ."</strong>.<br>See the details below.";
            $title = $params['event_type']==C::EVENT_TYPE_MEETING ? 'Meeting' : 'Internal Event';
            $subject = "PROLS » " .
                ($params['event_type']==C::EVENT_TYPE_MEETING ? 'Meeting Was Updated' :
                ($params['event_type']==C::EVENT_TYPE_INTERNAL ? 'Internal Event Was Updated' : 'Holiday Was Updated'));
        }

        if(isset($params['has-cancel'])) {
            $message = "<strong>".$ownerName . "</strong> has cancelled <strong>". $params['event_name'] ."</strong>.";
            $title = $params['event_type']==C::EVENT_TYPE_MEETING ? 'Cancelled Meeting' : ($params['event_type']==C::EVENT_TYPE_MEETING? 'Cancelled Internal Event':'Cancelled Holiday');
            $subject = "PROLS » " .
                ($params['event_type']==C::EVENT_TYPE_MEETING ? ' Meeting Was Cancelled' :
                ($params['event_type']==C::EVENT_TYPE_INTERNAL ? ' The Internal Event Was Cancelled' : 'Holiday Was Cancelled'));
        }

        $params['title'] = $title;
        $params['greetings'] = 'Hi '.$employeeName.',';
        $params['template'] = 'event-create';
        $params['message'] = $message;

        $emailContent = $class->renderView('AdminBundle:Templates/Email:email-has-table.html.twig', $params);

        return self::sendEmail($class, $subject, $from, $to, $emailContent);
    }

    public function notifyEmployeeOnEventUpdateTagStatus($params = array(), Controller $class)
    {

        if(isset($params['event_id'])) {
            $event = ListEventsQuery::_findById($params['event_id']);
            $user = EmpProfileQuery::_findByAccId($params['user_id']);

            if($event && $user) {
                $eventType = $event->getEventType();

                $eventTags = EventTaggedPersonsQuery::_findAllByEvent($event->getId());
                $title = 'Holiday';
                $message = '';
                $GuestIndicator = $params['status']==C::STATUS_APPROVED ? '':'not';

                if ($eventType!=C::EVENT_TYPE_HOLIDAY) {
                    $title = $eventType == C::EVENT_TYPE_MEETING ? 'Meeting' : 'Internal Event';
                    $action = $eventType == C::EVENT_TYPE_MEETING ? 'the meeting' : $event->getEventName();
                    $message = "<strong>[NAME]</strong> will $GuestIndicator be attending <strong>" . strtolower($action) . "</strong>. See the details below.[REASON]";
                }

                $owner = EmpProfileQuery::_findByAccId($event->getCreatedBy());
                $ownerName = trim($owner->getFname() .' '. $owner->getLname());

                $doerName = trim($user->getFname() . ' ' . $user->getLname());
                $doerEmail = $user->getEmpAcc()->getEmail();
                $doerAction = ucfirst($GuestIndicator) . ' be Attending';

                $params['title'] = $title;
                $params['event_name'] = $event->getEventName();
                $params['event_desc'] = $event->getEventDescription();
                $params['event_venue'] = $event->getEventVenue();
                $params['to_date'] = $event->getToDate()->format('F d, Y h:i a');
                $params['from_date'] = $event->getFromDate()->format('F d, Y h:i a');
                $params['owner_email'] = $event->getEmpAcc()->getEmail();
                $params['event_tag_names'] = array();
                $params['template'] = 'event-status-change';
                $params['links'] = array('View Event' => array(
                    'href' => $class->generateUrl('admin_manage_events', array('id' => $event->getId()), true)
                ));

                $from = array('no-reply@searchoptmedia.com', 'Propelrr Login System');
                $subject = "PROLS » " . $doerName . ' will ' . $doerAction . ' to ' . $event->getEventName();

                if(count($eventTags)) {
                    $emailTaggedList = array();
                    foreach ($eventTags as $et) {
                        $etStatus = $et->getStatus();

                        if($etStatus!=C::STATUS_INACTIVE) {
                            $employee = EmpProfileQuery::_findByAccId($et->getEmpId());
                            $params['event_tag_names'][$et->getEmpAcc()->getEmail()] = trim($employee->getFname() . ' ' . $employee->getLname());
                            $params['event_tag_status'][$et->getEmpAcc()->getEmail()] = $etStatus;
                            $emailTaggedList[] = $et->getEmpAcc()->getEmail();
                        }
                    }

                    $params['event_tag_names'][$event->getEmpAcc()->getEmail()] = trim($owner->getFname() . ' ' . $owner->getLname());
                    $params['event_tag_status'][$event->getEmpAcc()->getEmail()] = $event->getIsGoing() ? C::STATUS_APPROVED : C::STATUS_DECLINED;

                    if(! in_array($event->getEmpAcc()->getEmail(), $emailTaggedList)) {
                        $params['greetings'] = 'Hi ' . $ownerName . ',';
                        $params['message'] = strtr($message, array(
                            '[NAME]' => $doerName,
                            '[REASON]' => strlen($params['reason']) ? "<br><br><hr style=\"border-top:1px dotted #ccc;margin-bottom:10px\"><p style=\"vertical-align:top;padding-top:0px;font-size:16px;padding-bottom:0px;font-family:'Lato',Calibri,Arial,sans-serif\"><strong>Reason:</strong><br>".$params['reason']."</p>":""
                        ));

                        $to = array(array($event->getEmpAcc()->getEmail() => $ownerName));
                        $emailContent = $class->renderView('AdminBundle:Templates/Email:email-has-table.html.twig', $params);
                        $email = self::sendEmail($class, $subject, $from, $to, $emailContent);
                    }

                    foreach ($eventTags as $et) {
                        $employee = EmpProfileQuery::_findByAccId($et->getEmpId());
                        $etEmail = $et->getEmpAcc()->getEmail();
                        $etName = trim($employee->getFname() . " " . $employee->getLname());

                        $to = array(array($etEmail => $etName));
                        $params['greetings'] = 'Hi ' . $etName . ',';

                        if($doerEmail!=$etEmail && $et->getStatus()!=C::STATUS_INACTIVE) {
                            $params['message'] = strtr($message, array(
                                '[NAME]' => $doerName,
                                '[REASON]' => (strlen($params['reason'])) ? "<br><br><hr style='border-top:1px dotted #ccc;margin-bottom:10px'><strong>Comment:</strong><br>".$params['reason']:""
                            ));

                            $emailContent = $class->renderView('AdminBundle:Templates/Email:email-has-table.html.twig', $params);
                            $email = self::sendEmail($class, $subject, $from, $to, $emailContent);
                        }
                    }
                }
            }
        }
    }

    /**
     * Get admin email list
     * @param array $adminList
     * @return array
     */
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

    /**
     * Get admin email list
     * @param array $adminList
     * @return array
     */
    public function getAdmins()
    {
        $admins = EmpProfileQuery::create()
            ->useEmpAccQuery()
                ->filterByRole('ADMIN')
                ->filterByStatus(C::STATUS_ACTIVE)
            ->endUse()
            ->find();

        $adminLists = array();

        if($admins) {
            foreach($admins as $e) {
                $email = $e->getEmpAcc()->getEmail();
                $name  = trim($e->getFname() .' '. $e->getLname());

                $adminLists[] = array(
                    'id' => $e->getEmpAcc()->getId(),
                    'email' => array( $email =>$name ),
                    'name' => 'Admin'
                );
            }
        }

        return $adminLists;
    }

    public function addTeamLeadEmail($department)
    {
        $admin = EmpProfileQuery::create()
            ->filterByListDeptDeptId($department)
                ->useEmpAccQuery()
                    ->filterByTeamRole('lead')
                    ->filterByStatus(C::STATUS_ACTIVE)
                ->endUse()
            ->findOne();

        if($admin) {
            return array(
                'id' => 0,
                'email' => array($admin->getEmpAcc()->getEmail() => trim($admin->getFname() .' '. $admin->getLname())),
                'name' => $admin->getFname()
            );
        }

        return array();
    }



    public function RequestMeetingEmail($req, $class)
    {
        $user = $class->getUser();
        $id = $user->getId();
        $empemail = $req->request->get('taggedemail');
        $empinfo = EmpProfilePeer::getInformation($req->request->get('empId'));
        $empname = $empinfo->getFname() . " " .$empinfo->getLname();

        //admin profile information
        $data = EmpProfilePeer::getInformation($id);
        $name = $data->getFname(). " " .$data->getLname();

        $subject = $req->request->get('requestname') . " " . " Request Accepted";
        $from = array('no-reply@searchoptmedia.com', 'PROLS');
        $to = array($empemail);

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
        $userId = $user->getId();


        $empinfo = EmpProfilePeer::getInformation($id);
        $empname = $empinfo->getFname() . " " . $empinfo->getLname();


    }

    /**
     * Employee Request
     * @param $req
     * @param $class
     * @param null $reqId
     * @return int
     */
    public function requestTypeEmail($request, $class, $reqId = null)
    {
        $params = $request->request->all();
        $email = 0;
        $user = $class->getUser();
        $id   = $user->getId();

        //lets get the request details
        $obj = $params['empRequest'];

        $empinfo = EmpProfilePeer::getInformation($id);
        $empName = $empinfo->getFname() . " " . $empinfo->getLname();

        $typeOfLeave = $params['typeleave'];

        if(empty($typeOfLeave)) {
            $requestlist = ListRequestTypePeer::retrieveByPK(4);
        } else {
            $requestlist = ListRequestTypePeer::retrieveByPK($typeOfLeave);
        }

        $requestType = $requestlist->getRequestType();

        $subject = "PROLS » " . $requestType . " Request";
        $from    = array('no-reply@searchoptmedia.com', 'PROLS');
        $adminEmailList = $this->getAdmins();
        $teamLeadEmail = $this->addTeamLeadEmail($empinfo->getListDeptDeptId());

        if($teamLeadEmail)
            $adminEmailList[] = $teamLeadEmail;

        if(count($adminEmailList)) {
            foreach($adminEmailList as $ae) {
                $to = array($ae['email']);
                $requestDates = array();
                $requestLinks = array();
                $requestIds = array();

                foreach($obj as $o) {
                    $requestDates[] = array(
                        'start' => date('F d, Y', strtotime($o["start_date"])),
                        'end' => date('F d, Y', strtotime($o["end_date"])),
                        'reason' => $o["reason"],
                        'id' => $o['requestId']
                    );

                    $requestIds[] = $o['requestId'];

                    if($ae['id']!=0) {
                        $requestLinks[] = array(
                            'Approve' => array('bgColor' => '#4CAF50', 'href' => $class->generateUrl('listener_homepage', array('id' => $o['requestId'], 'type' => 'approve', 'uid' => $ae['id']), true)),
                            'Decline' => array('bgColor' => '#F44336', 'href' => $class->generateUrl('listener_homepage', array('id' => $o['requestId'], 'type' => 'decline', 'uid' => $ae['id']), true)),
                            'View' => array('href' => $class->generateUrl('view_request', array('id' => $o['requestId']), true)),
                        );
                    }
                }

                $urlParam = count($requestDates)==1 ? array('id' => $requestDates[0]['id']) : array();
                $urlParam1 = count($requestDates)==1 ? array('id' => $requestDates[0]['id'], 'uid' => $ae['id']) : array('id' => implode(',', $requestIds), 'uid' => $ae['id']);
                $urlParam2 = count($requestDates)==1 ? array('id' => $requestDates[0]['id'], 'uid' => $ae['id']) : array('id' => implode(',', $requestIds), 'uid' => $ae['id']);

                $urlParam1['type'] = 'approve';
                $urlParam2['type'] = 'decline';

                $links = $ae['id'] > 0 ? array(
                    (count($requestDates)==1 ? 'Approve':'Approve All') =>  array('href' => $class->generateUrl('listener_homepage', $urlParam1, true), 'bgColor' => '#4CAF50'),
                    (count($requestDates)==1 ? 'Decline':'Decline All') =>  array('href' => $class->generateUrl('listener_homepage', $urlParam2, true), 'bgColor' => '#F44336'),
                    (count($requestDates)==1 ? 'View':'View All') =>  array('href' => $class->generateUrl('view_request', $urlParam, true), 'bgColor' => 'transparent', 'color'=>'#26a69a;', 'borderColor' => 'rgb(38, 166, 154)'),
                ) : array();

                $emailContent = $class->renderView('AdminBundle:Templates/Email:email-has-table.html.twig', array(
                    'data' => $requestDates,
                    'title' => $requestType,
                    'greetings' => 'Hi '.$ae['name'].',',
                    'message' => "<strong>$empName</strong> has requested for a <strong>$requestType</strong>.",
                    'template' => 'leave-request',
                    'requestLinks' => $requestLinks,
                    'links' => $links
                ));

                $response = self::sendEmail($class, $subject, $from, $to, $emailContent);

                if ($response)
                    $email++;
            }
        }

        return $email;
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


}

?>