<?php

namespace AdminBundle\Controller;

use AdminBundle\Controller\AdminController;
use CoreBundle\Model\EmpRequestQuery;
use CoreBundle\Model\RequestMeetingsTag;
use CoreBundle\Model\RequestMeetingsTagPeer;
use CoreBundle\Model\RequestMeetingsTagsPeer;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use CoreBundle\Model\EmpProfilePeer;
use CoreBundle\Model\EmpTimePeer;
use CoreBundle\Model\ListIpPeer;
use CoreBundle\Model\ListEvents;
use CoreBundle\Model\ListEventsPeer;
use CoreBundle\Model\ListEventsQuery;

class EventManagerController extends Controller
{
    public function ManageAction()
    {
        $user = $this->getUser();
        $page = 'Manage Events';
        $id = $user->getId();
        $timename = AdminController::timeInOut($id);

        $getEvents = ListEventsPeer::getAllEvents();
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
            }else
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
        }

        $systime = date('H:i A');
        $timetoday = date('h:i A');
        $afternoon = date('H:i A', strtotime('12 pm'));

        $userip = InitController::getUserIP($this);
        $ip_add = ListIpPeer::getValidIP($userip);
        $is_ip  = InitController::checkIP($userip);

        $getTime = EmpTimePeer::getAllTime();
        $getAllProfile = EmpProfilePeer::getAllProfile();
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

        return $this->render('AdminBundle:EventManager:manage.html.twig', array(
            'page' => $page,
            'user' => $user,
            'timename' => $timename,
            'allEvents' => $getEvents,
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
            'timetoday' => $timetoday
        ));
    }

    public function addAction(Request $req) {
        $notify = $req->request->get('notify');
        $event = new ListEvents();
        $event->setDate($req->request->get('event_date'));
        $event->setName($req->request->get('event_name'));
        $event->setDescription($req->request->get('event_desc'));
        $event->setType($req->request->get('event_type'));
        $event->save();

        if($notify == 1) {
            $this->notifyAction($req);
        } else {
            echo json_encode(array('result' => 'ok'));
            exit;
        }
    }

    public function notifyAction(Request $req) {
        $email = new EmailController();
        $sendemail = $email->notifyEventEmail($req, $this);

        if($sendemail == 0) {
            $this->deleteAction($req);
            echo json_encode(array('error' => 'Email not sent'));
            exit;
        } else {
            echo json_encode(array('result' => 'ok'));
            exit;
        }
    }

    public function editAction(Request $req) {
        $result = array('result' => 'error');
        $event = ListEventsQuery::create()->findPk($req->request->get('event_id'));

        if(!empty($event)) {
            $event->setDate($req->request->get('event_date'));
            $event->setName($req->request->get('event_name'));
            $event->setDescription($req->request->get('event_desc'));
            $event->setType($req->request->get('event_type'));
            $event->save();
            $lastid = $event->getId();

            if(!empty($lastid)) {
                $result = array('result' => 'Success');
            } else {
                $result = array('error' => 'Event update not successful');
            }
        } else {
            $result = array('error' => 'Event not found');
        }

        echo json_encode($result);
        exit;
    }

    public function deleteAction(Request $req) {
        $result = array('result' => 'error');
        $event = ListEventsQuery::create()->findPk($req->request->get('event_id'));

        if(!empty($event)) {
            $event->delete();
            $deleted = $event->isDeleted();

            if($deleted) {
                $result = array('result' => 'Success');
            } else {
                $result = array('error' => 'Event update not successful');
            }
        } else {
            $result = array('error' => 'Event not found');
        }

        echo json_encode($result);
        exit;
    }

    public function showEventsAction($request) {
        $allEvents = ListEventsPeer::getAllEvents();
        foreach ($allEvents as $event) {
            $eventdate = $event->getDate();
            $eventType = $event->getType();
            $eventId = $event->getId();
            $eventName = $event->getName();

            if ($eventType == "HOLIDAY") {
                $event = array(
                    'date' => 'From: ' . $eventdate->format('Y-m-d') . "<br>" . "To: " . $eventdate->format('Y-m-d 23:59:00'),
                    'id' => $eventId,
                    'title' => $eventName,
                    'start' => $eventdate->format('Y-m-d'),
                    'end' => $eventdate->format('Y-m-d 23:59:00'),
                    'editable' => false,
                    'color' => '#64b5f6',
                    'eventName' => $eventName,
                    'eventType' => "Holiday Event",
                    'type' => "event"
                );
            } else {
                $event = array(
                    'date' => 'From: ' . $eventdate->format('Y-m-d') . "<br>" . "To: " . $eventdate->format('Y-m-d 23:59:00'),
                    'id' => $eventId,
                    'title' => $eventName,
                    'start' => $eventdate->format('Y-m-d'),
                    'end' => $eventdate->format('Y-m-d 23:59:00'),
                    'editable' => false,
                    'color' => '#e57373',
                    'eventName' => $eventName,
                    'eventType' => "Regular Event",
                    'type' => "event"
                );
            }

            array_push($request, $event);
        }

        return $request;
    }
}
