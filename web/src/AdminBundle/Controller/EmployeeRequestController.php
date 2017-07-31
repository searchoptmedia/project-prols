<?php

namespace AdminBundle\Controller;

use CoreBundle\Model\EmpRequest;
use CoreBundle\Model\EmpRequestPeer;
use CoreBundle\Model\EmpRequestQuery;
use CoreBundle\Model\EmpTimeQuery;
use CoreBundle\Model\ListRequestTypeQuery;
use CoreBundle\Model\RequestMeetingsTag;
use CoreBundle\Model\RequestMeetingsTagPeer;
use CoreBundle\Model\RequestMeetingsTagsPeer;
use CoreBundle\Model\RequestMeetingTags;
use CoreBundle\Model\RequestMeetingTagsPeer;
use CoreBundle\Model\RequestMeetingTagsQuery;
use CoreBundle\Model\EmpProfilePeer;
use CoreBundle\Model\EmpTimePeer;
use CoreBundle\Model\ListIpPeer;
use CoreBundle\Model\EmpAccPeer;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use CoreBundle\Model\ListEventsPeer;

class EmployeeRequestController extends Controller
{
    public function requestRedirectAction()
    {
        return $this->redirectToRoute('view_request');
    }

    public function requestAction(Request $req)
    {
        $user = $this->getUser();
        $name = $user->getUsername();
        $page = 'View Request';
        $role = $user->getRole();
        $id = $user->getId();
        $admincontroller = new AdminController();
        $timename = $admincontroller->timeInOut($id);

        // redirect to index if not Admin

            if((strcasecmp($role,'ADMIN')== 0))
                $getEmployeeRequest = EmpRequestPeer::getAllRequest($id);
            else $getEmployeeRequest = EmpRequestPeer::getIndividualRequest($id);
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

            $requestcount = EmpRequestQuery::_getTotalByStatusRequest(2);

            //single record - if from email
            $activeId = $req->query->get('id');
            $activeRequest = EmpRequestPeer::retrieveByPK($activeId);

            return $this->render('AdminBundle:EmployeeRequest:request.html.twig', array(
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
                'activeRequest' => $activeRequest
            ));
    }

    public function requestMeetingAction(Request $req)
    {
        $email = new EmailController();
        $user = $this->getUser();
        $user_id = $user->getId();
        $current_date = date('Y-m-d H:i:s');
        $meetingTitle = $req->request->get('meetingTitle');
        $taggedemail = $req->request->get('taggedemail');
        $taggedMessage = $req->request->get('meetingNote');
        $meetingTimeFrom = $req->request->get('meetingTimeFrom');
        $meetingTimeTo = $req->request->get('meetingTimeTo');

        $empRequest = new EmpRequest();
        $empRequest->setMeetingTitle($meetingTitle);
        $empRequest->setRequest($taggedMessage);
        $empRequest->setStatus(2);
        $empRequest->setEmpAccId($user_id);
        $empRequest->setListRequestTypeId(4);
        $empRequest->setDateStarted($meetingTimeFrom);
        $empRequest->setDateEnded($meetingTimeTo);
        $empRequest->save();

        $request_id = $empRequest->getId();

        $tag_names = array();
        $tag_request_ids = array();

        try {
            foreach($taggedemail as $tagemail)
            {
                $emp = EmpAccPeer::getUserInfo($tagemail);
                $tag_record = EmpAccPeer::getUserInfo($tagemail);
                $tag_profile = EmpProfilePeer::getInformation($tag_record->getId());
                $tag_names[] = $tag_profile->getFname() . " " . $tag_profile->getLname();
                if(!empty($emp))
                {
                    $empTagRequest = new RequestMeetingTags();
                    $empTagRequest->setRequestId($request_id);
                    $empTagRequest->setEmpAccId($emp->getId());
                    $empTagRequest->setStatus(2);
                    $empTagRequest->save();
                    array_push($tag_request_ids, $empTagRequest->getId());
                    $send =  $email->sendEmailMeetingRequest($req, $emp->getEmail(), $this, array("type" => 1));

                    if (!$send) {
                        $this->deleteRequestMeeting($request_id, $tag_request_ids);
                        echo json_encode(array('error' => 'Email not successfully sent'));
                        exit;
                    }
                }
            }

            $tagnames = implode("," ,$tag_names);

            $send = $email->sendEmailMeetingRequest($req, $this->getUser()->getEmail() , $this ,array("names" =>$tagnames,
                "type" => 2));

            if (!$send) {
                $this->deleteRequestMeeting($request_id, $tag_request_ids);
                echo json_encode(array('error' => 'Email not successfully sent'));
                exit;
            }
            else {
                echo json_encode(array('result' => 'Request for Meeting has been successfully sent'));
                exit;
            }
        } catch (Exception $e){
            echo $e->getMessage();
        } finally {
            $this->deleteRequestMeeting($request_id, $tag_request_ids);
            echo json_encode(array('error' => 'Server Error'));
            exit;
        }
    }

    public function deleteRequestMeeting($reqId, $reqMeetingTagIds) {
        $request = EmpRequestQuery::create()->findPk($reqId);
        if(!empty($request)) {
            $request->delete();
        }

        if(!empty($reqMeetingTagIds)) {
            foreach($reqMeetingTagIds as $id) {
                $request = RequestMeetingTagsQuery::create()->findPk($id);
                if(!empty($request)) {
                    $request->delete();
                }
            }
        }
    }

