<?php

namespace AdminBundle\Controller;

use CoreBundle\AuthenticationHandler\LoginHandler;
use CoreBundle\Model\CapabilitiesListPeer;
use CoreBundle\Model\EmpAcc;
use CoreBundle\Model\EmpAccPeer;
use CoreBundle\Model\EmpAccQuery;
use CoreBundle\Model\EmpCapabilities;
use CoreBundle\Model\EmpCapabilitiesPeer;
use CoreBundle\Model\EmpStatusTypePeer;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use CoreBundle\Model\EmpProfile;
use CoreBundle\Model\EmpProfilePeer;
use CoreBundle\Model\EmpProfileQuery;
use CoreBundle\Model\EmpContactPeer;
use CoreBundle\Model\EmpContact;
use CoreBundle\Model\ListContTypesPeer;
use CoreBundle\Model\ListDeptPeer;
use CoreBundle\Model\ListPosPeer;
use CoreBundle\Model\EmpTimePeer;
use CoreBundle\Model\ListIpPeer;
use CoreBundle\Model\ListPos;
use CoreBundle\Model\ListDept;
use CoreBundle\Model\EmpTime;
use CoreBundle\Model\EmpRequest;
use CoreBundle\Model\EmpRequestQuery;
use CoreBundle\Model\RequestMeetingsTag;
use CoreBundle\Model\RequestMeetingsTagPeer;
use CoreBundle\Model\RequestMeetingsTagsPeer;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\StreamedResponse;

class EmployeeController extends Controller
{
	protected $session;

	function __construct()
	{
		$this->session = new Session();
	}

	public function TimeInAction(Request $request)
	{
		date_default_timezone_set('Asia/Manila');

		//check session active
		$user = $this->getUser();

		if(empty($user))
		{
			// if session expire
			echo 'Session Expire';
			exit;
		}
		$matchedip 		= InitController::getUserIP($this);
		$datetimetoday 	= date('Y-m-d H:i:s');
		$datetoday 		= date('Y-m-d');
		$timeflag 		= 0;
		$emp 			= $this->getUser()->getId();
		$timedata 		= EmpTimePeer::getEmpLastTimein($emp);
		$ip_add 		= ListIpPeer::getValidIP($matchedip);

		//Compare last time in date with date today
		if(!empty($timedata))
		{
			$emptimedate = $timedata->getTimeIn()->format('Y-m-d');
			if($emptimedate == $datetoday)
			{
				$timeflag = 1;
                InitController::ResetSessionValue();
                InitController::loginSetTimeSession($this);
			}
		}
		//Time in
		if($timeflag == 0) {
			$empTimeSave = new EmpTime();
			$empTimeSave->setTimeIn($datetimetoday);
			$empTimeSave->setIpAdd($matchedip);
			$empTimeSave->setDate($datetoday);
			$empTimeSave->setEmpAccAccId($this->getUser()->getId());

			if(!empty($ip_add)) {
				$allowedip = $ip_add->getAllowedIp();

				if($allowedip == $matchedip) {
					$empTimeSave->setCheckIp(1);
				} else {
					$empTimeSave->setCheckIp(0);
				}
			} else {
				$empTimeSave->setCheckIp(0);
			}

             $message = "Oops. Something went wrong. Please try again.";
             $empTimeAlreadySaved = false;
             $this->session->set('timeout', 'false');
             $is_message = $request->request->get('is_message');
             $emailresp = '';
             $is_email = false;
             if(!is_null($is_message))
             {
                 $email = new EmailController();
                 $sendemail = $email->sendTimeInRequest($request, $this);
                 if (!$sendemail) {
                     $emailresp = 'No email sent';
                 } else {
                     $emailresp = 'Email Sent';
                     $requesttimein = new EmpRequest();
                     $requesttimein->setStatus(2);
                     $requesttimein->setRequest($request->request->get('message'));
                     $requesttimein->setEmpAccId($this->getUser()->getId());
                     $requesttimein->setDateStarted($datetoday);
                     $requesttimein->setDateEnded($datetoday);
                     $requesttimein->setListRequestTypeId(3);

                     if($empTimeSave->save()) {
                         $requesttimein->setEmpTimeId($empTimeSave->getId());

                         if($empTimeSave->isAlreadyInSave())
                             $empTimeAlreadySaved = true;

                         $requesttimein->save();
                         InitController::ResetSessionValue();
                         InitController::loginSetTimeSession($this);
                         $message = 'Time in Successful';
                     }
                 }
             }

             if(!$empTimeAlreadySaved) {
                 if($empTimeSave->save()) {
                     InitController::ResetSessionValue();
                     InitController::loginSetTimeSession($this);
                     $message = 'Time in Successful';
                 }
             }
		} else {
			$message = 'Already Time in today';
		}
		$response = array('message' => $message, 'emailresp' => $emailresp);
		echo json_encode($response);
    	exit;
    }

