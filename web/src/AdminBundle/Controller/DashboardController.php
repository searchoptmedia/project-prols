<?php

namespace AdminBundle\Controller;

use CoreBundle\Model\EmpRequestQuery;
use CoreBundle\Utilities\Constant as C;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DashboardController extends Controller
{
    function getCalendarEventsAction(Request $request)
    {
        $params = $request->query->all();

        $queryData = array();
        $date = $params['date'];
        $view = $params['viewType'];

        if($view=='month') {
            $startDate = (new \DateTime($date))
                ->modify('first day of this month')
                ->format(C::DATE_FORMAT_MID);

            $queryData['date_started'] = $params['date_started'] = array(
                'data' => $startDate
            );

            $endDate = (new \DateTime($date))
                ->modify('last day of this month')
                ->format(C::DATE_FORMAT_NIGHT);

            $queryData['date_ended'] = $params['date_ended'] = array(
                'data' => $endDate
            );
        }

        $params['requests'] = isset($params['requests'])? $params['requests'] : array(-1);
        $params['events'] = isset($params['events'])? $params['events'] : array(-1);

        $queryData['status'] = array( 'data' => C::STATUS_APPROVED );
        $queryData['request_type'] = array( 'data' => $params['requests'], 'criteria' => \Criteria::IN );

        $requests = EmpRequestQuery::_findAll($queryData);

        $request = [];
        foreach ($requests as $r){
            $requesttype = $r->getListRequestTypeId();
            if($requesttype == 1){
                $event = array(
                    'date' => 'From: ' . $r->getDateStarted()->format('Y-m-d') . "<br>" . "To: " . $r->getDateEnded()->format('Y-m-d'),
                    'id' => $r->getId(),
                    'title' => $r->getListRequestType()->getRequestType(). " - " . $r->getEmpAccRelatedByEmpAccId()->getEmpProfiles()[0]->getFname() . " " .
                        $r->getEmpAccRelatedByEmpAccId()->getEmpProfiles()[0]->getLname(),
                    'start' => $r->getDateStarted()->format('Y-m-d'),
                    'end' => $r->getDateEnded()->format('Y-m-d 23:59:00'),
                    'editable' => false,
                    'empname' => $r->getEmpAccRelatedByEmpAccId()->getEmpProfiles()[0]->getFname() . " " .
                        $r->getEmpAccRelatedByEmpAccId()->getEmpProfiles()[0]->getLname(),
                    'requesttype' => $r->getListRequestType()->getRequestType(),
                    'type' => "request"

                );
            }else if($requesttype == 2){
                $event = array(
                    'date' => 'From: ' . $r->getDateStarted()->format('Y-m-d') . "<br>" . "To: " . $r->getDateEnded()->format('Y-m-d'),
                    'id' => $r->getId(),
                    'title' => $r->getListRequestType()->getRequestType(). " - " . $r->getEmpAccRelatedByEmpAccId()->getEmpProfiles()[0]->getFname() . " " .
                        $r->getEmpAccRelatedByEmpAccId()->getEmpProfiles()[0]->getLname(),
                    'start' => $r->getDateStarted()->format('Y-m-d'),
                    'end' => $r->getDateEnded()->format('Y-m-d, 23:59:00'),
                    'color' => '#0072B1',
                    'editable' => false,
                    'empname' => $r->getEmpAccRelatedByEmpAccId()->getEmpProfiles()[0]->getFname() . " " .
                        $r->getEmpAccRelatedByEmpAccId()->getEmpProfiles()[0]->getLname(),
                    'requesttype' => $r->getListRequestType()->getRequestType(),
                    'type' => "request"
                );
            }else if($requesttype == 3){
                $event = array(
                    'date' => 'From: ' . $r->getDateStarted()->format('Y-m-d') . "<br>" . "To: " . $r->getDateEnded()->format('Y-m-d'),
                    'id' => $r->getId(),
                    'title' => $r->getListRequestType()->getRequestType(). " - " . $r->getEmpAccRelatedByEmpAccId()->getEmpProfiles()[0]->getFname() . " " .
                        $r->getEmpAccRelatedByEmpAccId()->getEmpProfiles()[0]->getLname(),
                    'start' => $r->getDateStarted()->format('Y-m-d'),
                    'end' => $r->getDateEnded()->format('Y-m-d 23:59:00'),
                    'color' => '#9e9e9e',
                    'editable' => false,
                    'empname' => $r->getEmpAccRelatedByEmpAccId()->getEmpProfiles()[0]->getFname() . " " .
                        $r->getEmpAccRelatedByEmpAccId()->getEmpProfiles()[0]->getLname(),
                    'requesttype' => $r->getListRequestType()->getRequestType(),
                    'type' => "request"
                );
            } else if($requesttype == 4) {
                $event = array(
                    'date' => 'From: ' . $r->getDateStarted()->format('Y-m-d') . "<br>" . "To: " . $r->getDateEnded()->format('Y-m-d'),
                    'id' => $r->getId(),
                    'title' => $r->getListRequestType()->getRequestType(). " - " . $r->getEmpAccRelatedByEmpAccId()->getEmpProfiles()[0]->getFname() . " " .
                        $r->getEmpAccRelatedByEmpAccId()->getEmpProfiles()[0]->getLname(),
                    'start' => $r->getDateStarted()->format('Y-m-d'),
                    'end' => $r->getDateEnded()->format('Y-m-d 23:59:00'),
                    'color' => '#4CAF50',
                    'editable' => false,
                    'empname' => $r->getEmpAccRelatedByEmpAccId()->getEmpProfiles()[0]->getFname() . " " .
                        $r->getEmpAccRelatedByEmpAccId()->getEmpProfiles()[0]->getLname(),
                    'requesttype' => $r->getListRequestType()->getRequestType(),
                    'type' => "request"
                );
            }
            else if($requesttype == 5) {
                $event = array(
                    'date' => 'From: ' . $r->getDateStarted()->format('Y-m-d') . "<br>" . "To: " . $r->getDateEnded()->format('Y-m-d'),
                    'id' => $r->getId(),
                    'title' => $r->getListRequestType()->getRequestType(). " - " . $r->getEmpAccRelatedByEmpAccId()->getEmpProfiles()[0]->getFname() . " " .
                        $r->getEmpAccRelatedByEmpAccId()->getEmpProfiles()[0]->getLname(),
                    'start' => $r->getDateStarted()->format('Y-m-d'),
                    'end' => $r->getDateEnded()->format('Y-m-d 23:59:00'),
                    'color' => '#F44336',
                    'editable' => false,
                    'empname' => $r->getEmpAccRelatedByEmpAccId()->getEmpProfiles()[0]->getFname() . " " .
                        $r->getEmpAccRelatedByEmpAccId()->getEmpProfiles()[0]->getLname(),
                    'requesttype' => $r->getListRequestType()->getRequestType(),
                    'type' => "request"
                );
            }
            array_push($request, $event);
        }

        $eventManager =  new EventManagerController();
        $request = $eventManager->getCalendarEvents($request, $params);

        echo json_encode($request);
        exit;
    }
}