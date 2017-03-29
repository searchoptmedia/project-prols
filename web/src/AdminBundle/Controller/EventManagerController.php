<?php

namespace AdminBundle\Controller;

use AdminBundle\Controller\AdminController;
use CoreBundle\Model\EmpAccPeer;
use CoreBundle\Model\EmpRequestQuery;
use CoreBundle\Model\EventTaggedPersons;
use CoreBundle\Model\EventTaggedPersonsQuery;
use CoreBundle\Model\ListEventsTypePeer;
use CoreBundle\Model\RequestMeetingsTag;
use CoreBundle\Model\RequestMeetingsTagPeer;
use CoreBundle\Model\RequestMeetingsTagsPeer;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Definition\Exception\Exception;
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
        $admincontroller = new AdminController();
        $timename = $admincontroller->timeInOut($id);

        $getEvents = ListEventsPeer::getAllEvents($id);
//        echo "<pre>";
//        var_dump($getEvents);
//        exit;
//        echo "<pre>";
//        var_dump($getEvents);
//        exit;
//        $getEvents = null;
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

        $eventTypes = ListEventsTypePeer::getAllEventType();
        $allacc = EmpAccPeer::getAllUser();

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
            'timetoday' => $timetoday,
            'eventTypes' => $eventTypes,
            'allacc' => $allacc
        ));
    }

    public function addAction(Request $req) {
        date_default_timezone_set('Asia/Manila');
        $datetimetoday 	= date('Y-m-d H:i:s');
        $user = $this->getUser();
        $id = $user->getId();
        $eventType = $req->request->get('event_type');

        if($eventType == 1) {
            // HOLIDAY
            $event = new ListEvents();
            $event->setStatus(1);
            $event->setDateCreated($datetimetoday);
            $event->setFromDate($req->request->get('from_date'));
            $event->setToDate($req->request->get('to_date'));
            $event->setEventName($req->request->get('event_name'));
            $event->setEventDescription("");
            $event->setEventType($eventType);
            $event->setCreatedBy($id);

            if($event->save()) {
                $event_id = $event->getId();
                try {
                    $success = $this->notify($req, $datetimetoday, null);
                    if ($success) {
                        // email successfully sent
                        echo json_encode(array('result' => 'Event Successfully Added', 'event_id' => $event_id));
                        exit;
                    } else {
                        // email not successfully sent
                        echo json_encode(array('error' => 'Event not Successfully Added'));
                        exit;
                    }
                } catch(Exception $e) {
                    //
                } finally {
                    $deleted = $this->deleteById($event_id);
                    echo json_encode(array('error' => 'Server Error'));
                    exit;
                }
            } else {
                echo json_encode(array('error' => 'Event not Successfully Added'));
                exit;
            }
        } else {
            $taggedEmail = $req->request->get('taggedPersons');
            $event = new ListEvents();
            $event->setStatus(1);
            $event->setDateCreated($datetimetoday);
            $event->setFromDate($req->request->get('parse_from_date'));
            $event->setToDate($req->request->get('parse_to_date'));
            $event->setEventName($req->request->get('event_name'));
            $event->setEventDescription($req->request->get('event_desc'));
            $event->setEventType($eventType);
            $event->setCreatedBy($id);

            $tag_names = array();
            $tag_request_ids = array();

            if($event->save()) {
                $event_id = $event->getId();
                try {
                    foreach($taggedEmail as $tagemail)
                    {
                        $emp = EmpAccPeer::getUserInfo($tagemail);
                        $tag_record = EmpAccPeer::getUserInfo($tagemail);
                        $tag_profile = EmpProfilePeer::getInformation($tag_record->getId());
                        $tag_names[] = $tag_profile->getFname() . " " . $tag_profile->getLname();
                        if(!empty($emp))
                        {
                            $empTag = new EventTaggedPersons();
                            $empTag->setEventId($event_id);
                            $empTag->setEmpId($emp->getId());
                            $empTag->setStatus(2);
                            $empTag->save();

                            array_push($tag_request_ids, $empTag->getId());
                            $send = $this->notify($req, $datetimetoday, array("type" => 1, "guestEmail" => $emp->getEmail()));

                            if (!$send) {
                                $this->deleteEventTagged($event_id, $tag_request_ids);
                                echo json_encode(array('error' => 'Email not successfully sent'));
                                exit;
                            }
                        }
                    }

                    $tagnames = implode(", " ,$tag_names);
                    $send = $this->notify($req, $datetimetoday, array("names" =>$tagnames, "type" => 2, "guestEmail" => $this->getUser()->getEmail()));

                    if (!$send) {
                        $this->deleteEventTagged($event_id, $tag_request_ids);
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
                    $this->deleteEventTagged($event_id, $tag_request_ids);
                    echo json_encode(array('error' => 'Server Error'));
                    exit;
                }
            } else {
                echo json_encode(array('error' => 'Event not Successfully Added'));
                exit;
            }
        }
    }

    public function deleteEventTagged($reqId, $reqTagIds) {
        $request = EmpRequestQuery::create()->findPk($reqId);
        if(!empty($request)) {
            $request->delete();
        }

        if(!empty($reqTagIds)) {
            foreach($reqTagIds as $id) {
                $request = EventTaggedPersonsQuery::create()->findPk($id);
                if(!empty($request)) {
                    $request->delete();
                }
            }
        }
    }

    public function deleteById($id) {
        $event = ListEventsQuery::create()->findPk($id);
        if(!empty($event)) {
            $event->delete();
            $deleted = $event->isDeleted();
            return $deleted;
        } else {
            return false;
        }
    }

    public function notifyAction(Request $req) {
        $success = $this->notify($req);
        try {
            if ($success) {
                // email successfully sent
                echo json_encode(array('result' => 'Event Successfully Notified'));
                exit;
            } else {
                // email not successfully sent
                echo json_encode(array('error' => 'Notification Error: Email not sent'));
                exit;
            }
        } catch (Exception $e) {
            //
        } finally {
            echo json_encode(array('error' => 'Server Error'));
            exit;
        }
    }

    public function notify(Request $req, $datetimecreated, $arr) {
        $email = new EmailController();
        $sendemail = $email->notifyEventEmail($req, $this, $datetimecreated, $arr);

        if($sendemail == 0) {
            //$delete = $this->delete($req);
            return false;
        } else {
            return true;
        }
    }

    public function editAction(Request $req) {
        $event = ListEventsQuery::create()->findPk($req->request->get('event_id'));

        if(!empty($event)) {
            $event->setDate($req->request->get('event_date'));
            $event->setName($req->request->get('event_name'));
            $event->setDescription($req->request->get('event_desc'));
            $event->setType($req->request->get('event_type'));
            $event->save();
            $lastid = $event->getId();

            if(!empty($lastid)) {
                $result = array('result' => 'Event update successful');
            } else {
                $result = array('error' => 'Event update not successful');
            }
        } else {
            $result = array('error' => 'Event not found');
        }

        echo json_encode($result);
        exit;
    }

    public function delete(Request $req) {
        $event = ListEventsQuery::create()->findPk($req->request->get('event_id'));
        if(!empty($event)) {
            $event->delete();
            $deleted = $event->isDeleted();
            return $deleted;
        } else {
            return false;
        }
    }

    public function deleteAction(Request $req) {
        $deleted = $this->delete($req);
        if($deleted)
            $result = array('result' => 'Event Successfully Deleted');
        else $result = array('error' => 'Event not Successfully Deleted');

        echo json_encode($result);
        exit;
    }

    public function showEventsAction($request, $userId = 0, $userLevel) {
        $allEvents = ListEventsPeer::getAllEvents($userId, $userLevel);
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