    public function requestLeaveAction(Request $req)
    {
        $obj = $req->request->get('obj');                           // get json object
        $typeleave = $req->request->get('typeleave');
        $userid = $this->getUser()->getId();
        $reqIds = array();
        $ref = $this;

        $requestId = 0;

        $addedRequestList = array();

        foreach($obj as $k=>$o) {
            $leaveinput = new EmpRequest();
            $leaveinput->setRequest($o['reason']);
            $leaveinput->setStatus(2);
            $leaveinput->setDateStarted($o['start_date']);
            $leaveinput->setDateEnded($o['end_date']);
            $leaveinput->setEmpAccId($userid);
            $leaveinput->setListRequestTypeId($typeleave);
            $leaveinput->save();
            array_push($reqIds, $leaveinput->getId());

            $requestId = $leaveinput->getId();
            $addedRequestList[] = array(
                'reason' => $o['reason'],
                'start_date' => $o['start_date'],
                'end_date' => $o['end_date'],
                'requestId' => $requestId
            );
        }

        $req->request->set('obj', $addedRequestList);

        try {
            $email = new EmailController();
            $sendemail = $email->requestTypeEmail($req, $this, $requestId);
            if (!$sendemail) {
                $this->deleteRequestLeave($reqIds);
                echo json_encode(array('error' => 'Email not successfully sent'));
                exit;
            } else {
                echo json_encode(array('result' => 'Request for Leave has been successfully sent'));
                exit;
            }
        } catch (Exception $e){
            $ref->deleteRequestLeave($reqIds);
            echo json_encode(array('error' => $e->getMessage()));
            exit;
        }
    }

    public function deleteRequestLeave($reqIds) {
        foreach ($reqIds as $reqId) {
            $request = EmpRequestQuery::create()->findPk($reqId);
            if(!empty($request)) {
                $request->delete();
            }
        }
    }

    public function statusChangeAction(Request $req)
    {
        $emptimeid = $req->request->get('emptimeid');
        $requesttype = $req->request->get('requesttype');
        $prevstatus = $req->request->get('prevstatus');
        $response = array('error' => 'none');
        $accept = EmpRequestPeer::retrieveByPk($req->request->get('reqid'));
        if(isset($accept) && !empty($accept))
        {
            $accept->setAdminId($req->request->get('adminid'));
            $status = $req->request->get('status');

            if($status == 3) {
                $statusLbl = "Approved";
                if($requesttype == 3) {
                    $emptime = EmpTimePeer::retrieveByPK($emptimeid);
                    $emptime->setStatus(1);
                    $emptime->save();
                }
            }
            else{
                $statusLbl = "Declined";
                if($requesttype == 3) {
                    $emptime = EmpTimePeer::retrieveByPK($emptimeid);
                    $emptime->setStatus(-1);
                    $emptime->save();
                }
            }

            $response = "Status " . $statusLbl;

            if($prevstatus != $status) {
                $accept->setStatus($status);
                $email = new EmailController();
                $sendemail = $email->acceptRequestEmail($req, $this);
            }

            if($accept->save())
            {
                $response = array('result' => $response);
            }
            else
            {
                $response = array('error' => 'Status not changed');
            }

        }
        else
        {
            $response = array('error' => 'not found');
        }

        echo json_encode($response);
        exit;
    }

    public function addRequestCalendarAction(){

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

        $requestcount = EmpRequestQuery::_getTotalByStatusRequest(2);

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

    public function editRequestAction(Request $req) {
        $result = array('result' => 'error');
        $request = EmpRequestQuery::create()->findPk($req->request->get('req_id'));

        if(!empty($request)) {
            $oldData = array(
                'startDate' => $request->getDateStarted()->format('F d, Y'),
                'endDate' => $request->getDateEnded()->format('F d, Y'),
                'request' => $request
            );

            $request->setStatus($req->request->get('status'));
            $request->setDateStarted($req->request->get('start_date'));
            $request->setDateEnded($req->request->get('end_date'));
            $request->setRequest($req->request->get('reason'));
            $request->save();
            $lastid = $request->getId();

            if(!empty($lastid)) {
                $result = array('result' => 'Success');

                $email = new EmailController();
                $sendemail = $email->notifyRequestEmail($req, $this, "UPDATED", $oldData);

                if($sendemail == 0) {
                    //$this->deleteAction($req);
                    $result = array('error' => 'Email not sent');
                } else {
                    $result = array('result' => 'ok');
                }
            } else {
                $result = array('error' => 'Request update not successful');
            }
        } else {
            $result = array('error' => 'Request not found');
        }

        echo json_encode($result);
        exit;
    }

    public function deleteRequestAction(Request $req) {
        $result = array('result' => 'error');
        $request = EmpRequestQuery::create()->findPk($req->request->get('req_id'));

        if(!empty($request)) {
            $oldData = array(
                'startDate' => $request->getDateStarted()->format('F d, Y'),
                'endDate' => $request->getDateEnded()->format('F d, Y'),
                'reason' => $request->getRequest(),
                'request' => $request
            );

            $empTimeId = $request->getEmpTimeId();
            $empTime = EmpTimePeer::retrieveByPK($empTimeId);

            $email = new EmailController();
            $sendemail = $email->notifyRequestEmail($req, $this, "CANCELLED", $oldData);

            if(! $sendemail) {
                //$this->deleteRequestAction($req);
                $result = array('error' => 'Email not sent!');
            } else {
                $request->setStatus(-1);
                $request->save();

                if($empTime)
                    $empTime->setStatus(-2)->save();


                $result = array('result' => 'Request Successfully Deleted!');
            }
        } else {
            $result = array('error' => 'Request not found!');
        }

        echo json_encode($result);
        exit;
    }
}