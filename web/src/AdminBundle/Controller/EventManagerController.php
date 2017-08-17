<?php

namespace AdminBundle\Controller;

use AdminBundle\Controller\AdminController;
use CoreBundle\Model\EmpAccPeer;
use CoreBundle\Model\EmpAccQuery;
use CoreBundle\Model\EmpProfileQuery;
use CoreBundle\Model\EmpRequestQuery;
use CoreBundle\Model\EventTaggedPersons;
use CoreBundle\Model\EventTaggedPersonsQuery;
use CoreBundle\Model\EventTagHistory;
use CoreBundle\Model\EventTagHistoryQuery;
use CoreBundle\Model\History;
use CoreBundle\Model\HistoryQuery;
use CoreBundle\Model\ListEventsTypePeer;
use CoreBundle\Utilities\Constant as C;
use CoreBundle\Utilities\Utils as U;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use CoreBundle\Model\EmpProfilePeer;
use CoreBundle\Model\EmpTimePeer;
use CoreBundle\Model\ListIpPeer;
use CoreBundle\Model\ListEvents;
use CoreBundle\Model\ListEventsPeer;
use CoreBundle\Model\ListEventsQuery;

class EventManagerController extends Controller
{
    protected $email;
    public function __construct()
    {
        $this->email = new EmailController();

        date_default_timezone_set('Asia/Manila');
    }