	public function TimeOutAction($passw)
	{
		date_default_timezone_set('Asia/Manila');

		$user = $this->getUser();
		if(empty($user)) {
			// if session expire
			echo 1;
			exit;
		}

		$pass = $user->getPassword();
		$datetimetoday 	= date('Y-m-d H:i:s');
		$emp 			= $this->getUser()->getId();
		$timedata 		= EmpTimePeer::getEmpLastTimein($emp);
		$timeinId 		= $timedata->getId();
		$inputpass 		= $passw;
		$error 			= false;

		if($pass == $inputpass) {
			//set time out
			$timeout = EmpTimePeer::retrieveByPK($timeinId);
			$timeout->setTimeOut($datetimetoday);

			$in = new \DateTime($timeout->getTimeIn()->format('Y-m-d H:i:s'));
			$out = new \DateTime($timeout->getTimeOut()->format('Y-m-d H:i:s'));
			$manhours = date_diff($out, $in);
			$h = $manhours->format('%h');
			$i = intval($manhours->format('%i'));
			$i = $i > 0 ? ($i/60) : 0;
			$totalHoursDec = number_format($h + $i, 2);

			//set total hours and overtime
			if(date('D') == 'Sat' || date('D')=='Sun') {
				$timeout->setOvertime($totalHoursDec);
			} else {
				$timeout->setManhours($totalHoursDec);
				$overtime = 0;

				if($totalHoursDec > 9) {
					$overtime = $totalHoursDec - 9;
				}

				$timeout->setOvertime($overtime);
			}

			$timeout->save();
            InitController::ResetSessionValue();
            InitController::loginSetTimeSession($this);
			$message = 'Time out successful';
		} else {
			$error = true;
			$message = 'Wrong Password';
			echo $error;
			exit;
		}

		$response = array('message' => $message, 'error' => $error);
		echo json_encode($response);
		exit;
	}

	public function autoTimeOutAction()
	{
		$user = $this->getUser();
		$id = $user->getId();
		$emplasttime = EmpTimePeer::getEmpLastTimein($id);
		$emplasttime->setTimeOut(new \DateTime('00:00:00'));
		$emplasttime->save();
		echo 1;
		exit;
	}

	public function ManageTimeOnNewDayAction($type)
	{
		date_default_timezone_set('Asia/Manila');
		$datetimetoday 	= date('Y-m-d H:i:s');
		$session = new Session();
		$userId = $this->getUser()->getId();

		if($type == 'working') {
			$session->set('isSameDay', '');
		} else {
			$session->set('isSameDay', '');
			$session->set('timeout', 'true');
			$user = $this->getUser();
			$id = $user->getId();
			$emplasttime = EmpTimePeer::getEmpLastTimein($id);
			$emplasttime->setTimeOut($datetimetoday);
			$emplasttime->save();
		}

		echo json_encode(array('success' => 1, 'user' => $userId, 'type' => $type));
		exit;
	}

