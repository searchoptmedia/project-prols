<?php

namespace ListenerBundle\Controller;

use AdminBundle\Controller\EmployeeRequestController;
use AdminBundle\Controller\EventManagerController;
use CoreBundle\Model\EmpProfilePeer;
use CoreBundle\Model\EmpRequestPeer;
use CoreBundle\Model\EventTaggedPersonsQuery;
use CoreBundle\Model\ListEventsPeer;
use CoreBundle\Utilities\Constant as C;
use CoreBundle\Utilities\Utils;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class DefaultController extends Controller
{
    public function indexAction($name)
    {

    }

    public function requestApprovalAction($type = 'approve', Request $request)
    {
        $adminId = $request->get('uid');
        $ids = $request->get('id');
        $ids = explode(",", $ids);

        $requestList = array();
        $approveList = array();
        $declineList = array();

        $newStatus = ($type=='approve' ? C::STATUS_APPROVED : C::STATUS_DECLINED);

        if(count($ids)) {
            foreach($ids as $k=>$id) {
                $req = EmpRequestPeer::retrieveByPK($id);

                if($req) {
                    $requestList[$k] = $req;
                    $requestList[$k]->employee = EmpProfilePeer::getInformation($req->getEmpAccId());

                    //approve
                    if($req->getStatus()==C::STATUS_APPROVED)
                        $approveList[] = $req;
                    //decline
                    else if($req->getStatus()==C::STATUS_DECLINED)
                        $declineList[] = $req;
                    else if($type=='approve') {
                        $this->getRequest()->request->set('emptimeid', null);
                        $this->getRequest()->request->set('requesttype', $req->getListRequestTypeId());
                        $this->getRequest()->request->set('prevstatus', $req->getStatus());
                        $this->getRequest()->request->set('status', $newStatus);
                        $this->getRequest()->request->set('reqId', $req->getId());
                        $this->getRequest()->request->set('adminid', $adminId);
                        $this->getRequest()->request->set('comment', '');
                        $this->getRequest()->request->set('reason', $req->getRequest());
                        $this->getRequest()->request->set('isChanged', false);

                        $empRequestController = new EmployeeRequestController();
                        $empRequestController->statusChangeAction( $request, $this);
                    }
                }
            }

            if(count($requestList)) {
                return $this->render('ListenerBundle::request.html.twig', array(
                    'requests' => $requestList,
                    'approveRequests' => $approveList,
                    'declineRequests' => $declineList,
                    'existApproval' => count($approveList) || count($declineList) ? 1 : 0,
                    'type' => $type
                ));
            }
        }

        throw new AccessDeniedException("Access Denied!");
    }

    public function changeStatusAction(Request $request)
    {
        $params = $request->request->all();
        $response = Utils::getForbid();
        $totalUpdates = 0;

        if(count($params['requests'])) {
            $response = Utils::getError();

            if($params['status']=='approve')
                $request->request->set('status', C::STATUS_APPROVED);
            else
                $request->request->set('status', C::STATUS_DECLINED);

            foreach($params['requests'] as $re) {
                $empRequest = EmpRequestPeer::retrieveByPK($re['id']);
                $re['comment'] = isset($re['comment']) ? $re['comment'] : '';
                $re['emptimeid'] = isset($re['emptimeid']) ? $re['emptimeid'] : '';

                if($empRequest) {
                    $request->request->set('comment', $re['comment']);
                    $request->request->set('emptimeid', $re['emptimeid']);
                    $request->request->set('reqId', $re['id']);
                    $request->request->set('requesttype', $empRequest->getListRequestTypeId());
                    $request->request->set('prevstatus', $empRequest->getStatus());

                    if($params['status']!=$empRequest->getStatus())
                        $request->request->set('isChanged', true);
                    else
                        $request->request->set('isChanged', false);

                    $response = Utils::getSuccess();
                    $empRequestController = new EmployeeRequestController();
                    $res = $empRequestController->statusChangeAction( $request, $this);

                    if($res['code']==C::CODE_SUCCESS) $totalUpdates++;
                }
            }

            if($totalUpdates)
                $response = Utils::getSuccess();
            else
                $response = Utils::getNoChange();

        }

        return new JsonResponse($response);
    }


    public function eventResponseAction($type = 'approve', Request $request)
    {
        $userId = $request->get('uid');
        $ids = $request->get('id');
        $ids = explode(",", $ids);

        $requestList = array();
        $approveList = array();
        $declineList = array();

        $newStatus = ($type=='approve' ? C::STATUS_APPROVED : C::STATUS_DECLINED);

        if(count($ids)) {
            foreach ($ids as $k => $id) {
                $events = ListEventsPeer::retrieveByPK($id);

                if ($events) {
                    $eventTag = EventTaggedPersonsQuery::_findByEmployeeAndEvent($events->getId(), $userId);

                    if($eventTag) {
                        $requestList[$k] = $events;
                        $requestList[$k]->tag = $eventTag;
                        $requestList[$k]->employee = EmpProfilePeer::getInformation($eventTag->getEmpId());

                        //approve
                        if ($events->getStatus() == C::STATUS_APPROVED)
                            $approveList[] = $events;
                        //decline
                        else if ($events->getStatus() == C::STATUS_DECLINED)
                            $declineList[] = $events;
                        else if ($type=='approve') {
                            $this->getRequest()->request->set('event_id', $events->getId());
                            $this->getRequest()->request->set('user_id', $userId);
                            $this->getRequest()->request->set('status', $newStatus);
                            $this->getRequest()->request->set('reason', '');
                            $this->getRequest()->setMethod('POST');

                            $empRequestController = new EventManagerController();
                            $response = $empRequestController->updateTagStatusAction($request, $this);
                        }
                    }
                }
            }

            if (count($requestList)) {
                return $this->render('ListenerBundle::event-response.html.twig', array(
                    'requests' => $requestList,
                    'approveRequests' => $approveList,
                    'declineRequests' => $declineList,
                    'existApproval' => count($approveList) || count($declineList) ? 1 : 0,
                    'type' => $type
                ));
            }
        }
    }

    public function eventChangeStatusAction(Request $request)
    {
        $params = $request->request->all();
        $response = Utils::getForbid();
        $totalUpdates = 0;

        if(count($params['requests'])) {
            $status = C::STATUS_DECLINED;

            if($params['status']=='approve') {
                $request->request->set('status', C::STATUS_APPROVED);
                $status = C::STATUS_APPROVED;
            } else
                $request->request->set('status', C::STATUS_DECLINED);

            foreach($params['requests'] as $re) {
                $event = ListEventsPeer::retrieveByPK($re['id']);
                $userId = $params['empId'];

                if($event) {
                    $eventTag = EventTaggedPersonsQuery::_findByEmployeeAndEvent($event->getId(), $userId);

                    if($eventTag) {
                        $this->getRequest()->request->set('event_id', $event->getId());
                        $this->getRequest()->request->set('user_id', $userId);
                        $this->getRequest()->request->set('status', $status);
                        $this->getRequest()->request->set('reason', $re['reason']);

                        if ($params['status'] != $eventTag->getStatus())
                            $request->request->set('isChanged', true);
                        else
                            $request->request->set('isChanged', false);


                        $empRequestController = new EventManagerController();
                        $res = $empRequestController->updateTagStatusAction($request, $this);

                        if ($res['code'] == C::CODE_SUCCESS) $totalUpdates++;
                    }
                }
            }

            if($totalUpdates)
                $response = Utils::getSuccess();
            else
                $response = Utils::getNoChange();

        }

        return new JsonResponse($response);
    }
}