    /**
     * Save
     * @param Request $request
     * @return JsonResponse
     */
    public function saveAction(Request $request)
    {
        $method = $request->getMethod();
        $userId = U::getUserDetails('id', $this);
        $response = U::getForbiddenResponse();

        if($method=='POST') {
            $params = $request->request->all();
            $response['error'] = 'Event was failed to save!';

            $event = new ListEvents();
            $eventQry = null;
            $isNew = true;

            if(!empty($params['event_id']))
                $eventQry = ListEventsQuery::_findById($params['event_id']);

            $oldEventName = $eventQry ? $eventQry->getEventName() : Null;
            $oldEventVenue = $eventQry ? $eventQry->getEventVenue() : Null;
            $oldEventFrom = $eventQry ? $eventQry->getFromDate() : Null;
            $oldEventTo = $eventQry ? $eventQry->getToDate() : Null;
            $oldEventType = $eventQry ? $eventQry->getEventType() : Null;

            if(!$eventQry) {
                $params['status'] = C::STATUS_ACTIVE;
                $params['created_by'] = $userId;
                $params['date_created'] = U::getDate();
            } else {
                $isNew = false;
            }

            $params['isNew'] = $isNew;
            $params['from_date'] = date('Y-m-d H:i:s', strtotime($params['from_date']));
            $params['to_date'] = date('Y-m-d H:i:s', strtotime($params['to_date']));

            $event = $event->_save($params, $eventQry);

            if($event) {
                $response = U::getSuccessResponse();

                $params['owner_email'] = U::getUserDetails('email', $this);

                if($event->getEventType()!=C::EVENT_TYPE_HOLIDAY) {
                    if(!empty($params['tags'])) {
                        $owner = EmpProfileQuery::_findByAccId($event->getCreatedBy());
                        $empTagIds = array();
                        $params['event_tag_names'] = array();

                        foreach($params['tags'] as $email) {
                            $empAcc = EmpAccQuery::_findByEmail($email);
                            $empProfile = EmpProfileQuery::_findByAccId($empAcc->getId());

                            $params['event_tag_names'][$empAcc->getEmail()] = trim($empProfile->getFname() . ' ' .$empProfile->getLname());
                            $params['event_tag_status'][$empAcc->getEmail()] = C::STATUS_PENDING;

                            //get status
                            $eventTags = EventTaggedPersonsQuery::_findAllByEvent($event->getId());
                            if($eventTags) {
                                foreach ($eventTags as $et) {
                                    $etStatus = $et->getStatus();
                                    $em = $et->getEmpAcc()->getEmail();
                                    $params['event_tag_status'][$em] = $etStatus;
                                }
                            }
                        }

                        $params['event_tag_names'][$event->getEmpAcc()->getEmail()] = trim($owner->getFname() . ' ' . $owner->getLname());
                        $params['event_tag_status'][$event->getEmpAcc()->getEmail()] = $event->getIsGoing() ? C::STATUS_APPROVED : C::STATUS_DECLINED;

                        foreach($params['tags'] as $email) {
                            $empAcc = EmpAccQuery::_findByEmail($email);
                            $eventTag = new EventTaggedPersons();
                            $empTagIds[] = $empAcc->getId();

                            $eventTagQry = EventTaggedPersonsQuery::_findOneByEventAndEmployee($event->getId(), $empAcc->getId());
                            $eventTagged = $eventTag->_save(array(
                                'emp_id' => $empAcc->getId(),
                                'event_id' => $event->getId(),
                                'status' => $eventTagQry ? ( $eventTagQry->getStatus()!=C::STATUS_INACTIVE ? $eventTagQry->getStatus() : C::STATUS_PENDING ) : C::STATUS_PENDING
                            ), $eventTagQry);

                            $params['user_id'] = $empAcc->getId();

                            if(($eventTagged && $eventTagged->getStatus()==C::STATUS_PENDING) || ($event)) {
                                $params['from_date'] = date('F d, Y h:i a', strtotime($params['from_date']));
                                $params['to_date'] = date('F d, Y h:i a', strtotime($params['to_date']));

                                $historyCreate = EventTagHistoryQuery::_findByActionAndTag( $eventTagged->getId(), 'tag-create');

                                if(!$historyCreate) {
                                    $this->saveHistory(array(
                                        'event_tag_id' => $eventTagged->getId()
                                    ), C::HA_EVENT_TAG_ADD);
                                }

                                //links
                                $params['links'] = array(
                                    'Going' => array( 'href' => $this->generateUrl('listener_event_response', array('id' => $event->getId(), 'type' => 'approve', 'uid' => $params['user_id']), true), 'bgColor' => '#4CAF50' ),
                                    'Not Going' => array( 'href' => $this->generateUrl('listener_event_response', array('id' => $event->getId(),  'type' => 'decline', 'uid' => $params['user_id']), true), 'bgColor' => '#F44336'),
                                    'View Event' => array( 'href' => $this->generateUrl('admin_manage_events', array('id' => $event->getId()), true) )
                                );

                                if((! $eventTagQry || ($eventTagQry && $eventTagQry->getStatus()==C::STATUS_PENDING)) && $params['notify_email']) {
                                    $params['has-update'] = false;
                                    $email = $this->email->notifyEmployeeOnEvent($params, $this);

                                    if($email) {
                                        $this->saveHistory(array(
                                            'event_tag_id' => $eventTagged->getId()
                                        ), C::HA_EVENT_TAG_EMAIL);
                                    }
                                } else if($eventTagQry  && $params['notify_email']) {
                                    $params['has-update'] = true;

                                    if($oldEventName!=$event->getEventName() ||
                                        ($oldEventFrom->format('m-d-Y h:i a')!=$event->getFromDate()->format('m-d-Y h:i a')) ||
                                        ($oldEventTo->format('m-d-Y h:i a')!=$event->getToDate()->format('m-d-Y h:i a')) ||
                                        ($oldEventVenue!=$event->getEventVenue()) ||
                                        ($oldEventType!=$event->getEventType()) ) {
                                        $email = $this->email->notifyEmployeeOnEvent($params, $this);

                                        if($email)
                                            $this->saveHistory(array(
                                                'event_tag_id' => $eventTagged->getId()
                                            ), C::HA_EVENT_TAG_EMAIL);
                                    }
                                }
                            }
                        }

                        $eventTags = EventTaggedPersonsQuery::_findAllByEvent($event->getId());
                        foreach($eventTags as $et) {
                            if(!in_array($et->getEmpId(), $empTagIds)) {
                                $eventTag = new EventTaggedPersons();
                                $eventTagQry = EventTaggedPersonsQuery::_findById($et->getId());

                                if($eventTagQry)
                                    $eventTag->_save(array(
                                        'status' =>  C::STATUS_INACTIVE
                                    ), $eventTagQry);
                            }
                        }
                    }
                } else if($event->getEventType()==C::EVENT_TYPE_HOLIDAY &&  $params['notify_email']){
                    $empList = EmpAccQuery::_findAll(array(
                        'status' => array('data' => -1, 'criteria' => \Criteria::NOT_EQUAL)
                    ));

                    if($empList) {
                        $params['from_date'] = date('F d, Y', strtotime($params['from_date']));
                        $params['to_date'] = date('F d, Y', strtotime($params['to_date']));
                        $toList = array();

                        foreach($empList as $e) {
                            $em = $e->getEmail();
                            if(!empty($em))
                                $toList[] = $em;
                        }

                        if(count($toList))
                            $params['to_list'] = array($toList);

                        $params['user_id'] = $this->getUser()->getId();
                        $this->email->notifyEmployeeOnEvent($params, $this);
                    }
                }
            }
        }

        return new JsonResponse($response);
    }

