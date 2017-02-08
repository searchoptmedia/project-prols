<?php

namespace AdminBundle\Controller;

use CoreBundle\Model\EmpRequest;
use CoreBundle\Model\EmpRequestPeer;
use CoreBundle\Model\EmpRequestQuery;
use CoreBundle\Model\RequestMeetingsTag;
use CoreBundle\Model\RequestMeetingsTagPeer;
use CoreBundle\Model\RequestMeetingsTagsPeer;
use CoreBundle\Model\RequestMeetingTags;
use CoreBundle\Model\RequestMeetingTagsQuery;
use CoreBundle\Model\EmpProfilePeer;
use CoreBundle\Model\EmpTimePeer;
use CoreBundle\Model\ListIpPeer;
use CoreBundle\Model\EmpAccPeer;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use CoreBundle\Model\ListEventsPeer;

class RequestTypeController extends Controller
{
    public function requestAction()
    {
        $user = $this->getUser();
        $name = $user->getUsername();
        $page = 'View Request';
        $role = $user->getRole();
        $id = $user->getId();
        $capabilities = $user->getCapabilities();
        $timename = AdminController::timeInOut($id);
        // redirect to index if not Admin
        if(empty($capabilities) && (strcasecmp($role, 'employee') == 0))
        {
            return $this->redirect($this->generateUrl('admin_homepage'));
        }
        else
        {
            if(empty($capabilities) && (strcasecmp($role,'ADMIN')== 0))
            {
                $getEmployeeRequest = EmpRequestPeer::getAllRequest();
            }
            else{
                $getEmployeeRequest = EmpRequestPeer::getIndividualRequest($id);
            }

            $timedata = EmpTimePeer::getTime($id);
            $timeflag = 0;
            $currenttimein = 0;
            $currenttimeout = 0;

            for ($ctr = 0; $ctr < sizeof($timedata); $ctr++)
            {
                $checktimein = $timedata[$ctr]->getTimeIn();
                $checktimeout = $timedata[$ctr]->getTimeOut();
                if(!is_null($checktimein) && is_null($checktimeout))
                {
                    $currenttimein = $checktimein->format('h:i A');
                }
                else
                {
                    $currenttimein = 0;
                    $currenttimeout = $checktimeout->format('h:i A');
                }
            }
            $timeoutdata = '';
            $checkipdata = null;
            if(!empty($timedata))
            {
                $datetoday = date('Y-m-d');
                $emp_time = EmpTimePeer::getTime($id);
                $currenttime = sizeof($emp_time) - 1;
                $timein_data = $emp_time[$currenttime]->getTimeIn();
                $timeout_data = $emp_time[$currenttime]->getTimeOut();
                $checkipdata = $emp_time[$currenttime]->getCheckIp();
            }
            $systime = date('H:i A');
            $timetoday = date('h:i A');
            $afternoon = date('H:i A', strtotime('12 pm'));

            $et = EmpTimePeer::getEmpLastTimein($id);
            if(!empty($et))
            {
                $lasttimein	= $et->getTimeIn()->format('M d, Y, h:i A');
                $emptimedate = $et->getDate();
                if($emptimedate->format('Y-m-d') == $datetoday)
                {
                    $timeflag = 1;
                }
                if(! empty($et->getTimeOut()))
                    $isTimeOut = 'true';
            }
            $userip = InitController::getUserIP($this);
            $ip_add = ListIpPeer::getValidIP($userip);
            $is_ip  = InitController::checkIP($userip);

            $requestcount = EmpRequestQuery::create()
                ->filterByStatus('Pending')
                ->find()->count();


            return $this->render('AdminBundle:RequestType:request.html.twig', array(
                'name' => $name,
                'page' => $page,
                'user' => $user,
                'timename' => $timename,
                'allrequest' => $getEmployeeRequest,
                'userid' =>$id,
                'timeflag' => $timeflag,
                'currenttimein' => $currenttimein,
                'currenttimeout' => $currenttimeout,
                'matchedip' => is_null($ip_add) ? "" : $ip_add->getAllowedIp(),
                'userip' => $userip,
                'checkipdata' => $checkipdata,
                'checkip' => $is_ip,
                'systime' => $systime,
                'afternoon' => $afternoon,
                'requestcount' => $requestcount,
                'isTimeoutAlready' => !empty($isTimeOut) ? $isTimeOut : null,
                'lasttimein' => !empty($lasttimein) ? $lasttimein : null,
                'timetoday' => $timetoday,
            ));
        }
    }

