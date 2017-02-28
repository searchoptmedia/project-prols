<?php

namespace AdminBundle\Controller;

use AdminBundle\Controller\DefaultController;
use CoreBundle\Model\EmpProfileQuery;
use CoreBundle\Model\EmpRequest;
use CoreBundle\Model\EmpRequestPeer;
use CoreBundle\Model\EmpRequestQuery;
use CoreBundle\Model\EmpTimeReject;
use CoreBundle\Model\RequestMeetingsTag;
use CoreBundle\Model\RequestMeetingsTagPeer;
use CoreBundle\Model\RequestMeetingsTagsPeer;
use CoreBundle\Model\RequestMeetingTags;
use CoreBundle\Model\RequestMeetingTagsPeer;
use CoreBundle\Model\RequestMeetingTagsQuery;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\BrowserKit\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;

use CoreBundle\Model\EmpProfile;
use CoreBundle\Model\EmpProfilePeer;
use CoreBundle\Model\EmpContactPeer;
use CoreBundle\Model\EmpContact;
use CoreBundle\Model\EmpWorkPeer;
use CoreBundle\Model\ListContTypesPeer;
use CoreBundle\Model\ListDeptPeer;
use CoreBundle\Model\ListPosPeer;
use CoreBundle\Model\EmpTimePeer;
use CoreBundle\Model\ListIpPeer;
use CoreBundle\Model\ListLeaveType;
use CoreBundle\Model\ListLeaveTypePeer;
use CoreBundle\Model\ListPos;
use CoreBundle\Model\ListIp;
use CoreBundle\Model\ListDept;
use Symfony\Component\Validator\Constraints\Date;
use CoreBundle\Model\EmpAcc;
use CoreBundle\Model\EmpAccPeer;
use CoreBundle\Model\EmpAccQuery;

use CoreBundle\Model\EmpTimeQuery;
use CoreBundle\Model\EmpTime;

use CoreBundle\Model\EmpLeave;
use CoreBundle\Model\EmpLeavePeer;
use CoreBundle\Model\EmpLeaveQuery;
use CoreBundle\Form\Type\EmpLeaveType;

use CoreBundle\Utilities\Mailer;

use Swift_Mailer;
use Swift_SmtpTransport;
use Swift_Message;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Validator\Constraints\Email;

use Pagerfanta\Adapter\PropelAdapter;
use Pagerfanta\Pagerfanta;

class EventManagerController extends Controller
{
    public function ManageAction()
    {
        $user = $this->getUser();
        $page = 'Home';
        $id = $user->getId();

        $timename = DefaultController::timeInOut($id);
        $overtime = 0;
        $timedata = EmpTimePeer::getTime($id);
        $currenttimein = 0;
        $currenttimeout = 0;
        $timeflag = 0;

        //get last timed in
        for ($ctr = 0; $ctr < sizeof($timedata); $ctr++) {
            $checktimein = $timedata[$ctr]->getTimeIn();
            $checktimeout = $timedata[$ctr]->getTimeOut();
            if (!is_null($checktimein) && is_null($checktimeout)) {
                $currenttimein = $checktimein->format('h:i A');
            } else {
                $currenttimein = 0;
                $currenttimeout = $checktimeout->format('h:i A');
            }
        }
        $checkipdata = null;
        $datetoday = date('Y-m-d');
        //check if already timed in today
        if (!empty($timedata)) {
            $overtime = date('h:i A', strtotime('+9 hours', strtotime($currenttimein)));
            $datetoday = date('Y-m-d');
            $emp_time = EmpTimePeer::getTime($id);
            $currenttime = sizeof($emp_time) - 1;
            $timein_data = $emp_time[$currenttime]->getTimeIn();
            $timeout_data = $emp_time[$currenttime]->getTimeOut();
            $checkipdata = $emp_time[$currenttime]->getCheckIp();
        }

        $et = EmpTimePeer::getEmpLastTimein($id);
        if (!empty($et)) {
            $emptimedate = $et->getDate();
            $lasttimein = $et->getTimeIn()->format('M d, Y, h:i A');
            if ($emptimedate->format('Y-m-d') == $datetoday) {
                $timeflag = 1;
            }
            if (!empty($et->getTimeOut()))
                $isTimeOut = 'true';
        } else {
            $isTimeOut = 'true';
        }

        $systime = date('H:i A');
        $timetoday = date('h:i A');
        $afternoon = date('H:i A', strtotime('12 pm'));

        //counts number of pending requests
        $requestcount = EmpRequestQuery::create()
            ->filterByStatus('Pending')
            ->find()->count();

        $userip = InitController::getUserIP($this);
        $ip_add = ListIpPeer::getValidIP($userip);

//		var_dump($this->getRequest()->server->all());
//		exit;
        if (!is_null($ip_add)) {
            $matchedip = $ip_add->getAllowedIp();
        } else {
            $matchedip = '';
        }

        if ($userip == $matchedip) {
            $ip_checker = 1;
        } else {
            $ip_checker = 0;
        }

        $timedintoday = EmpTimePeer::getAllTimeToday($datetoday);
        $allusers = EmpProfilePeer::getAllProfile();
        $allacc = EmpAccPeer::getAllUser();
        $userbdaynames = array();
        foreach ($allusers as $u) {
            $bday = $u->getBday()->format('m-d');
//			echo $datetoday . "|" .$bday . '<br>';
            if ($bday == date('m-d')) {
                $userbdaynames[] = $u->getFname();
            }
        }
        return $this->render('AdminBundle:EventManager:Manage.html.twig', array(
            'userbdaynames' => $userbdaynames,
            'page' => $page,
            'user' => $user,
            'timename' => $timename,
            'id' => $id,
            'currenttimein' => $currenttimein,
            'currenttimeout' => $currenttimeout,
            'overtime' => $overtime,
            'timeflag' => $timeflag,
            'systime' => $systime,
            'afternoon' => $afternoon,
            'userip' => $userip,
            'matchedip' => $matchedip,
            'checkipdata' => $checkipdata,
            'checkip' => $ip_checker,
            'requestcount' => $requestcount,
            'isTimeoutAlready' => !empty($isTimeOut) ? $isTimeOut : null,
            'lasttimein' => !empty($lasttimein) ? $lasttimein : null,
            'timetoday' => $timetoday,
            'allacc' => $allacc,
            't' => $timedintoday,
        ));
    }

    public function addAction() {

    }
}