    public function listAction(Request $request)
    {

        $eventTypes = ListEventsTypePeer::getAllEventType();
        $allacc = EmpAccPeer::getAllUser();
        $allAccList = EmpAccPeer::getAllUser(null);

        $response = $this->render('AdminBundle:EventManager:manage.html.twig', array(
            'eventTypes' => $eventTypes,
            'allacc' => $allacc,
            'allRecords' => $allAccList
        ));

        $response->setSharedMaxAge(3600);
        $response->headers->addCacheControlDirective('must-revalidate', true);

        return $response;
    }

    function getListAction(Request $request)
    {
        $method = $request->getMethod();

        if($method=='POST') {
            $params = $request->request->all();
            $user = $this->getUser();
            $id = $user->getId();

            $entriesData = array(
                'tag_ids' => array( 'data' => $id ),
                'status' => array( 'data' => C::STATUS_INACTIVE, 'criteria' => \Criteria::NOT_EQUAL ),
                'order' => array( 'data' => $params['sort'], 'criteria' =>$params['order'] ),
                'page' => $params['page'],
                'limit' => $params['limit'],
                'searchText' => $params['searchQry']
            );

            if($params['role']==1) {
                $entriesData['created_by'] = array('data' => array($this->getUser()->getId()), 'criteria' => \Criteria::IN);
            } else if($params['role']==2) {
                $entriesData['created_by'] = array('data' => array($this->getUser()->getId()), 'criteria' => \Criteria::NOT_IN);
            } else {
                $entriesData['created_by'] = array('data' => array($this->getUser()->getId()), 'criteria' => \Criteria::IN, '_or' => !empty($params['event_type']) ? false : true );
            }

            if(!empty($params['status'])) {
                $entriesData['tag_status'] = array('data' => $params['status']);
            }

            if(!empty($params['event_type'])) {
                $entriesData['event_type'] = array('data' => $params['event_type']);
            }

            if(!empty($params['from'])) {
                $entriesData['date_started'] =  array('data' => date('Y-m-d H:i:s',strtotime($params['from'])));
            }
            if(!empty($params['to'])) {
                $entriesData['date_ended'] =  array('data' => date('Y-m-d H:i:s',strtotime($params['to'])));
            }

            if(strtolower($user->getRole())=='employee') {

            }

            $getEvents = ListEventsQuery::_findAll($entriesData);

            unset($entriesData['page']);
            $totalEntries = ListEventsQuery::_findAll($entriesData, true);

            $activeEvent = null;

            if(!empty($params['id'])) {
                $activeEvent = ListEventsQuery::_findById($params['id']);

                if($activeEvent && $activeEvent->getStatus()==C::STATUS_INACTIVE)
                    $activeEvent = null;
                else {
                    $etags = $activeEvent->getEventTaggedPersonss();
                    if($etags) {
                        foreach($etags as $k2=>$et) {
                            $profile = EmpProfileQuery::_findByAccId($et->getEmpId());
                            $activeEvent->getEventTaggedPersonss()[$k2]->tagHistory = EventTagHistoryQuery::_find(array(
                                'TagID' => array(
                                    'value' => $et->getId()
                                ),
                                'order' => array(
                                    'value' => 'date_created',
                                    'criteria' => \Criteria::DESC
                                )
                            ));
                            $activeEvent->getEventTaggedPersonss()[$k2]->tagName = trim($profile->getFname().' '.$profile->getLname());
                        }
                    }
                }
            }

            foreach($getEvents as $k=>$e) {
                $etags = $e->getEventTaggedPersonss();
                if($etags) {
                    foreach($etags as $k2=>$et) {
                        $profile = EmpProfileQuery::_findByAccId($et->getEmpId());
                        $getEvents[$k]->getEventTaggedPersonss()[$k2]->tagHistory = EventTagHistoryQuery::_find(array(
                            'TagID' => array(
                                'value' => $et->getId()
                            ),
                            'order' => array(
                                'value' => 'date_created',
                                'criteria' => \Criteria::DESC
                            )
                        ));
                        $getEvents[$k]->getEventTaggedPersonss()[$k2]->tagName = trim($profile->getFname().' '.$profile->getLname());
                    }
                }
            }

            $response = array(
                'list' => $this->renderView('AdminBundle:EventManager:ajax-list.html.twig', array(
                    'allEvents' => $getEvents,
                    'event' => $activeEvent
                )),
                'totalEntries' => $totalEntries
            );

            return new JsonResponse($response);
        }
    }