    public function requestMeetingAction(Request $req)
    {
        //request meeting functionality
        $email = new EmailController();

        $sendemail = $email->requestTypeEmail($req, $this);

        $requestMeeting = new EmpRequest();
        date_default_timezone_set('Asia/Manila');
        $current_date = date('Y-m-d H:i:s');
        $requestMeeting->setRequest($req->request->get('reqmeetmessage'));
        $taggedemail = $req->request->get('taggedemail');
        $requestId = $req->request->get('reqId');

        $tag_names = array();
        $tag_id = array();
        $old_tagged_ids = array();

        $requesttag = new RequestMeetingTags();

        if(!empty($requestId)) {
            $requesttag = EmpRequestPeer::retrieveByPK($requestId);

            $reqMeetingTags = RequestMeetingTagsQuery::create()
                ->filterByRequestId($requestId->getId())
                ->filterByStatus(1)
                ->find();


            if($reqMeetingTags)
            {
                foreach ($reqMeetingTags as $r)
                {
                    $old_tagged_ids[] = $r->getEmpAccId();
                }
            }
        } else {
            $requesttag = new EmpRequest();
        }


        foreach($taggedemail as $tagemail)
        {

            $tag_record = EmpAccPeer::getUserInfo($tagemail);
            $tag_profile = EmpProfilePeer::getInformation($tag_record->getId());

            $tag_names[] = $tag_profile->getFname() . " " . $tag_profile->getLname();

            $tag_id[]  = $tag_profile->getId();

            $send =  $email->sendEmailMeetingRequest($req, $tag_record->getEmail(), $this, array("type" => 1));


        }
        if(!empty($requestId))
        {
            foreach ($old_tagged_ids as $empId)
            {
                if (!in_array($empId, $tag_id))
                {
                    $reqMeetingTags = RequestMeetingTagsQuery::create()
                        ->filterByRequestId($requesttag->getId())
                        ->filterByEmpAccId($empId)
                        ->filterByStatus(1)
                        ->findOne();

                    $reqMeetingTags->setStatus(0);
                    $reqMeetingTags->save();
                }
            }
        }

        $tagnames = implode("," ,$tag_names);

        $send = $email->sendEmailMeetingRequest($req, $this->getUser()->getEmail() , $this ,array("names" =>$tagnames,
            "type" => 2));

        $requestMeeting->setStatus('Pending');
        $requestMeeting->setDateStarted($current_date);
        $requestMeeting->setDateEnded($current_date);
        $requestMeeting->setEmpAccId($this->getUser()->getId());
        $requestMeeting->setListRequestTypeId(4);
        $requestMeeting->save();
        echo json_encode(array('result' => 'ok'));
        exit;
    }

    public function requestLeaveAction(Request $req)
    {
        $obj = $req->request->get('obj');                           // get json object
        $object = json_decode($obj, true);                          // decode json
        $typeleave = $req->request->get('typeleave');
        $userid = $this->getUser()->getId();

        for ($i = 0; $i <= $object["unique_id"]; $i++) {            // loop for saving all dates
            $start = "start_date" . $i;
            $end = "end_date" . $i;
            $reasonleave = "reasonleave" . $i;

            $leaveinput = new EmpRequest();
            $leaveinput->setRequest($object["$reasonleave"]);
            $leaveinput->setStatus('Pending');
            $leaveinput->setDateStarted($object["$start"]);
            $leaveinput->setDateEnded($object["$end"]);
            $leaveinput->setEmpAccId($userid);
            $leaveinput->setListRequestTypeId($typeleave);
            $leaveinput->save();
        }

        $email = new EmailController();
        $sendemail = $email->requestTypeEmail($req, $this);
        if (!$sendemail)
        {
            $emailresp = 'No email sent';
        }
        else
        {
            $emailresp = 'Email Sent';
        }
        echo json_encode(array('result' => 'ok', 'email' => $emailresp));
        exit;
    }

    public function statusAcceptAction(Request $req, $id, $id2)
    {
        $accept = EmpRequestPeer::retrieveByPk($id);
        if(isset($accept) && !empty($accept))
        {
            $accept->setAdminId($id2);
            $accept->setStatus('Accepted');

            $email = new EmailController();

            $sendemail = $email->acceptRequestEmail($req, $this);

            if($accept->save())
            {
                $response = array('response' => 'success');
            }
            else
            {
                $response = array('response' => 'not saved');
            }

        }
        else
        {
            $response = array('response' => 'not found');
        }

        echo json_encode($response);
        exit;
    }