    public function timeLogsAction()
    {
        $user = $this->getUser();
        $name = $user->getUsername();
        $role = $user->getRole();
        $id = $user->getId();
        $timename = AdminController::timeInOut($id);

        if((strcasecmp($role, 'employee') == 0)) {
            return $this->redirect($this->generateUrl('admin_homepage'));
        } else {
            $getEmployee = EmpProfilePeer::getAllProfile();
            $getPos = ListPosPeer::getAllPos();
            $getDept = ListDeptPeer::getAllDept();
            $timedata = EmpTimePeer::getTime($id);
            $currenttimein = 0;
            $currenttimeout = 0;
            $timeflag = 0;

            //get last timed in
            for ($ctr = 0; $ctr < sizeof($timedata); $ctr++) {
                $checktimein = $timedata[$ctr]->getTimeIn();
                $checktimeout = $timedata[$ctr]->getTimeOut();

                if(!is_null($checktimein) && is_null($checktimeout)) {
                    $currenttimein = $checktimein->format('h:i A');
                } else {
                    $currenttimein = 0;
                    $currenttimeout = $checktimeout->format('h:i A');
                }
            }

            $checkipdata = null;
            //check if already timed in today
            if(!empty($timedata)) {
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

            $getTime = EmpTimePeer::getAllTime();
            $getAllProfile = EmpProfilePeer::getAllProfile();
            $et = EmpTimePeer::getEmpLastTimein($id);

            if(!empty($et)) {
                $lasttimein	= $et->getTimeIn()->format('M d, Y, h:i A');
                $emptimedate = $et->getDate();
                if($emptimedate->format('Y-m-d') == $datetoday) {
                    $timeflag = 1;
                }

                if(! empty($et->getTimeOut()))
                    $isTimeOut = 'true';
            }

            $requestcount = EmpRequestQuery::_getTotalByStatusRequest(2);

            return $this->render('AdminBundle:Employee:managetime.html.twig', array(
                'name' => $name,
                'user' => $user,
                'timename' => $timename,
                'getEmployee' => $getEmployee,
                'getPos' => $getPos,
                'getDept' => $getDept,
                'userip' => $userip,
                'matchedip' => is_null($ip_add) ? "" : $ip_add->getAllowedIp(),
                'checkipdata' => $checkipdata,
                'checkip' => $is_ip,
                'currenttimein' => $currenttimein,
                'timeflag' => $timeflag,
                'systime' => $systime,
                'afternoon' => $afternoon,
                'getTime' => $getTime,
                'getAllProfile' => $getAllProfile,
                'requestcount' => $requestcount,
                'isTimeoutAlready' => !empty($isTimeOut) ? $isTimeOut : null,
                'lasttimein' => !empty($lasttimein) ? $lasttimein : null,
                'timetoday' => $timetoday,
            ));
        }
    }

    public function exportAction(){
//		$results = EmpTimePeer::getEmployeeTime();
//		var_dump($results);
//		exit;
//		$t = EmpTimePeer::getAllTime();

//		foreach($t as $ttt){
//
//			$tt = $ttt->getEmpAcc()->getEmpProfiles()->get(0)->getFname();
//
//			var_dump($tt);
//		}
//
//		exit;
        $response = new StreamedResponse();
        $response->setCallback(function() {
            $handle = fopen('php://output', 'w+');

            // Add the header of the CSV file
            fputcsv($handle, array('Employee ID', 'Name', 'Time in', 'Time out', 'Date', 'Work in Office', 'Total hours', 'Overtime'));
            // Query data from database
//			$results = $this->connection->query("Replace this with your query");
//			$results = EmpTimePeer::getEmployeeTime();
            // Add the data queried from database
            $results = EmpTimePeer::getAllTime();

            for($ctr = 0; $ctr < sizeof($results); $ctr++){
                $timeindata = $results[$ctr]->getTimeIn()->format('h:i A');
                $timeoutdata = $results[$ctr]->getTimeOut()->format('h:i A');
                $empaccid = $results[$ctr]->getEmpAccAccId();
                $timein = $results[$ctr]->getTimeIn();
                $timeout = $results[$ctr]->getTimeOut();
                $date = $results[$ctr]->getDate()->format('M d, Y');
                $checkip = $results[$ctr]->getCheckIp()?'No':'Yes';
                $profile = EmpProfilePeer::getInformation($empaccid);
                $fname = $profile->getFname();
                $lname = $profile->getLname();
                $empnum = $profile->getEmployeeNumber();
                $in = new \DateTime($timein->format('Y-m-d H:i:s'));
                $out = new \DateTime($timeout->format('Y-m-d H:i:s'));
                $manhours = date_diff($out, $in);
                $answer = $manhours->format('%h') . 'hours ' . $manhours->format('%i') . 'minutes ';
                $h = $manhours->format('%h:%i');
                $i = $manhours->format('%i');

                $H = intval($h);
                $ot = $H - 9;
                if($ot >= 0)
                {
                    $over = $ot . 'hours ' . $i . ' minutes ';
                }else{
                    $over = 0;
                }


                fputcsv($handle, // The file pointer
                    array($empnum, $fname . " " . $lname, $timeindata, $timeoutdata, $date, $checkip, $answer, $over)
                );
            }
//exit;
//
            fclose($handle);
        });
////
        $response->setStatusCode(200);
        $response->headers->set('Content-Type', 'text/csv; charset=utf-8');
        $response->headers->set('Content-Disposition', 'attachment; filename="export.csv"');
        return $response;
    }

    public function employeeProfileAction($id){
        $user = $this->getUser();
        $name = $user->getUsername();
        $role = $user->getRole();
        $adminid = $user->getId();
        $timename = AdminController::timeInOut($adminid);
        if((strcasecmp($role, 'employee') == 0)){
            return $this->redirect($this->generateUrl('admin_homepage'));
            exit;
        }

        $user2 = EmpAccPeer::retrieveByPK($id);

        //employee profile information
        $data = EmpProfilePeer::getInformation($id);
        $data2 = EmpProfilePeer::getInformation($id);

        $fname = $data->getFname();
        $lname = $data->getLname();
        $mname = $data->getMname();
        $bday = $data->getBday();
//		$bday = date_format($bday, 'd/m/y');
        $address = $data->getAddress();
        $img = $data->getImgPath();
        $datejoined = $data->getDateJoined();
//		$datejoined = date_format($datejoined, 'd/m/y');
        $profileid = $data->getId();
        $deptid = $data->getListDeptDeptId();

        //employee contact information

        $datacontact = EmpContactPeer::getContact($profileid);
        $contact = '';
        $conEmail = '';
        $conMobile = '';
        $conTele = '';
        $conEmailId = '';
        $conMobileId = '';
        $conTeleId = '';
        $contacttype = '';

        if(!is_null($datacontact))
        {
            for ($ct = 0; $ct < sizeof($datacontact); $ct++)
            {
                // $contactArr[$ct] = $datacontact[$ct]->getContact();
                $contacttype =  ListContTypesPeer::getContactType($datacontact[$ct]->getListContTypesId())->getContactType();
                $contactvalue =  $datacontact[$ct]->getContact();
                $contactid = $datacontact[$ct]->getId();
                if(strcasecmp($contacttype, 'email') == 0)
                {
                    $conEmail .= $contactvalue;
                    $conEmailId .= $contactid;
                }
                elseif(strcasecmp($contacttype, 'mobile') == 0)
                {
                    $conMobile .= $contactvalue;
                    $conMobileId .= $contactid;
                }
                elseif(strcasecmp($contacttype, 'telephone') == 0)
                {
                    $conTele .= $contactvalue;
                    $conTeleId .= $contactid;
                }
                $contact .= '<p>Contact:'.$contactvalue.'</p><p>Concact Type:'.$contacttype.'</p>';
            }
        }
        else
        {
            $contact = null;
            $contype = null;
            $contact2 = null;
            $contype2 = null;
        }

        //employee work information
        $datawork = EmpProfilePeer::retrieveByPk($profileid);

        if(!is_null($datawork))
        {
            $workdeptid = $datawork->getListDeptDeptId();
            $workposid = $datawork->getListPosPosId();
            $empnumber = $datawork->getEmployeeNumber();

            $datadept = ListDeptPeer::getDept($workdeptid);
            $datapos = ListPosPeer::getPos($workposid);

            $deptnames = $datadept->getDeptNames();
            $posStatus = $datapos->getPosNames();
        }
        else
        {
            $workdeptid = null;
            $workposid = null;
            $empnumber = null;
            $deptnames = null;
            $posStatus = null;
        }

        $getDept = ListDeptPeer::getAllDept();

        //check pending count
        $requestcount = EmpRequestQuery::_getTotalByStatusRequest(2);

        //Check late
        $late = 0;
        $getEmpTime = EmpTimePeer::getTime($id);
        for ($ct = 0; $ct < sizeof($getEmpTime); $ct++)
        {
            $checklate = $getEmpTime[$ct]->getTimeIn();
            if($checklate->format('H:i:s') > 12)
            {
                $late++;
            }
        }


        $timedata = EmpTimePeer::getTime($adminid);
        $timeflag = 0;
        $currenttimein = 0;
        $currenttimeout = 0;
        for ($ctr = 0; $ctr < sizeof($timedata); $ctr++)
        {
            $checktimein = $timedata[$ctr]->getTimeIn();
            $checktimeout = $timedata[$ctr]->getTimeOut();
//			$date_due = date('m/d/Y h:i:s a', time());
//				$diff_due = $checktimein - $date_due;
//				$hours_diff = $diff_due / (60*60);
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
//        //add duration
//        $tmn_in = EmpTimePeer::getEmpLastTimein($id);
//        $date_now = date("h:i:sa");
//        $total_due = date_diff($tmn_in,$date_now);
//        $total_dueFormat = strtotime($total_due);

        $timeoutdata = '';
        $checkipdata = null;
        if(!empty($timedata))
        {
            $datetoday = date('Y-m-d');
            $emp_time = EmpTimePeer::getTime($adminid);
            $currenttime = sizeof($emp_time) - 1;
            $timein_data = $emp_time[$currenttime]->getTimeIn();
            $timeout_data = $emp_time[$currenttime]->getTimeOut();
            $checkipdata = $emp_time[$currenttime]->getCheckIp();


        }
        $firstchar = $fname[0];
        $systime = date('H:i A');
        $timetoday = date('h:i A');
        $afternoon = date('H:i A', strtotime('12 pm'));

        if(!empty($timedata) && !empty($timeout_data))
        {
            for ($ctr = 0; $ctr < sizeof($timedata); $ctr++)
            {
                $timeindata = $timedata[$ctr]->getTimeIn();
                $timeoutdata = $timedata[$ctr]->getTimeOut();
                $in = new \DateTime($timeindata->format('Y-m-d H:i:s'));
                $out = new \DateTime($timeoutdata->format('Y-m-d H:i:s'));
                $manhours = date_diff($out, $in);

                $ins = $in->format('H:i:s');
                $outs = $out->format('H:i:s');
                $answer = $manhours->format('%h') . 'hours ' . $manhours->format('%i') . 'minutes ' . $manhours->format('%s') .' secs<br>';

                $h = $manhours->format('%h');
                $i = $manhours->format('%i');
                $s = $manhours->format('%s');

                $H = intval($h);
                $ot = $H - 9;
                if($ot >= 0){
                    $over = $ot . 'hours ' . $i . ' minutes ' . $s . ' secs';
                }else{
                    $over = 0;
                }

            }
        }
        $et = EmpTimePeer::getEmpLastTimein($adminid);
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

        $getAllTimeData = EmpTimePeer::getTimeDescendingOrder($id);

        return $this->render('AdminBundle:Employee:empprofile.html.twig', array(
            'name' => $name,
            'fname' => $fname,
            'lname' => $lname,
            'mname' => $mname,
            'bday' => $bday,
            'address' => $address,
            'img' => $img,
            'datejoined' => $datejoined,
            'deptnames' => $deptnames,
            'posStatus' => $posStatus,
            'user' => $user,
            'contactArr' => $contact,
            'conEmail' => $conEmail,
            'conMobile' => $conMobile,
            'conTele' => $conTele,
            'timename' => $timename,
            'role' => $role,
            'user2' => $user2,
            'profileId' => $profileid,
            'contacttype' => $contacttype,
            'conEmailId' => $conEmailId,
            'conMobileId' => $conMobileId,
            'conTeleId' => $conTeleId,
            'getDept' => $getDept,
            'empnumber' => $empnumber,
            'deptid' => $deptid,
            'late' =>$late,
            'timeflag' => $timeflag,
            'currenttimein' => $currenttimein,
            'currenttimeout' => $currenttimeout,
            'firstchar' => $firstchar,
            'matchedip' => is_null($ip_add) ? "" : $ip_add->getAllowedIp(),
            'userip' => $userip,
            'checkipdata' => $checkipdata,
            'propelrr' => $data2,
            'checkip' => $is_ip,
            'systime' => $systime,
            'afternoon' => $afternoon,
            'requestcount' => $requestcount,
            'isTimeoutAlready' => !empty($isTimeOut) ? $isTimeOut : null,
            'lasttimein' => !empty($lasttimein) ? $lasttimein : null,
            'timetoday' => $timetoday,
            'getAllTime' => $getAllTimeData,
        ));
    }

    public function profileAction()
    {
        $page = 'Profile';
        //employee account information
        $user = $this->getUser();
        $name = $user->getUsername();
        $role = $user->getRole();
        $pw = $user->getPassword();
        $id = $user->getId();
        $user2 = EmpAccPeer::retrieveByPK($id);

        //employee profile information
        $data = EmpProfilePeer::getInformation($id);
        $data2 = EmpProfilePeer::getInformation($id);

        $fname = $data->getFname();
        $img = $data->getImgPath();
        $datejoined = $data->getDateJoined();
        $profileid = $data->getId();
        $deptid = $data->getListDeptDeptId();

        //employee contact information

        $datacontact = EmpContactPeer::getContact($profileid);
        $contact = '';
        $conEmail = '';
        $conMobile = '';
        $conTele = '';
        $conEmailId = '';
        $conMobileId = '';
        $conTeleId = '';
        $contacttype = '';

        if(!is_null($datacontact))
        {
            for ($ct = 0; $ct < sizeof($datacontact); $ct++)
            {
                // $contactArr[$ct] = $datacontact[$ct]->getContact();
                $contacttype =  ListContTypesPeer::getContactType($datacontact[$ct]->getListContTypesId())->getContactType();
                $contactvalue =  $datacontact[$ct]->getContact();
                $contactid = $datacontact[$ct]->getId();
                if(strcasecmp($contacttype, 'email') == 0)
                {
                    $conEmail .= $contactvalue;
                    $conEmailId .= $contactid;
                }
                elseif(strcasecmp($contacttype, 'mobile') == 0)
                {
                    $conMobile .= $contactvalue;
                    $conMobileId .= $contactid;
                }
                elseif(strcasecmp($contacttype, 'telephone') == 0)
                {
                    $conTele .= $contactvalue;
                    $conTeleId .= $contactid;
                }

                $contact .= '<p>Contact:'.$contactvalue.'</p><p>Concact Type:'.$contacttype.'</p>';
            }
        }
        else
        {
            $contact = null;
            $contype = null;

            $contact2 = null;
            $contype2 = null;
        }

        //employee work information
        $datawork = EmpProfilePeer::retrieveByPk($profileid);

        if(!is_null($datawork))
        {
            $workdeptid = $datawork->getListDeptDeptId();
            $workposid = $datawork->getListPosPosId();
            $empnumber = $datawork->getEmployeeNumber();

            $datadept = ListDeptPeer::getDept($workdeptid);
            $datapos = ListPosPeer::getPos($workposid);

            $deptnames = $datadept->getDeptNames();
            $posStatus = $datapos->getPosNames();
        }
        else
        {
            $workdeptid = null;
            $workposid = null;
            $empnumber = null;
            $deptnames = null;
            $posStatus = null;
        }
        $admincontroller = new AdminController();
        $timename = $admincontroller->timeInOut($id);
        $getDept = ListDeptPeer::getAllDept();

        //check pending count
        $requestcount = EmpRequestQuery::_getTotalByStatusRequest(2);

        //Check late
        $late = 0;
        $getEmpTime = EmpTimePeer::getTime($id);
        for ($ct = 0; $ct < sizeof($getEmpTime); $ct++)
        {
            $checklate = $getEmpTime[$ct]->getTimeIn();
            if($checklate->format('H:i:s') > 12)
            {
                $late++;
            }
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
        $firstchar = $fname[0];
        $systime = date('h:i A');
        $timetoday = date('h:i A');
        $afternoon = date('h:i A', strtotime('12 pm'));

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
        // echo'<pre>';var_dump($manhours);
        $userip = InitController::getUserIP($this);
        $ip_add = ListIpPeer::getValidIP($userip);
        $is_ip  = InitController::checkIP($userip);

        //count duration



        $getAllTimeData = EmpTimePeer::getTimeDescendingOrder($id);


        // add duration
        return $this->render('AdminBundle:Employee:profile.html.twig', array(
            'page' => $page,
            'img' => $img,
            'datejoined' => $datejoined,
            'user' => $user,
            'contactArr' => $contact,
            'conEmail' => $conEmail,
            'conMobile' => $conMobile,
            'conTele' => $conTele,
            'timename' => $timename,
            'user2' => $user2,
            'profileId' => $profileid,
            'contacttype' => $contacttype,
            'conEmailId' => $conEmailId,
            'conMobileId' => $conMobileId,
            'conTeleId' => $conTeleId,
            'getDept' => $getDept,
            'empnumber' => $empnumber,
            'deptid' => $deptid,
            'late' =>$late,
            'timeflag' => $timeflag,
            'currenttimein' => $currenttimein,
            'currenttimeout' => $currenttimeout,
            'firstchar' => $firstchar,
            'matchedip' => is_null($ip_add) ? "" : $ip_add->getAllowedIp(),
            'userip' => $userip,
            'checkipdata' => $checkipdata,
            'propelrr' => $data2,
            'checkip' => $is_ip,
            'systime' => $systime,
            'afternoon' => $afternoon,
            'requestcount' => $requestcount,
            'isTimeoutAlready' => !empty($isTimeOut) ? $isTimeOut : null,
            'lasttimein' => !empty($lasttimein) ? $lasttimein : null,
            'getAllTime' => $getAllTimeData,
            'timetoday' => $timetoday,
        ));

    }

    public function manageAction(Request $request)
    {
        $item_per_page = 10;

        $user = $this->getUser();
        $name = $user->getUsername();
        $page = 'Manage Employees';
        $role = $user->getRole();
        $id = $user->getId();

        $capabilities = EmpCapabilitiesPeer::getEmpCapabilities($id);

        $adminController = new AdminController();

        $timename = $adminController->timeInOut($id);

        $getEmployee     = EmpProfilePeer::getAllProfile();
        $getPos          = ListPosPeer::getAllPos();
        $getDept         = ListDeptPeer::getAllDept();
        $timedata        = EmpTimePeer::getTime($id);
        $currenttimein   = 0;
        $currenttimeout  = 0;
        $timeflag        = 0;

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
            // echo $checkipdata;
        }
        $systime = date('H:i A');
        $timetoday = date('h:i A');
        $afternoon = date('H:i A', strtotime('12 pm'));

        $userip = InitController::getUserIP($this);
        $ip_add = ListIpPeer::getValidIP($userip);
        $is_ip  = InitController::checkIP($userip);

        $getTime = EmpTimePeer::getAllTime();

        $countAllEmpData = EmpProfileQuery::create()
            ->count();

        $total_page = ceil($countAllEmpData/$item_per_page); //divide

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

        $requestcount = EmpRequestQuery::_getTotalByStatusRequest(2);

        $AllUsers = EmpAccPeer::getAllUser();
        $AllDepartments = ListDeptPeer::getAllDept();
        $AllPositions = ListPosPeer::getAllPos();
        $AllEmpStatus = EmpStatusTypePeer::getAllEmpStatus();
        $AllCapabilities = CapabilitiesListPeer::getAllCapabilities();
        $getContact = EmpContactPeer::getAllContact();

        return $this->render('AdminBundle:Employee:manage.html.twig', array(
            'name' => $name,
            'page' => $page,
            'user' => $user,
            'timename' => $timename,
            'getEmployee' => $getEmployee,
            'getPos' => $getPos,
            'getDept' => $getDept,
            'userip' => $userip,
            'matchedip' => is_null($ip_add) ? "" : $ip_add->getAllowedIp(),
            'checkipdata' => $checkipdata,
            'checkip' => $is_ip,
            'currenttimein' => $currenttimein,
            'timeflag' => $timeflag,
            'systime' => $systime,
            'afternoon' => $afternoon,
            'getTime' => $getTime,
            'getAllProfile' => $getAllProfile,
            'requestcount' => $requestcount,
            'isTimeoutAlready' => !empty($isTimeOut) ? $isTimeOut : null,
            'lasttimein' => !empty($lasttimein) ? $lasttimein : null,
            'timetoday' => $timetoday,
            'allusers' => $AllUsers,
            'alldept' => $AllDepartments,
            'allpos' => $AllPositions,
            'allstatus' => $AllEmpStatus,
            'getContact' => $getContact,
            'capabilities' => $capabilities,
            'allcapabilities' => $AllCapabilities
        ));
    }

    public function addEmployeeAction(Request $request)
    {
        date_default_timezone_set('Asia/Manila');
        $datetimetoday = date('Y-m-d H:i:s');
        $user = $this->getUser();
        $id = $user->getId();
        $role = $user->getRole();

        $newUserEmail = $request->request->get('emailinput');
        $newUser = EmpAccPeer::getUserInfo($newUserEmail);
        if($newUser != null) {
            $newUserStatus = $newUser->getStatus();
            if($newUserStatus == -1) {
                // send email
                $email = new EmailController();
                $sendemail = $email->addEmployeeEmail($request, $this);
                if (!$sendemail) {
                    echo json_encode(array('error' => 'Error Occurred. Please try again.'));
                    exit;
                }
                else {
                    // create user
                    $newUser->setUsername($request->request->get('usernameinput'));
                    $newUser->setPassword($request->request->get('passwordinput'));
                    $newUser->setTimestamp($datetimetoday);
                    $newUser->setIpAdd(InitController::getUserIP($this));
                    $newUser->setStatus(1);
                    $newUser->setEmail($request->request->get('emailinput'));
                    $newUser->setRole("employee");
                    $newUser->setCreatedBy($id);
                    $newUser->setLastUpdatedBy($id);
                    $newUser->save();
                    $empid = $newUser->getId();

                    if($empid != '') {
                        $empprofile = EmpProfilePeer::getInformation($empid);
                        $empprofile->setFname($request->request->get('fnameinput'));
                        $empprofile->setMname($request->request->get('mnameinput'));
                        $empprofile->setLname($request->request->get('lnameinput'));
                        $empprofile->setBday($request->request->get('bdayinput'));
                        $empprofile->setAddress($request->request->get('addressinput'));
                        $empprofile->setGender($request->request->get('genderinput'));
                        $empprofile->setDateJoined($datetimetoday);
                        $empprofile->setEmployeeNumber($request->request->get('empnumber'));
                        $empprofile->setListDeptDeptId($request->request->get('departmentinput'));
                        $empprofile->setListPosPosId($request->request->get('positioninput'));
                        $empprofile->setStatus($request->request->get('statusinput'));
                        $empprofile->setSss($request->request->get('sssinput'));
                        $empprofile->setBir($request->request->get('birinput'));
                        $empprofile->setPhilhealth($request->request->get('philhealthinput'));
                        $empprofile->save();
                        $profileid = $empprofile->getId();

                        $cellcontact = EmpContactPeer::getContactObject($profileid, 2);
                        $cellcontact->setContact($request->request->get('celnuminput'));
                        $cellcontact->save();

                        $telcontact = EmpContactPeer::getContactObject($profileid, 3);
                        $telcontact->setContact($request->request->get('telnuminput'));
                        $telcontact->save();

                        if ($role == 'ADMIN') {
                            $capabilities = $request->request->get('capabilities');
                            $capIds = array();
                            foreach ($capabilities as $cap) {
                                $empcap = new EmpCapabilities();
                                $empcap->setEmpId($empid);
                                $empcap->setCapId($cap);
                                $empcap->save();
                                array_push($capIds, $empcap->getId());
                            }
                        }
                    }

                    echo json_encode(array('result' => 'User has been successfully created'));
                    exit;
                }
            } else {
                // return user exists
                echo json_encode(array('error' => 'User already exists'));
                exit;
            }
        } else {
            // create user
            $empacc = new EmpAcc();
            $empacc->setUsername($request->request->get('usernameinput'));
            $empacc->setPassword($request->request->get('passwordinput'));
            $empacc->setTimestamp($datetimetoday);
            $empacc->setIpAdd(InitController::getUserIP($this));
            $empacc->setStatus(-1);
            $empacc->setEmail($request->request->get('emailinput'));
            $empacc->setRole("employee");
            $empacc->setCreatedBy($id);
            $empacc->setLastUpdatedBy($id);
            $empacc->save();
            $empid = $empacc->getId();

            if($empid != '') {
                $empprofile = new EmpProfile();
                $empprofile->setEmpAccAccId($empid);
                $empprofile->setFname($request->request->get('fnameinput'));
                $empprofile->setMname($request->request->get('mnameinput'));
                $empprofile->setLname($request->request->get('lnameinput'));
                $empprofile->setBday($request->request->get('bdayinput'));
                $empprofile->setAddress($request->request->get('addressinput'));
                $empprofile->setGender($request->request->get('genderinput'));
                $empprofile->setDateJoined($datetimetoday);
                $empprofile->setEmployeeNumber($request->request->get('empnumber'));
                $empprofile->setListDeptDeptId($request->request->get('departmentinput'));
                $empprofile->setListPosPosId($request->request->get('positioninput'));
                $empprofile->setStatus($request->request->get('statusinput'));
                $empprofile->setSss($request->request->get('sssinput'));
                $empprofile->setBir($request->request->get('birinput'));
                $empprofile->setPhilhealth($request->request->get('philhealthinput'));
                $empprofile->save();
                $profileid = $empprofile->getId();

                $cellcontact = new EmpContact();
                $cellcontact->setEmpProfileId($profileid);
                $cellcontact->setListContTypesId(2);
                $cellcontact->setContact($request->request->get('celnuminput'));
                $cellcontact->save();

                $telcontact = new EmpContact();
                $telcontact->setEmpProfileId($profileid);
                $telcontact->setListContTypesId(3);
                $telcontact->setContact($request->request->get('telnuminput'));
                $telcontact->save();
            }

            $email = new EmailController();
            $sendemail = $email->addEmployeeEmail($request, $this);
            if (!$sendemail) {
                echo json_encode(array('error' => 'Error: Email cannot be sent.'));
                exit;
            } else {
                $empacc->setStatus(1);
                $empacc->save();

                if($role == 'ADMIN') {
                    $capabilities = $request->request->get('capabilities');
                    if($capabilities != '') {
                        $capIds = array();
                        foreach ($capabilities as $cap) {
                            $empcap = new EmpCapabilities();
                            $empcap->setEmpId($empid);
                            $empcap->setCapId($cap);
                            $empcap->save();
                            array_push($capIds, $empcap->getId());
                        }
                    }
                }
                echo json_encode(array('result' => 'User has been successfully created'));
                exit;
            }
        }
    }

    public function deleteEmployee($empAccid, $empProfileId, $capIds) {
        $employee = EmpProfileQuery::create()->findPk($empProfileId);
        $employee->delete();
        $employee = EmpAccQuery::create()->findPk($empAccid);
        $employee->delete();

        foreach ($capIds as $capId) {
            $capability = EmpRequestQuery::create()->findPk($capId);
            if(!empty($capability)) {
                $capability->delete();
            }
        }
    }

    public function addPositionAction(Request $req)
    {
        $inputName = $req->request->get('posname');
        $addpos = new ListPos();
        $addpos->setPosNames($inputName);
        $addpos->save();
        $saved = $addpos->getId();

        if($saved == '') {
            echo json_encode(array('error' => 'Position Not Successfully Added'));
            exit;
        } else {
            echo json_encode(array('result' => 'Position Successfully Added', 'id' => $saved));
            exit;
        }
    }

    public function addDepartmentAction(Request $req)
    {
        $inputName = $req->request->get('deptname');
        $addDept = new ListDept();
        $addDept->setDeptNames($inputName);
        $addDept->save();
        $saved = $addDept->getId();

        if($saved == '') {
            echo json_encode(array('error' => 'Department not saved'));
            exit;
        } else {
            echo json_encode(array('result' => 'Department Successfully Saved', 'id' => $saved));
            exit;
        }
    }

    public function empDeleteAction($id)
    {
        $user = $this->getUser();
        $role = $user->getRole();

        if((strcasecmp($role, 'employee') == 0))
        {
            return $this->redirect($this->generateUrl('admin_homepage'));
        }else
        {
            $empinfo = EmpProfilePeer::retrieveByPk($id);
            $empinfo->setProfileStatus(1);
            $empinfo->save();
            $response = array('Successfully Deleted');
            echo json_encode($response);
            exit;
        }
    }

    public function profileUpdateAction(Request $request)
    {
        $user = $this->getUser();
        $empid = $user->getId();
        $telId = $request->request->get('telId');
        $mobileId = $request->request->get('mobileId');

        $updateprofile = EmpProfilePeer::getInformation($empid);
        $updateprofile->setAddress($request->request->get('address'));
        $updateprofile->save();

        $profileId = $updateprofile->getId();

        $updatetel = EmpContactPeer::retrieveByPk($telId);
        if(empty($updatetel))
        {
            $newtel = new EmpContact();
            $newtel->setContact($request->request->get('telephone'));
            $newtel->setEmpProfileId($profileId);
            $newtel->setListContTypesId(3);
            $newtel->save();

        }
        else
        {
            $updatetel->setContact($request->request->get('telephone'));
            $updatetel->save();
        }
        $updatecell = EmpContactPeer::retrieveByPk($mobileId);
        if(empty($updatecell))
        {
            $newcel = new EmpContact();
            $newcel->setContact($request->request->get('cellphone'));
            $newcel->setEmpProfileId($profileId);
            $newcel->setListContTypesId(2);
            $newcel->save();
        }
        else
        {
            $updatecell->setContact($request->request->get('cellphone'));
            $updatecell->save();
        }

        $response = array('result' => 'Update Successful');
        echo json_encode($response);
        exit;
    }

    public function changePasswordAction(Request $request){
        $user = $this->getUser();
        $userid = $user->getId();
        $getinfo = EmpAccPeer::retrieveByPK($userid);
        if(!is_null($getinfo)){
            $oldpass = $getinfo->getPassword();
            $inputpass = $request->request->get('oldpass');
            $newpass = $request->request->get('newpass');
            if($oldpass == $inputpass){
                $getinfo->setPassword($newpass);
                $getinfo->save();
                $response = 'Password changed';
                $resp = array('result' => $response);
            }else{
                $response = 'Wrong Password';
                $resp = array('error' => $response);
            }
        } else {
            $response = 'Error. User not found';
            $resp = array('error' => $response);
        }

        echo json_encode($resp);
        exit;
        //   	echo json_encode($response);
        //   	$referer = $request->headers->get('referer');
        // return new RedirectResponse($referer);
    }

    public function checkTimeInAction()
    {
        $user = $this->getUser();
        $id = $user->getId();
        $timedata = EmpTimePeer::getEmpLastTimein($id);
        //exit if found no record
        if(is_null($timedata))
        {
            echo 0;
            exit;
        }
        $timeout = $timedata->getTimeOut();
        $timeindate = $timedata->getDate()->format('Y-m-d');
        $datetoday = date('Y-m-d');
        if($timeindate == $datetoday && empty($timeout))
        {
            echo 1;
        }
        {
            echo 0;
        }
        exit;
    }
}
