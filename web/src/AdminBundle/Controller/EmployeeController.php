<?php

namespace AdminBundle\Controller;

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

use CoreBundle\Model\EmpAcc;
use CoreBundle\Model\EmpAccPeer;
use CoreBundle\Model\EmpAccQuery;

use CoreBundle\Model\EmpTimeQuery;
use CoreBundle\Model\EmpTime;

use CoreBundle\Model\EmpLeave;
use CoreBundle\Model\EmpLeavePeer;
use CoreBundle\Model\EmpLeaveQuery;
use CoreBundle\Form\Type\EmpLeaveType;
use CoreBundle\Model\EmpRequest;
use CoreBundle\Model\EmpRequestPeer;

use CoreBundle\Utilities\Mailer;

use Swift_Mailer;
use Swift_SmtpTransport;
use Swift_Message;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\StreamedResponse;


class EmployeeController extends Controller{

	protected $session;

	function __construct()
	{
		$this->session = new Session();
	}

	public function TimeInAction(Request $request){

		date_default_timezone_set('Asia/Manila');
		
		//check session active
		$user = $this->getUser();
		if(empty($user)){
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
		if(!empty($timedata)){
			$emptimedate = $timedata->getDate();
			if($emptimedate == $datetoday){
				$timeflag = 1;
			}
		}

		//Time in
		if($timeflag == 0){
			$empTimeSave = new EmpTime();
			$empTimeSave->setTimeIn($datetimetoday);
			$empTimeSave->setIpAdd($matchedip);
			$empTimeSave->setDate($datetoday);
			$empTimeSave->setEmpAccAccId($this->getUser()->getId());
			if(!empty($ip_add)){
				$allowedip = $ip_add->getAllowedIp();
				if($allowedip == $matchedip){
					$empTimeSave->setCheckIp(1);
				}else{
					$empTimeSave->setCheckIp(0);
				}
			}else{
				$empTimeSave->setCheckIp(0);
			}

			if($empTimeSave->save()){

				$this->session->set('timeout', 'false');

				$is_message = $request->request->get('is_message');
				$emailresp = '';
				if(!is_null($is_message)) {
					$email = new EmailController();
					$sendemail = $email->sendTimeInRequest($request, $this);
					if (!$sendemail) {
						$emailresp = 'No email sent';
					} else {
						$emailresp = 'Email Sent';

						$requesttimein = new EmpRequest();
						$requesttimein->setStatus('Pending');
						$requesttimein->setRequest($request->request->get('message'));
						$requesttimein->setEmpAccId($this->getUser()->getId());
						$requesttimein->setDateStarted($datetoday);
						$requesttimein->setDateEnded($datetoday);
						$requesttimein->setListRequestTypeId(3);
						$requesttimein->setEmpTimeId($empTimeSave->getId());
						$requesttimein->save();

					}
				}
			}
			$message = 'Time in Successful';
		}else{
			$message = 'Already Time in today';
		}

		$response = array('message' => $message, 'emailresp' => $emailresp);
		echo json_encode($response);
    	exit;
    }


	public function TimeOutAction($passw){
		date_default_timezone_set('Asia/Manila');

		$user = $this->getUser();
		if(empty($user)){
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

		if($pass == $inputpass){
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
			if(date('D') == 'Sat' || date('D')=='Sun'){
				$timeout->setOvertime($totalHoursDec);
			}else{
				$timeout->setManhours($totalHoursDec);
				$overtime = 0;
				if($totalHoursDec > 9) {
					$overtime = $totalHoursDec - 9;
				}
				$timeout->setOvertime($overtime);
			}
			$timeout->save();
			$message = 'Time out successful';
		}else{
			$error = true;
			$message = 'Wrong Password';
			echo $error;
			exit;
		}
		$response = array('message' => $message, 'error' => $error);
		echo json_encode($response);
		exit;
	}

	public function autoTimeOutAction(){
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
	
//end
}