    public function addRequestCalendarAction(){
        $allRequest = EmpRequestPeer::getAllAcceptedRequest();
        $datetoday = date('Y-m-d');
        $timedintoday = EmpTimePeer::getAllTimeToday($datetoday);

        $request = [];
        foreach ($allRequest as $a){
            $requesttype = $a->getListRequestTypeId();
            if($requesttype == 1){
                $event = array(
                    'date' => 'From: ' . $a->getDateStarted()->format('Y-m-d') . "<br>" . "To: " . $a->getDateEnded()->format('Y-m-d'),
                    'id' => $a->getId(),
                    'title' => $a->getListRequestType()->getRequestType(). " - " . $a->getEmpAccRelatedByEmpAccId()->getEmpProfiles()[0]->getFname() . " " .
                        $a->getEmpAccRelatedByEmpAccId()->getEmpProfiles()[0]->getLname(),
                    'start' => $a->getDateStarted()->format('Y-m-d'),
                    'end' => $a->getDateEnded()->format('Y-m-d 23:59:00'),
                    'editable' => false,
                    'empname' => $a->getEmpAccRelatedByEmpAccId()->getEmpProfiles()[0]->getFname() . " " .
                        $a->getEmpAccRelatedByEmpAccId()->getEmpProfiles()[0]->getLname(),
                    'requesttype' => $a->getListRequestType()->getRequestType(),
                    'type' => "request"

                );
            }else if($requesttype == 2){
                $event = array(
                    'date' => 'From: ' . $a->getDateStarted()->format('Y-m-d') . "<br>" . "To: " . $a->getDateEnded()->format('Y-m-d'),
                    'id' => $a->getId(),
                    'title' => $a->getListRequestType()->getRequestType(). " - " . $a->getEmpAccRelatedByEmpAccId()->getEmpProfiles()[0]->getFname() . " " .
                        $a->getEmpAccRelatedByEmpAccId()->getEmpProfiles()[0]->getLname(),
                    'start' => $a->getDateStarted()->format('Y-m-d'),
                    'end' => $a->getDateEnded()->format('Y-m-d, 23:59:00'),
                    'color' => '#ff9800',
                    'editable' => false,
                    'empname' => $a->getEmpAccRelatedByEmpAccId()->getEmpProfiles()[0]->getFname() . " " .
                        $a->getEmpAccRelatedByEmpAccId()->getEmpProfiles()[0]->getLname(),
                    'requesttype' => $a->getListRequestType()->getRequestType(),
                    'type' => "request"
                );
            }else if($requesttype == 3){
                $event = array(
                    'date' => 'From: ' . $a->getDateStarted()->format('Y-m-d') . "<br>" . "To: " . $a->getDateEnded()->format('Y-m-d'),
                    'id' => $a->getId(),
                    'title' => $a->getListRequestType()->getRequestType(). " - " . $a->getEmpAccRelatedByEmpAccId()->getEmpProfiles()[0]->getFname() . " " .
                        $a->getEmpAccRelatedByEmpAccId()->getEmpProfiles()[0]->getLname(),
                    'start' => $a->getDateStarted()->format('Y-m-d'),
                    'end' => $a->getDateEnded()->format('Y-m-d 23:59:00'),
                    'color' => '#9e9e9e',
                    'editable' => false,
                    'empname' => $a->getEmpAccRelatedByEmpAccId()->getEmpProfiles()[0]->getFname() . " " .
                        $a->getEmpAccRelatedByEmpAccId()->getEmpProfiles()[0]->getLname(),
                    'requesttype' => $a->getListRequestType()->getRequestType(),
                    'type' => "request"
                );
            } else if($requesttype == 4) {
                $event = array(
                    'date' => 'From: ' . $a->getDateStarted()->format('Y-m-d') . "<br>" . "To: " . $a->getDateEnded()->format('Y-m-d'),
                    'id' => $a->getId(),
                    'title' => $a->getListRequestType()->getRequestType(). " - " . $a->getEmpAccRelatedByEmpAccId()->getEmpProfiles()[0]->getFname() . " " .
                        $a->getEmpAccRelatedByEmpAccId()->getEmpProfiles()[0]->getLname(),
                    'start' => $a->getDateStarted()->format('Y-m-d'),
                    'end' => $a->getDateEnded()->format('Y-m-d 23:59:00'),
                    'color' => '#4CAF50',
                    'editable' => false,
                    'empname' => $a->getEmpAccRelatedByEmpAccId()->getEmpProfiles()[0]->getFname() . " " .
                        $a->getEmpAccRelatedByEmpAccId()->getEmpProfiles()[0]->getLname(),
                    'requesttype' => $a->getListRequestType()->getRequestType(),
                    'type' => "request"
                );
            }
            else if($requesttype == 5) {
                $event = array(
                    'date' => 'From: ' . $a->getDateStarted()->format('Y-m-d') . "<br>" . "To: " . $a->getDateEnded()->format('Y-m-d'),
                    'id' => $a->getId(),
                    'title' => $a->getListRequestType()->getRequestType(). " - " . $a->getEmpAccRelatedByEmpAccId()->getEmpProfiles()[0]->getFname() . " " .
                        $a->getEmpAccRelatedByEmpAccId()->getEmpProfiles()[0]->getLname(),
                    'start' => $a->getDateStarted()->format('Y-m-d'),
                    'end' => $a->getDateEnded()->format('Y-m-d 23:59:00'),
                    'color' => '#F44336',
                    'editable' => false,
                    'empname' => $a->getEmpAccRelatedByEmpAccId()->getEmpProfiles()[0]->getFname() . " " .
                        $a->getEmpAccRelatedByEmpAccId()->getEmpProfiles()[0]->getLname(),
                    'requesttype' => $a->getListRequestType()->getRequestType(),
                    'type' => "request"
                );
            }
            array_push($request, $event);
        }

        $request = EventManagerController::showEventsAction($request);

        echo json_encode($request);
        exit;
    }