    public function updateTagStatusAction(Request $request, $refClass = null)
    {
        $response = U::getForbid();
        $method = $request->getMethod();

        if($method=='POST') {
            $params = $request->request->all();

            if(!empty($params['event_id']) && !empty($params['user_id'])) {
                $statusId = $params['status'];
                $eventTagQry = EventTaggedPersonsQuery::_findOneByEventAndEmployee($params['event_id'], $params['user_id']);

                $eventTag = new EventTaggedPersons();

                if($eventTagQry) {
                    $origStatus = $eventTagQry->getStatus();
                    $eventTagData = array(
                        'event_id' => $params['event_id'],
                        'emp_id' => $params['user_id'],
                        'status' => $statusId
                    );

                    if(isset($params['reason']))
                        $eventTagData['reason'] = $params['reason'];

                    $eventTag = $eventTag->_save($eventTagData, $eventTagQry);

                    if($eventTag) {
                        $response = U::getSuccess();
                        //send notification
                        if($origStatus != $statusId) {
                            $this->email->notifyEmployeeOnEventUpdateTagStatus($params, $refClass?:$this);
                            $historyData = array(
                                'event_tag_id' => $eventTag->getId(),
                                'status' => $statusId,
                                'date_created' => U::getDate()
                            );
                            if(isset($params['reason']))
                                $historyData['message'] = $params['reason'];

                            $this->saveHistory($historyData, C::HA_EVENT_TAG_STAT_UPDATE);
                        }
                    }
                }
            }
        }

        if($refClass)
            return $response;

        return new JsonResponse($response);
    }

