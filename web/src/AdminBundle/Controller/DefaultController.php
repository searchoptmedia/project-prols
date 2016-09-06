<?php

namespace AdminBundle\Controller;

use CoreBundle\Model\EmpRequest;
use CoreBundle\Model\EmpRequestPeer;
use CoreBundle\Model\EmpRequestQuery;
use CoreBundle\Model\EmpTimeReject;
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

use CoreBundle\Utilities\Mailer;

use Swift_Mailer;
use Swift_SmtpTransport;
use Swift_Message;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\Validator\Constraints\Email;

//-------------------------FOR ADMIN------------------------------------
class DefaultController extends Controller{


    public function timeInOut($id){
		//time in/out information
		$datatime = EmpTimePeer::getTime($id);
		$timename = '';

		if(isset($datatime) && !empty($datatime)){
			$currenttime = sizeof($datatime) - 1;
			$timein_data = $datatime[$currenttime]->getTimeIn();
			$timeout_data = $datatime[$currenttime]->getTimeOut();
			if(!is_null($timein_data) && !is_null($timeout_data)){
				// echo 'WORKING 1';
				$timename = true;
			}else if(!is_null($timein_data) && is_null($timeout_data)){
				// echo 'WORKING 2';
				$timename = false;
			}				
		} else if(isset($datatime) && empty($datatime)){
			// echo 'WORKING 3';
			$timename = true;
		}
		return $timename;    	
    }