    public function notifAction()
    {
        $user = $this->getUser();
        $name = $user->getUsername();
        $page = 'View Request';
        $role = $user->getRole();
        $id = $user->getId();
        $timename = AdminController::timeInOut($id);

//		$data = EmpLeaveQuery::create()
//		->filterByStatus('Pending')
//		->find()->count();

        $getRequests = EmpRequestPeer::getAllRequest();
        $timedata = EmpTimePeer::getTime($id);
        $currenttimein = 0;
        $currenttimeout = 0;
        $timeflag = 0;

        //get last timed in
        for ($ctr = 0; $ctr < sizeof($timedata); $ctr++)
        {
            $checktimein = $timedata[$ctr]->getTimeIn();
            $checktimeout = $timedata[$ctr]->getTimeOut();
            if(!is_null($checktimein) && is_null($checktimeout))
            {
                $currenttimein = $checktimein->format('h:i A');

            }
            else
            {
                $currenttimein = 0;
                $currenttimeout = $checktimeout->format('h:i A');
            }
        }
        $checkipdata = null;
        //check if already timed in today
        if(!empty($timedata))
        {

            $overtime = date('h:i A',strtotime('+9 hours',strtotime($currenttimein)));
            $datetoday = date('Y-m-d');
            $emp_time = EmpTimePeer::getTime($id);
            $currenttime = sizeof($emp_time) - 1;
            $timein_data = $emp_time[$currenttime]->getTimeIn();
            $timeout_data = $emp_time[$currenttime]->getTimeOut();
            $checkipdata = $emp_time[$currenttime]->getCheckIp();
            // echo $checkipdata;

        }

        $systime = date('H:i A');
        $timetoday = date('h:i A');
        $afternoon = date('H:i A', strtotime('12 pm'));

        $userip = InitController::getUserIP($this);
        $ip_add = ListIpPeer::getValidIP($userip);
        $is_ip  = InitController::checkIP($userip);
        $et = EmpTimePeer::getEmpLastTimein($id);
        if(!empty($et))
        {
            $lasttimein	= $et->getTimeIn()->format('M d, Y, h:i A');
            $emptimedate = $et->getDate();
            if($emptimedate->format('Y-m-d') == $datetoday)
            {
                $timeflag = 1;
            }
            if(! empty($et->getTimeOut()))
                $isTimeOut = 'true';


        }
        $requestcount = EmpRequestQuery::create()
            ->filterByStatus('Pending')
            ->find()->count();
        return $this->render('AdminBundle:Employee:notif.html.twig', array(
            'name' => $name,
            'page' => $page,
            'role' => $role,
            'user' => $user,
            'timename' => $timename,
            'id' => $id,
            'getRequests' => $getRequests,
            'userip' => $userip,
            'matchedip' => is_null($ip_add) ? "" : $ip_add->getAllowedIp(),
            'checkipdata' => $checkipdata,
            'checkip' => $is_ip,
            'currenttimein' => $currenttimein,
            'timeflag' => $timeflag,
            'systime' => $systime,
            'afternoon' => $afternoon,
            'requestcount' => $requestcount,
            'isTimeoutAlready' => !empty($isTimeOut) ? $isTimeOut : null,
            'lasttimein' => !empty($lasttimein) ? $lasttimein : null,
            'timetoday' => $timetoday,
        ));
    }
}