    public function cancelEventAction(Request $request)
    {
        $method = $request->getMethod();
        $result = array('error' => 'Cancelling Event Failed!');

        if($method=='POST') {
            $params = $request->request->all();
            $user = $this->getUser();
            $ownerEmail = $user->getEmail();

            if(isset($params['event_id'])) {
                $eventQry = ListEventsQuery::_findById($params['event_id']);

                if($eventQry) {
                    $eventQry->setStatus(C::STATUS_INACTIVE);

                    if($eventQry->save() || $eventQry) {
                        if($eventQry->getEventType()!=C::EVENT_TYPE_HOLIDAY) {
                            $empTagList = array();
                            $empTags = array();
                            $empTagStatus = array();

                            $tags = $eventQry->getEventTaggedPersonss();
                            if ($tags) {
                                foreach ($tags as $t) {
                                    $eventTagHistory = EventTagHistoryQuery::_findByActionAndTag($t->getId(), C::HA_EVENT_TAG_EMAIL);
                                    $email = $t->getEmpAcc()->getEmail();
                                    $profile = EmpProfileQuery::_findByAccId($t->getEmpId());
                                    $empTags[$email] = trim($profile->getFname() . ' ' . $profile->getLname());
                                    $empTagStatus[$email] = $t->getStatus();

                                    if ($eventTagHistory && $t->getStatus() != C::STATUS_INACTIVE) {
                                        if (!filter_var($email, FILTER_VALIDATE_EMAIL) === false)
                                            $empTagList[$email] = $t->getEmpId();
                                    }
                                }
                            }

                            $createdByAcc = EmpProfileQuery::_findByAccId($eventQry->getCreatedBy());
                            $empTags[$createdByAcc->getEmpAcc()->getEmail()] = trim($createdByAcc->getFname().' '.$createdByAcc->getLname());
                            $empTagStatus[$createdByAcc->getEmpAcc()->getEmail()] = $eventQry->getIsGoing() ? C::STATUS_APPROVED : C::STATUS_DECLINED;

                            if (count($empTagList)) {
                                foreach ($empTagList as $km=>$emp) {
                                    if($km!=$createdByAcc->getEmpAcc()->getEmail()):
                                        $fromDate = $eventQry->getFromDate();
                                        $toDate = $eventQry->getFromDate();

                                        $emailParams = array(
                                            'user_id' => $emp,
                                            'event_type' => $eventQry->getEventType(),
                                            'event_name' => $eventQry->getEventName(),
                                            'event_desc' => $eventQry->getEventDescription(),
                                            'event_venue' => $eventQry->getEventVenue(),
                                            'from_date' => !empty($fromDate) ? $fromDate->format('F d, Y h:i a') : '',
                                            'to_date' => !empty($toDate) ? $toDate->format('F d, Y h:i a') : '',
                                            'has-cancel' => false,
                                            'event_tag_names' => $empTags,
                                            'event_tag_status' => $empTagStatus,
                                            'owner_email' => $ownerEmail
                                        );

                                        $email = new EmailController();
                                        $email->notifyEmployeeOnEvent($emailParams, $this);
                                    endif;
                                }
                            }
                        } else {
                            $employees = EmpAccQuery::_findAll(array(
                                'status' => array('data' => -1, 'criteria'=> \Criteria::NOT_EQUAL)
                            ));

                            if($employees) {
                                foreach($employees as $emp) {
                                    $fromDate = $eventQry->getFromDate();
                                    $toDate = $eventQry->getFromDate();

                                    $emailParams = array(
                                        'user_id' => $emp->getId(),
                                        'event_type' => $eventQry->getEventType(),
                                        'event_name' => $eventQry->getEventName(),
                                        'event_desc' => $eventQry->getEventDescription(),
                                        'event_venue' => $eventQry->getEventVenue(),
                                        'from_date' => !empty($fromDate) ? $fromDate->format('F d, Y') : '',
                                        'to_date' => !empty($toDate) ? $toDate->format('F d, Y') : '',
                                        'has-cancel' => false
                                    );

                                    $email = new EmailController();
                                    $email->notifyEmployeeOnEvent($emailParams, $this);
                                }
                            }
                        }

                        $result = array('success' => 'Event Successfully Cancelled!');
                    }
                }
            }
        }

        return new JsonResponse($result);
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

    public function saveHistory($params = array(), $action = '')
    {
        $historyQry = HistoryQuery::_findModuleAction('event', $action);
        $history = new History();

        if(!$historyQry)
            $history = $history->_save(array(
                'module' => 'event',
                'action' => $action
            ));
        else
            $history = $historyQry;

        if($history) {
            if(in_array($action, array('tag-create', 'tag-send-email', 'tag-update-status'))) {
                $historyTag = new EventTagHistory();
                $params['history_id'] = $history->getId();
                $params['date_created'] = U::getDate();

                $historyTag->_save($params);
            }
        }
    }

    public function getCalendarEvents($request, $params = array())
    {
        $allEvents = ListEventsQuery::_findAll(array(
            'date_started' => $params['date_started'],
            'date_ended' => $params['date_ended'],
            'status' => array('data' => C::STATUS_INACTIVE, 'criteria' => \Criteria::NOT_EQUAL),
            'event_type' => array('data' => $params['events'], 'criteria' => \Criteria::IN),
            'calendar' => true
        ));

        $allProfiles = EmpProfilePeer::getAllProfile();
        $allInactiveProfiles = EmpProfilePeer::getAllProfile(-1);
        $listProfiles = array();

        if($allProfiles)
            foreach($allProfiles as $p) { $listProfiles[$p->getEmpAccAccId()] = trim($p->getFname().' '.$p->getLname()); }
        if($allInactiveProfiles)
            foreach($allInactiveProfiles as $p) { $listProfiles[$p->getEmpAccAccId()] = trim($p->getFname().' '.$p->getLname()); }

        foreach ($allEvents as $event) {
            $eventFromDate = $event->getFromDate();
            $eventToDate =  $event->getToDate();

            if(!is_null($eventFromDate)) {
                $eventFromDate = $eventFromDate->format('Y-m-d h:i:s');

                if(!is_null($eventToDate)) {
                    $eventToDate = $eventToDate->format('Y-m-d h:i:s');
                } else {
                    $eventToDate = $eventFromDate;
                }
            }

            $eventType = $event->getEventType();
            $eventId = $event->getId();
            $eventName = $event->getEventName();
            $eventOwnerId = $event->getCreatedBy();

            $eventOwner = EmpProfileQuery::_findByAccId($eventOwnerId);
            $eventOwnerName = trim($eventOwner->getFname() . ' ' . $eventOwner->getLname());

            $color = '#E90133';
            $eventTypeName = 'Holiday';

            if ($eventType == C::EVENT_TYPE_MEETING) {
                $eventTypeName = 'Meeting';
                $color = '#FFC82D';
            } else if ($eventType == C::EVENT_TYPE_INTERNAL) {
                $eventTypeName = 'Internal Event';
                $color = '#EB7124';
            }

            $eventTags = EventTaggedPersonsQuery::_findAllByEvent($event->getId());
            $eventTagsList = '';
            $totalTags = 0;

            foreach($eventTags as $et) {
                if($et->getStatus()!=C::STATUS_INACTIVE) {
                    $class = $et->getStatus()==C::STATUS_APPROVED ? 'green' : ($et->getStatus()==C::STATUS_DECLINED ? 'red strike' : 'gray');
                    $stat = $et->getStatus()==C::STATUS_APPROVED ? 'GOING' : ($et->getStatus()==C::STATUS_DECLINED ? 'NOT GOING' : 'PENDING');
                    $empId = $et->getEmpId();
                    $userProfile = EmpProfileQuery::_findByAccId($empId);
                    $eventTagsList .= '<a href="javascript:void(0);" class=" btn-tag-list-expand-reason '.$et->getStatus().'"> <span class="color-'.$class.'">'.( $userProfile->getFname(). ' ' . $userProfile->getLname() ).' - <span class="level-2-'.$class.'-text">'.$stat.'</span></span></a><br>';
                    $totalTags++;
                }
            }
            $eventTagsList .= '<a href="javascript:void(0);" class=" btn-tag-list-expand-reason"> <span class="'.($event->getIsGoing()==1 ? 'color-green':($event->getIsGoing()==0 ? 'color-red-text strike':'color-gray')).'">'.$listProfiles[$event->getCreatedBy()].' - <span class="level-2-'.($event->getIsGoing()==1?'green':'red').'-text">'.($event->getIsGoing()==1 ? 'GOING':'NOT GOING').'</span></span></a><br>';
            $eventTagsList = '<div class="col-md-10 sect-view-tags-list-area">'. $eventTagsList .'</div>';

            $eventData = array(
                'date' => 'From: ' . $eventFromDate . "<br>" . "To: " . $eventToDate,
                'id' => $eventId,
                'title' => $eventName,
                'start' => $eventFromDate,
                'end' => $eventToDate,
                'editable' => false,
                'color' => $color,
                'eventName' => $eventName,
                'eventOwnerName' => $eventOwnerName,
                'eventType' => $eventTypeName,
                'eventTypeId' => $eventType,
                'eventDesc' => $event->getEventDescription(),
                'eventVenue' => $event->getEventVenue(),
                'eventFromDate' => date('F d, Y h:i a', strtotime($eventFromDate)),
                'eventToDate' => date('F d, Y h:i a', strtotime($eventToDate)),
                'eventTags' => $eventTagsList,
                'type' => "event"
            );

            array_push($request, $eventData);
        }

        return $request;
    }
}