    public function indexAction(Request $request)
	{

    	$user = $this->getUser();

    	$name = $user->getUsername();
		$page = 'Home';
    	$role = $user->getRole();
    	$id = $user->getId();

		$timename = self::timeInOut($id);   
		$overtime = 0;
		$timedata = EmpTimePeer::getTime($id);
		$currenttimein = 0;
		$currenttimeout = 0;
		$timeflag = 0;

		//get last timed in 
	   		for ($ctr = 0; $ctr < sizeof($timedata); $ctr++) {
    			$checktimein = $timedata[$ctr]->getTimeIn();
    			$checktimeout = $timedata[$ctr]->getTimeOut();
    			if(!is_null($checktimein) && is_null($checktimeout)){
    				$currenttimein = $checktimein->format('h:i A');
    				

    			}else{
    				$currenttimein = 0;
    				$currenttimeout = $checktimeout->format('h:i A');
    			}
    		}
    	$checkipdata = null;
    	//check if already timed in today
    	if(!empty($timedata)){
			$overtime = date('h:i A',strtotime('+9 hours',strtotime($currenttimein)));
			$datetoday = date('Y-m-d');
			$emp_time = EmpTimePeer::getTime($id);
			$currenttime = sizeof($emp_time) - 1;
			$timein_data = $emp_time[$currenttime]->getTimeIn();
			$timeout_data = $emp_time[$currenttime]->getTimeOut();
			$checkipdata = $emp_time[$currenttime]->getCheckIp();
    	}

		$et = EmpTimePeer::getEmpLastTimein($id);
		if(!empty($et)){
			$emptimedate = $et->getDate();
			$lasttimein	= $et->getTimeIn()->format('M d, Y, h:i A');
			if($emptimedate->format('Y-m-d') == $datetoday){
				$timeflag = 1;
			}

			if(! empty($et->getTimeOut()))
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
		if(!is_null($ip_add)){
			$matchedip = $ip_add->getAllowedIp();
		}
    	else{
    		$matchedip = '';
    	}

		if($userip == $matchedip){
			$ip_checker = 1;
		}else{
			$ip_checker = 0;
		}

		$allusers = EmpProfilePeer::getAllProfile();
		$userbdaynames = array();
		foreach($allusers as $u){
			$bday = $u->getBday()->format('m-d');
//			echo $datetoday . "|" .$bday . '<br>';
			if($bday == date('m-d')){
				$userbdaynames[] = $u->getFname();
			}
		}

        return $this->render('AdminBundle:Default:index.html.twig', array(
			'userbdaynames' => $userbdaynames,
        	'name' => $name,
        	'page' => $page,
         	'role' => $role,
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

        ));
    }
	
	public function checkTimeInAction()
	{
		$user = $this->getUser();
		$id = $user->getId();
		$timedata = EmpTimePeer::getEmpLastTimein($id);
		//exit if found no record
		if(is_null($timedata)) {
			echo 0;
			exit;
		}
		$timeout = $timedata->getTimeOut();
		$timeindate = $timedata->getDate()->format('Y-m-d');
		$datetoday = date('Y-m-d');
		if($timeindate == $datetoday && empty($timeout)){
			echo 1;
		}else{
			echo 0;
		}
		exit;
	}

 	public function timeInAction(Request $request, $id, $passw){

		date_default_timezone_set('Asia/Manila');

		//check session active
		$user = $this->getUser();
		if(empty($user)){
			// if session expire
			echo 4;
			exit;
		}

		$matchedip 		= InitController::getUserIP($this);
		$datetimetoday 	= date('Y-m-d H:i:s');
		$datetoday 		= date('Y-m-d');
		$timeflag 		= 0;
		$emp 			= $this->getUser()->getId();
		$timedata 		= EmpTimePeer::getTime($emp);
		$emptime 		= EmpTimePeer::getTime($id);

		if(!empty($emptime)) {
			$currenttime = sizeof($emptime) - 1;
			$timein_data = $emptime[$currenttime]->getTimeIn();
			$timeout_data = $emptime[$currenttime]->getTimeOut();
			$id_data = $emptime[$currenttime]->getId();

			for ($ctr = 0; $ctr < sizeof($timedata); $ctr++) {
				$checkdate = $timedata[$ctr]->getDate();

				if($checkdate->format('Y-m-d') == $datetoday && !is_null($timeout_data)){
					$timeflag = 1;
				}elseif($checkdate->format('Y-m-d') == $datetoday && is_null($timeout_data)) {
					$timeflag = 0;
				}elseif($checkdate->format('Y-m-d') != $datetoday){
					$timeflag = 0;
				}

			}
		}
    		

    		if($timeflag == 0){
    			//set employee time

		    	$empTimeSave = new EmpTime();

		    	//employee time in/out
		    	$emp_time = EmpTimePeer::getTime($id);
				if(isset($emp_time) && !empty($emp_time)){
				$currenttime = sizeof($emp_time) - 1;
				$timein_data = $emp_time[$currenttime]->getTimeIn();
				$timeout_data = $emp_time[$currenttime]->getTimeOut();
				$id_data = $emp_time[$currenttime]->getId();
					if(!is_null($timein_data) && !is_null($timeout_data)){
						$ip_add = ListIpPeer::getAllIp();
						$stat = 'WORKING 1';
						$retval = 1;
				    	$empTimeSave->setTimeIn($datetimetoday);
				    	$empTimeSave->setIpAdd($matchedip);
				    	$empTimeSave->setDate($datetoday);
				    	$empTimeSave->setEmpAccAccId($this->getUser()->getId());
				    	for($ctr = 0; $ctr < sizeof($ip_add); $ctr++){
			    			$allowedip = $ip_add[$ctr]->getAllowedIp();
			    			if($allowedip == $matchedip){
			    				$empTimeSave->setCheckIp(1);
			    			}else{
			    				$empTimeSave->setCheckIp(0);
			    				// $mailer = new Mailer();
			    				// $send = $mailer->sendOutOfOfficeEmailToAdmin();

			    			}
			    		}
				    	if($empTimeSave->save()){
							$is_message = $request->request->get('is_message');
							if(!is_null($is_message)){
								$email = new EmailController();
								$sendemail = $email->sendTimeInRequest($request, $this);

								if(! $sendemail){
									//if error sending email
									$retval = 5;
								} else {
									echo 1;
									exit;
								}
							}
						}
				    	echo $retval;
						exit;
					}else if(!is_null($timein_data) && is_null($timeout_data)){
						$user = $this->getUser();
						$pass = $user->getPassword();
						$inputpass = $passw;
						if($pass == $inputpass){
							$time_out = EmpTimePeer::retrieveByPk($id_data);
							$stat = 'WORKING 2';
							$retval = 2;
							$time_out->setTimeOut($datetimetoday);
							$time_out->setIpAdd($matchedip);
							$time_out->setEmpAccAccId($this->getUser()->getId());
//							$time_out->save();

							$in = new \DateTime($time_out->getTimeIn()->format('Y-m-d H:i:s'));
							$out = new \DateTime($time_out->getTimeOut()->format('Y-m-d H:i:s'));
							$manhours = date_diff($out, $in);
							$totalHours = $manhours->format('%h') . ':' . $manhours->format('%i');

							$h = $manhours->format('%h');
							$i = intval($manhours->format('%i'));
							$i = $i > 0 ? ($i/60) : 0;
							$totalHoursDec = number_format($h + $i, 2);

							if(date('D') == 'Sat' || date('D')=='Sun'){
								$time_out->setOvertime($totalHoursDec);

							}else{
								$time_out->setManhours($totalHoursDec);
								$overtime = 0;
								if($totalHoursDec > 9) {
									$overtime = $totalHoursDec - 9;
								}
								$time_out->setOvertime($overtime);
							}

							$time_out->save();

							echo $retval;
						}else{
							$message = 'Wrong Password';
							$error = true;
							$response = array('message' => $message, 'error' => $error);
							echo json_encode($response);
						}

					}
				}else{
					$ip_add = ListIpPeer::getAllIp();
					$stat = 'WORKING 3';
					$retval = 1;
			    	$empTimeSave->setTimeIn($datetimetoday);
			    	$empTimeSave->setIpAdd($matchedip);
			    	$empTimeSave->setDate($datetoday);
			    	$empTimeSave->setEmpAccAccId($this->getUser()->getId());

					for($ctr = 0; $ctr < sizeof($ip_add); $ctr++){
						$allowedip = $ip_add[$ctr]->getAllowedIp();
						if($allowedip == $matchedip){
							$empTimeSave->setCheckIp(1);
						}else{
							$empTimeSave->setCheckIp(0);
						}
					}

					if($empTimeSave->save()){
						$is_message = $request->request->get('is_message');
						if(!is_null($is_message)){
							$email = new EmailController();
							$sendemail = $email->sendTimeInRequest($request, $this);

							if(! $sendemail){
								//if error sending email
								$retval = 5;
							} else {
								echo 1;
								exit;
							}
						}
					}
			    	echo $retval;
				}

    		}else{
    			$retval = 3;

    			echo $retval;
    		}
	    	
    	// }else{
    	// 	$retval = 0;
    	// 	echo $retval;
    	// }
			// $ip_add = ListIpPeer::getValidIP($this->container->get('request')->getClientIp());
    		// if(!is_null($ip_add)){
    		// 	$checkip = EmpTimePeer::retrieveByPk($id_data);
    		// 	$checkip->setCheckIp(0);
    		// }else{
    		// 	$checkip = EmpTimePeer::retrieveByPk($id_data);
    		// 	$checkip->setCheckIp(1);
    		// }

    	exit;
    }

    public function profileAction(){
    	
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

		if(!is_null($datacontact)){
			for ($ct = 0; $ct < sizeof($datacontact); $ct++) {
    			// $contactArr[$ct] = $datacontact[$ct]->getContact(); 
				$contacttype =  ListContTypesPeer::getContactType($datacontact[$ct]->getListContTypesId())->getContactType();
				$contactvalue =  $datacontact[$ct]->getContact();
				$contactid = $datacontact[$ct]->getId();
				if(strcasecmp($contacttype, 'email') == 0){
					$conEmail .= $contactvalue;
					$conEmailId .= $contactid;
				}elseif(strcasecmp($contacttype, 'mobile') == 0){
					$conMobile .= $contactvalue;
					$conMobileId .= $contactid;
				}elseif(strcasecmp($contacttype, 'telephone') == 0){
					$conTele .= $contactvalue;
					$conTeleId .= $contactid;
				}

    			$contact .= '<p>Contact:'.$contactvalue.'</p><p>Concact Type:'.$contacttype.'</p>';
   			} 			
		}else{
			$contact = null;
			$contype = null;

			$contact2 = null;
			$contype2 = null;
		}

		//employee work information
		$datawork = EmpProfilePeer::retrieveByPk($profileid);

		if(!is_null($datawork)){
			$workdeptid = $datawork->getListDeptDeptId();
			$workposid = $datawork->getListPosPosId();
			$empnumber = $datawork->getEmployeeNumber();

			$datadept = ListDeptPeer::getDept($workdeptid);
			$datapos = ListPosPeer::getPos($workposid);

			$deptnames = $datadept->getDeptNames();
			$posStatus = $datapos->getPosNames();
		}else{
			$workdeptid = null;
			$workposid = null;
			$empnumber = null;
			$deptnames = null;
			$posStatus = null;
		}

		$timename = self::timeInOut($id);    	
		$getDept = ListDeptPeer::getAllDept();

		//check pending count
		$requestcount = EmpRequestQuery::create()
		->filterByStatus('Pending')
		->find()->count();

		//Check late
		$late = 0;
    	$getEmpTime = EmpTimePeer::getTime($id);
    	for ($ct = 0; $ct < sizeof($getEmpTime); $ct++) {
    		$checklate = $getEmpTime[$ct]->getTimeIn();
    		if($checklate->format('H:i:s') > 12){
    		$late++;
    		}	
    	}

		$timedata = EmpTimePeer::getTime($id);
		$timeflag = 0;
		$currenttimein = 0;
		$currenttimeout = 0;
	   		for ($ctr = 0; $ctr < sizeof($timedata); $ctr++) {
    			$checktimein = $timedata[$ctr]->getTimeIn();
    			$checktimeout = $timedata[$ctr]->getTimeOut();
    			if(!is_null($checktimein) && is_null($checktimeout)){
    				$currenttimein = $checktimein->format('h:i A');
    				
    			}else{
    				$currenttimein = 0;
    				$currenttimeout = $checktimeout->format('h:i A');
    			}
    		}
    	$timeoutdata = '';
    	$checkipdata = null;
    	if(!empty($timedata)){
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
		if(!empty($et)){
			$lasttimein	= $et->getTimeIn()->format('M d, Y, h:i A');
			$emptimedate = $et->getDate();
			if($emptimedate->format('Y-m-d') == $datetoday){
				$timeflag = 1;
			}

			if(! empty($et->getTimeOut()))
				$isTimeOut = 'true';
		}
		// echo'<pre>';var_dump($manhours);
		$userip = InitController::getUserIP($this);
		$ip_add = ListIpPeer::getValidIP($userip);
		$is_ip  = InitController::checkIP($userip);

		$getAllTimeData = EmpTimePeer::getTimeDescendingOrder($id);

        return $this->render('AdminBundle:Default:profile.html.twig', array(
        	'page' => $page,
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
			'getAllTime' => $getAllTimeData,
			'timetoday' => $timetoday,
        	));

    }

    public function requestAction(){
  
    	$user = $this->getUser();
    	$name = $user->getUsername();
		$page = 'View Request';
    	$role = $user->getRole();
    	$id = $user->getId();

		$timename = self::timeInOut($id); 
		// redirect to index if not Admin
		if((strcasecmp($role, 'employee') == 0)){
			return $this->redirect($this->generateUrl('admin_homepage'));
		}	
		else {
		
		$requestGet = EmpRequestPeer::getAllRequest();

			$timedata = EmpTimePeer::getTime($id);
			$timeflag = 0;
			$currenttimein = 0;
			$currenttimeout = 0;
			for ($ctr = 0; $ctr < sizeof($timedata); $ctr++) {
				$checktimein = $timedata[$ctr]->getTimeIn();
				$checktimeout = $timedata[$ctr]->getTimeOut();
				if(!is_null($checktimein) && is_null($checktimeout)){
					$currenttimein = $checktimein->format('h:i A');

				}else{
					$currenttimein = 0;
					$currenttimeout = $checktimeout->format('h:i A');
				}
			}
			$timeoutdata = '';
			$checkipdata = null;
			if(!empty($timedata)){
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
			if(!empty($et)){
				$lasttimein	= $et->getTimeIn()->format('M d, Y, h:i A');
				$emptimedate = $et->getDate();
				if($emptimedate->format('Y-m-d') == $datetoday){
					$timeflag = 1;
				}

				if(! empty($et->getTimeOut()))
					$isTimeOut = 'true';
			}

		//echo '<pre>';var_dump($requestGet);exit;		
			$userip = InitController::getUserIP($this);
			$ip_add = ListIpPeer::getValidIP($userip);
			$is_ip  = InitController::checkIP($userip);

			$requestcount = EmpRequestQuery::create()
				->filterByStatus('Pending')
				->find()->count();


			return $this->render('AdminBundle:Default:request.html.twig', array(
        	'name' => $name,
        	'page' => $page,
         	'role' => $role,
        	'user' => $user,
          	'timename' => $timename,
          	'allrequest' => $requestGet,
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

    public function statusAcceptAction(Request $req, $id, $id2){
		$accept = EmpRequestPeer::retrieveByPk($id);
		if(isset($accept) && !empty($accept)) {
			$accept->setAdminId($id2);
			$accept->setStatus('Accepted');

			$email = new EmailController();

			$sendemail = $email->acceptRequestEmail($req, $this);

			if($accept->save()){
				$response = array('response' => 'success');
			}else{
				$response = array('response' => 'not saved');
			}
			
		} else {
			$response = array('response' => 'not found');
		}
		
		echo json_encode($response);
		exit;
    }



    public function profileUpdateAction(Request $request){
		$user = $this->getUser();
		$empid = $user->getId();
   		$telId = $request->request->get('telId');
   		$mobileId = $request->request->get('mobileId');

		$updateprofile = EmpProfilePeer::getInformation($empid);
		$updateprofile->setAddress($request->request->get('address'));
		$updateprofile->save();

		$profileId = $updateprofile->getId();

		$updatetel = EmpContactPeer::retrieveByPk($telId);
		if(empty($updatetel)){
			$newtel = new EmpContact();
			$newtel->setContact($request->request->get('telephone'));
			$newtel->setEmpProfileId($profileId);
			$newtel->setListContTypesId(3);
			$newtel->save();

		}else{
			$updatetel->setContact($request->request->get('telephone'));
			$updatetel->save();
		}
		$updatecell = EmpContactPeer::retrieveByPk($mobileId);
		if(empty($updatecell)){
			$newcel = new EmpContact();
			$newcel->setContact($request->request->get('cellphone'));
			$newcel->setEmpProfileId($profileId);
			$newcel->setListContTypesId(2);
			$newcel->save();
		}else{
			$updatecell->setContact($request->request->get('cellphone'));
			$updatecell->save();
		}
		$response = array('Update Successful' => 'success');
		echo json_encode($response);
		exit;
    }

    public function manageAction(Request $request){
		$user = $this->getUser();
    	$name = $user->getUsername();
		$page = 'View Request';
    	$role = $user->getRole();
    	$id = $user->getId();
		$timename = self::timeInOut($id); 

    	if((strcasecmp($role, 'employee') == 0)){
			return $this->redirect($this->generateUrl('admin_homepage'));
		}
		else{
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
				if(!is_null($checktimein) && is_null($checktimeout)){
					$currenttimein = $checktimein->format('h:i A');
				}else{
					$currenttimein = 0;
					$currenttimeout = $checktimeout->format('h:i A');
				}
			}
			$checkipdata = null;
			//check if already timed in today
			if(!empty($timedata)){

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
			if(!empty($et)){
				$lasttimein	= $et->getTimeIn()->format('M d, Y, h:i A');
				$emptimedate = $et->getDate();
				if($emptimedate->format('Y-m-d') == $datetoday){
					$timeflag = 1;
				}

				if(! empty($et->getTimeOut()))
					$isTimeOut = 'true';
			}

			$requestcount = EmpRequestQuery::create()
				->filterByStatus('Pending')
				->find()->count();

		return $this->render('AdminBundle:Default:manage.html.twig', array(
        	'name' => $name,
        	'page' => $page,
         	'role' => $role,
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

	public function timeLogsAction(){
		$user = $this->getUser();
		$name = $user->getUsername();
		$page = 'View Request';
		$role = $user->getRole();
		$id = $user->getId();
		$timename = self::timeInOut($id);

		if((strcasecmp($role, 'employee') == 0)){
			return $this->redirect($this->generateUrl('admin_homepage'));
		}
		else{

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
				if(!is_null($checktimein) && is_null($checktimeout)){
					$currenttimein = $checktimein->format('h:i A');
				}else{
					$currenttimein = 0;
					$currenttimeout = $checktimeout->format('h:i A');
				}
			}
			$checkipdata = null;
			//check if already timed in today
			if(!empty($timedata)){
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
			if(!empty($et)){
				$lasttimein	= $et->getTimeIn()->format('M d, Y, h:i A');
				$emptimedate = $et->getDate();
				if($emptimedate->format('Y-m-d') == $datetoday){
					$timeflag = 1;
				}
				if(! empty($et->getTimeOut()))
					$isTimeOut = 'true';
			}

			$requestcount = EmpRequestQuery::create()
				->filterByStatus('Pending')
				->find()->count();

			return $this->render('AdminBundle:Default:managetime.html.twig', array(
				'name' => $name,
				'page' => $page,
				'role' => $role,
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
	
    public function addEmployeeAction(Request $request){
    	$user = $this->getUser();
    	$role = $user->getRole();
   		
   		// Check role
    	if((strcasecmp($role, 'employee') == 0)){
			return $this->redirect($this->generateUrl('admin_homepage'));
		}else{
			$pass = $request->request->get('password');
	    	$repass = $request->request->get('repassword');
	    	$user = $request->request->get('username');
	    	$empNum = $request->request->get('empnum');
		    $empFname = $request->request->get('fname');
		    $empLname = $request->request->get('lname');
	    	$empAddress = $request->request->get('address');
	    	$empBday = $request->request->get('bday');
	    	$empDept = $request->request->get('dept');
	    	$empPos = $request->request->get('pos');
	    	$empStatus = $request->request->get('status');
			$empEmail = $request->request->get('email');
			$empTelNum = $request->request->get('telnum');
			$empCelNum = $request->request->get('cellnum');

		    date_default_timezone_set('Asia/Manila');
		    $datetimetoday = date('Y-m-d H:i:s');
	  		
	  		//check if input is empty
	    	if(!empty($empNum) && !empty($empFname) && !empty($empLname) && !empty($empAddress) && !empty($empBday)
	    		&& !empty($empDept) && !empty($empPos) && !empty($empPos) && !empty($empStatus) && !empty($user)
	    		&& !empty($pass) && !empty($repass)){
	    		//Check password and repassword
		    	if($pass != $repass){
			    	$response = array('Password does not match');
					echo json_encode($response);
					exit;
		    	}else{
		    		//add new acc
			 		$addacc = new EmpAcc(); 
			    	$addacc->setUsername($user);
			    	$addacc->setPassword($pass);
			    	$addacc->setRole('employee');
					$addacc->setEmail($empEmail);
			    	$addacc->save();
			    	$newacc = $addacc->getId();

			    	//add new profile
		    		$addemp = new EmpProfile();
			    	$addemp->setEmpAccAccId($newacc);
			    	$addemp->setEmployeeNumber($empNum);
			    	$addemp->setFname($empFname);
			    	$addemp->setLname($empLname);
			    	$addemp->setAddress($empAddress);
			    	$addemp->setBday($empBday);
//			    	$addemp->setDateJoined($current_date);
			    	$addemp->setListDeptDeptId($empDept);
			    	$addemp->setListPosPosId($empPos);
			    	$addemp->setStatus($empStatus);
			    	$addemp->save();
			    	$empid = $addemp->getId();

			    	$cellcontact = new EmpContact();
			    	$cellcontact->setEmpProfileId($empid);
			    	$cellcontact->setListContTypesId(2);
			    	$cellcontact->setContact($request->request->get('cellnum'));
			    	$cellcontact->save();

					$telcontact = new EmpContact();
			    	$telcontact->setEmpProfileId($empid);
			    	$telcontact->setListContTypesId(3);
			    	$telcontact->setContact($request->request->get('telnum'));
			    	$telcontact->save();

					$response = array('Added Successfully');
	    			}
	    		}else{
					$response = array('Missing Input');
		    	}
			$resp = array('response' => $response);
			echo json_encode($resp);
			exit;
			}
    }
	
	public function requestMeetingAction(Request $req){
		$requestMeeting = new EmpRequest();
		date_default_timezone_set('Asia/Manila');
    	$current_date = date('Y-m-d H:i:s');
		$requestMeeting->setRequest($req->request->get('reqmeetmessage'));
		$requestMeeting->setStatus('Pending');
		$requestMeeting->setDateStarted($current_date);
		$requestMeeting->setDateEnded($current_date);
		$requestMeeting->setEmpAccId($this->getUser()->getId());
		$requestMeeting->setListRequestTypeId(4);
		$requestMeeting->save();
		echo json_encode(array('result' => 'ok'));
		exit;
	}

	public function requestLeaveAction(Request $req){

		$leaveinput = new EmpRequest();
		$leaveinput->setRequest($req->request->get('reasonleave'));
		$leaveinput->setStatus('Pending');
		$leaveinput->setDateStarted($req->request->get('leavestartdate'));
		$leaveinput->setDateEnded($req->request->get('leaveenddate'));
		$leaveinput->setEmpAccId($this->getUser()->getId());
		$leaveinput->setListRequestTypeId($req->request->get('typeleave'));
		$leaveinput->save();
		$email = new EmailController();
		$sendemail = $email->requestTypeEmail($req, $this);
		if (!$sendemail) {
			$emailresp = 'No email sent';
		} else {
			$emailresp = 'Email Sent';
		}
			echo json_encode(array('result' => 'ok', 'emailresp' => $emailresp));
		exit;
		}

	public function addPositionAction($id){
		$user = $this->getUser();
    	$role = $user->getRole();
   
    	if((strcasecmp($role, 'employee') == 0)){
			return $this->redirect($this->generateUrl('admin_homepage'));
		}else{
		$response = '';
			if(isset($id) && !empty($id)){
				$addpos = new ListPos();
				$addpos->setPosNames($id);
				$addpos->save();
				$response = array('Added successfully');	
			}else{
				$response = array('No input');
			}
			
			echo json_encode($response);
			exit;	
		}
	}

	public function addDepartmentAction($id){
		$user = $this->getUser();
    	$role = $user->getRole();
   
    	if((strcasecmp($role, 'employee') == 0)){
			return $this->redirect($this->generateUrl('admin_homepage'));
		}else{
			$response = '';
			if(isset($id) && !empty($id)){
			$addDept = new ListDept();
			$addDept->setDeptNames($id);
			$addDept->save();
			$response = array('Added successfully');	
			}else{
				$response = array('No input');
			}
			echo json_encode($response);
			exit;
		}	
	}

	public function notifAction(){
		$user = $this->getUser();
    	$name = $user->getUsername();
		$page = 'View Request';
    	$role = $user->getRole();
    	$id = $user->getId();
		$timename = self::timeInOut($id); 

//		$data = EmpLeaveQuery::create()
//		->filterByStatus('Pending')
//		->find()->count();

		$getRequests = EmpRequestPeer::getAllRequest();
		$timedata = EmpTimePeer::getTime($id);
		$currenttimein = 0;
		$currenttimeout = 0;
		$timeflag = 0;

		//get last timed in
		for ($ctr = 0; $ctr < sizeof($timedata); $ctr++) {
			$checktimein = $timedata[$ctr]->getTimeIn();
			$checktimeout = $timedata[$ctr]->getTimeOut();
			if(!is_null($checktimein) && is_null($checktimeout)){
				$currenttimein = $checktimein->format('h:i A');


			}else{
				$currenttimein = 0;
				$currenttimeout = $checktimeout->format('h:i A');
			}
		}
		$checkipdata = null;
		//check if already timed in today
		if(!empty($timedata)){

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
		if(!empty($et)){
			$lasttimein	= $et->getTimeIn()->format('M d, Y, h:i A');
			$emptimedate = $et->getDate();
			if($emptimedate->format('Y-m-d') == $datetoday){
				$timeflag = 1;
			}
			if(! empty($et->getTimeOut()))
				$isTimeOut = 'true';


		}

		$requestcount = EmpRequestQuery::create()
			->filterByStatus('Pending')
			->find()->count();
		return $this->render('AdminBundle:Default:notif.html.twig', array(
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

	public function employeeProfileAction($id){
		$user = $this->getUser();
    	$name = $user->getUsername();
    	$role = $user->getRole();
    	$adminid = $user->getId();
		$timename = self::timeInOut($adminid);
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

		if(!is_null($datacontact)){
			for ($ct = 0; $ct < sizeof($datacontact); $ct++) {
				// $contactArr[$ct] = $datacontact[$ct]->getContact();
				$contacttype =  ListContTypesPeer::getContactType($datacontact[$ct]->getListContTypesId())->getContactType();
				$contactvalue =  $datacontact[$ct]->getContact();
				$contactid = $datacontact[$ct]->getId();
				if(strcasecmp($contacttype, 'email') == 0){
					$conEmail .= $contactvalue;
					$conEmailId .= $contactid;
				}elseif(strcasecmp($contacttype, 'mobile') == 0){
					$conMobile .= $contactvalue;
					$conMobileId .= $contactid;
				}elseif(strcasecmp($contacttype, 'telephone') == 0){
					$conTele .= $contactvalue;
					$conTeleId .= $contactid;
				}

				$contact .= '<p>Contact:'.$contactvalue.'</p><p>Concact Type:'.$contacttype.'</p>';
			}
		}else{
			$contact = null;
			$contype = null;

			$contact2 = null;
			$contype2 = null;
		}

		//employee work information
		$datawork = EmpProfilePeer::retrieveByPk($profileid);

		if(!is_null($datawork)){
			$workdeptid = $datawork->getListDeptDeptId();
			$workposid = $datawork->getListPosPosId();
			$empnumber = $datawork->getEmployeeNumber();

			$datadept = ListDeptPeer::getDept($workdeptid);
			$datapos = ListPosPeer::getPos($workposid);

			$deptnames = $datadept->getDeptNames();
			$posStatus = $datapos->getPosNames();
		}else{
			$workdeptid = null;
			$workposid = null;
			$empnumber = null;
			$deptnames = null;
			$posStatus = null;
		}

		$getDept = ListDeptPeer::getAllDept();

		//check pending count
		$requestcount = EmpRequestQuery::create()
			->filterByStatus('Pending')
			->find()->count();

		//Check late
		$late = 0;
		$getEmpTime = EmpTimePeer::getTime($id);
		for ($ct = 0; $ct < sizeof($getEmpTime); $ct++) {
			$checklate = $getEmpTime[$ct]->getTimeIn();
			if($checklate->format('H:i:s') > 12){
				$late++;
			}
		}


		$timedata = EmpTimePeer::getTime($adminid);
		$timeflag = 0;
		$currenttimein = 0;
		$currenttimeout = 0;
		for ($ctr = 0; $ctr < sizeof($timedata); $ctr++) {
			$checktimein = $timedata[$ctr]->getTimeIn();
			$checktimeout = $timedata[$ctr]->getTimeOut();
			if(!is_null($checktimein) && is_null($checktimeout)){
				$currenttimein = $checktimein->format('h:i A');

			}else{
				$currenttimein = 0;
				$currenttimeout = $checktimeout->format('h:i A');
			}
		}
		$timeoutdata = '';
		$checkipdata = null;
		if(!empty($timedata)){
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

		if(!empty($timedata) && !empty($timeout_data)){
			for ($ctr = 0; $ctr < sizeof($timedata); $ctr++) {
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
		if(!empty($et)){
			$lasttimein	= $et->getTimeIn()->format('M d, Y, h:i A');
			$emptimedate = $et->getDate();
			if($emptimedate->format('Y-m-d') == $datetoday){
				$timeflag = 1;
			}
			if(! empty($et->getTimeOut()))
				$isTimeOut = 'true';
		}


		$userip = InitController::getUserIP($this);
		$ip_add = ListIpPeer::getValidIP($userip);
		$is_ip  = InitController::checkIP($userip);

		$getAllTimeData = EmpTimePeer::getTimeDescendingOrder($id);

		return $this->render('AdminBundle:Default:empprofile.html.twig', array(
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

    public function empDeleteAction($id){
    	$user = $this->getUser();
    	$role = $user->getRole();
   
    	if((strcasecmp($role, 'employee') == 0)){
			return $this->redirect($this->generateUrl('admin_homepage'));
		}else{
	    	$empinfo = EmpProfilePeer::retrieveByPk($id);
	    	$empinfo->setProfileStatus(1);
	    	$empinfo->save();
	    	$response = array('Successfully Deleted');
			echo json_encode($response);
	    	exit;
    	}
    }

    public function declinedRequestAction(Request $req){
    $declined = EmpRequestPeer::retrieveByPk($req->request->get('leaveId'));
		if(isset($declined) && !empty($declined)) {
			$requesttype = $declined->getListRequestTypeId();
			if($requesttype == 3){
				$timeId = $declined->getEmpTimeId();
				$emptime = EmpTimePeer::retrieveByPK($timeId);
				$rejectemptime = new EmpTimeReject();
				$rejectemptime->setTimeIn($emptime->getTimeIn());
				$rejectemptime->setTimeOut($emptime->getTimeOut());
				$rejectemptime->setIpAdd($emptime->getIpAdd());
				$rejectemptime->setDate($emptime->getDate());
				$rejectemptime->setEmpAccAccId($emptime->getEmpAccAccId());
				$rejectemptime->setManhours($emptime->getManhours());
				$rejectemptime->setOvertime($emptime->getOvertime());
				$rejectemptime->setCheckIp($emptime->getCheckIp());
				$rejectemptime->save();
				$emptime->delete();
			}
			$declined->setAdminId($req->request->get('adminId'));
			$declined->setStatus('Declined');
			$email = new EmailController();

			$sendemail = $email->declinedRequestEmail($req, $this);

			if($declined->save()){
				$response = array('response' => 'success');
			}else{
				$response = array('response' => 'not saved');
			}
			
		} else {
			$response = array('response' => 'not found');
		}


  //   	$user = $this->getUser();
  //   	$id = $user->getId();

		// //employee profile information
		// $data = EmpProfilePeer::getInformation($id);
		// $name = $data->getFname(). " " .$data->getLname();
		// $profileid = $data->getId();
		
		// //employee contact information
		// $datacontact = EmpContactPeer::getContact($profileid);		
		// $conEmail = '';

		// if(!is_null($datacontact)){
		// 	for ($ct = 0; $ct < sizeof($datacontact); $ct++) {
  //   			// $contactArr[$ct] = $datacontact[$ct]->getContact(); 
		// 		$contacttype =  ListContTypesPeer::getContactType($datacontact[$ct]->getListContTypesId())->getContactType();
		// 		$contactvalue =  $datacontact[$ct]->getContact();
		// 		if(strcasecmp($contacttype, 'email') == 0){
		// 			$conEmail = $contactvalue;
		// 		}
  //  			}
		// }else{
		// 	$conEmail = null;
		// }


		echo json_encode($response);
		exit;
    }

    public function checkTimeAction(){

    	$user = $this->getUser();
    	$id = $user->getId();

    	echo 1;
    	exit;
    		
  		// echo '<pre>';var_dump($overtime);exit;
    }

    public function acceptRequestAction(){
    	$empip = $this->getRequest()->server->get('HTTP_X_FORWARDED_FOR');
    	$acceptrequest = new ListIp();
    	$acceptrequest->setAllowedIp($empip);
    	$acceptrequest->setStatus('Active');
    	$acceptrequest->save();
    	echo 1;
    	exit;
    }

	public function changePasswordAction(Request $request){
		$response = '';
		$error = '';
		$user = $this->getUser();
		$userid = $user->getId();
		$getinfo = EmpAccPeer::retrieveByPk($userid);
		if(!is_null($getinfo)){
			$oldpass = $getinfo->getPassword();
			$inputpass = $request->request->get('oldpass');
			$newpass = $request->request->get('newpass');
			$confirmpass = $request->request->get('conpass');
			if($oldpass == $inputpass){
				$getinfo->setPassword($newpass);
				$getinfo->save();
				$response = 'Password changed';
				$error = false;
			}else{
				$response = 'Wrong Password';
				$error = true;
			}

		}

		$resp = array('response' => $response, 'error' => $error);
		echo json_encode($resp);
		exit;
		//   	echo json_encode($response);
		//   	$referer = $request->headers->get('referer');
		// return new RedirectResponse($referer);
	}

	public function sendEmailAction(Request $request)
	{
		$val = $request->request->get('email');

//		$c = new \Criteria();
//		$c->add(EmpAccPeer::EMAIL, $val, \Criteria::EQUAL);
//		$valid = EmpAccPeer::doCount($c);
//
//
//		$valid = EmpAccQuery::create()
//			->filterByEmail($val)
//			->count();

		$valid = EmpAccPeer::getUserByEmail($val);
		if($valid == 1){
			$accesskey = sha1(rand());
			$user = EmpAccPeer::getUserInfo($val);
			$user->setKey($accesskey);


			$subject = 'Forgot Password';
			$from = array('prols.mailer@propelrr.com' => 'Prols Mailer');
			$to = array(
				'christian.fallaria@searchoptmedia.com'  => 'Recipient1 Name',
			);

			$text = 'Forgot Password';

			$transport = Swift_SmtpTransport::newInstance('smtp.gmail.com', 465, 'ssl');
			$transport->setUsername('christian.fallaria@searchoptmedia.com');
			$transport->setPassword("baddonk123");
			$swift = Swift_Mailer::newInstance($transport);

			$message = new Swift_Message($subject);
			$message->setFrom($from);
			$message->setBody($text, 'text/plain');
			$message->setTo($to);
			$message->addPart($text, 'text/plain');

			if ($recipients = $swift->send($message))
			{
				echo "Email Sent";
			} else {
				echo "There was an error";
			}
			exit;

		}else{
			echo 'Invalid Email';
			exit;
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
					if($ot >= 0){
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
//end
}
