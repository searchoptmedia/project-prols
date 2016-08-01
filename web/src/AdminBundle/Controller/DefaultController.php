<?php

namespace AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
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

    public function indexAction(){

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
    	// echo $checkipdata;

    		for ($ctr = 0; $ctr < sizeof($timedata); $ctr++) {
    			$checkdate = $timedata[$ctr]->getDate();    			
    			if($checkdate->format('Y-m-d') == $datetoday && !is_null($timeout_data)){
    				$timeflag = 1;
				}elseif($checkdate->format('Y-m-d') == $datetoday && is_null($timeout_data)){
					$timeflag = 0;
				}elseif($checkdate->format('Y-m-d') != $datetoday){
					$timeflag = 0;
				}
    				
    		}	
    	}

		$systime = date('H:i A');
		$afternoon = date('H:i A', strtotime('12 pm')); 	
		//counts number of pending requests
		$data = EmpLeaveQuery::create()
		->filterByStatus('Pending')
		->find()->count();

		$ip_add = ListIpPeer::getValidIP($this->container->get('request')->getClientIp());
		$userip = $this->getRequest()->server->get('HTTP_X_FORWARDED_FOR');

		echo $userip;

//		var_dump($this->getRequest()->server->all());
//		exit;
		if(!is_null($ip_add)){
			$matchedip = $ip_add->getAllowedIp();
		}
    	else{
    		$matchedip = '';
    	}

		echo '<br>' + $matchedip;
		exit;

   //  		$mailer = new Mailer();
			// $send = $mailer->sendOutOfOfficeEmailToAdmin();
			// var_dump($send);    				


        return $this->render('AdminBundle:Default:index.html.twig', array(
        	'name' => $name,
        	'page' => $page,
         	'role' => $role,
        	'user' => $user,
          	'timename' => $timename,
          	'id' => $id,
          	'data' => $data,
          	'currenttimein' => $currenttimein,
          	'currenttimeout' => $currenttimeout,
          	'overtime' => $overtime,
          	'timeflag' => $timeflag,
          	'systime' => $systime,
          	'afternoon' => $afternoon,
          	'userip' => $userip,
          	'matchedip' => $matchedip,
          	'checkipdata' => $checkipdata,

        ));
    }

/*
public function timeInAction($id){
$ip_add = ListIpPeer::getValidIP($this->container->get('request')->getClientIp());

    	if(!is_null($ip_add)){
    		$matchedip = $ip_add->getAllowedIp();
    		date_default_timezone_set('Asia/Manila');
    		$current_date = date('Y-m-d H:i:s');

	    	//set employee time

	    	$empTimeSave = new EmpTime();
	    	$empTimeSave->setTimeIn($current_date);
			    	$empTimeSave->setIpAdd($matchedip);
			    	$empTimeSave->setDate($current_date);
			    	$empTimeSave->setEmpAccAccId($this->getUser()->getId());
			    	$empTimeSave->save();
		
	}

}

public function timeOutAction($id){
$ip_add = ListIpPeer::getValidIP($this->container->get('request')->getClientIp());

    	if(!is_null($ip_add)){
    		$matchedip = $ip_add->getAllowedIp();
    		date_default_timezone_set('Asia/Manila');
    		$current_date = date('Y-m-d H:i:s');

    				$time_out = EmpTimePeer::retrieveByPk($id);
			    	$time_out->setTimeOut($current_date);
			    	$time_out->setIpAdd($matchedip);
			    	$time_out->setDate($current_date);
			    	$time_out->setEmpAccAccId($this->getUser()->getId());
			    	$time_out->save();


			    }


}

*/


 public function timeInAction($id){
		//valid ip address
    	// $ip_add = ListIpPeer::getValidIP($this->container->get('request')->getClientIp());

    	// if(!is_null($ip_add)){
    		$matchedip = $this->container->get('request')->getClientIp();
    		date_default_timezone_set('Asia/Manila');
    		$current_date = date('Y-m-d H:i:s');
    		$datetoday = date('Y-m-d');
    		$timeflag = 0;
			$emp = $this->getUser()->getId();
    		$timedata = EmpTimePeer::getTime($emp);
    		$emptime = EmpTimePeer::getTime($id);
    		
    		if(!empty($emptime)){
    		$currenttime = sizeof($emptime) - 1;
			$timein_data = $emptime[$currenttime]->getTimeIn();
			$timeout_data = $emptime[$currenttime]->getTimeOut();
			$id_data = $emptime[$currenttime]->getId();
			

	    		for ($ctr = 0; $ctr < sizeof($timedata); $ctr++) {
	    			$checkdate = $timedata[$ctr]->getDate();
	    			
	    			if($checkdate->format('Y-m-d') == $datetoday && !is_null($timeout_data)){
	    				$timeflag = 1;
					}elseif($checkdate->format('Y-m-d') == $datetoday && is_null($timeout_data)){
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
				    	$empTimeSave->setTimeIn($current_date);
				    	$empTimeSave->setIpAdd($matchedip);
				    	$empTimeSave->setDate($datetoday);
				    	$empTimeSave->setEmpAccAccId($this->getUser()->getId());
				    	for($ctr = 0; $ctr < sizeof($ip_add); $ctr++){
			    			$allowedip = $ip_add[$ctr]->getAllowedIp();
			    			if($allowedip == $matchedip){
			    				$empTimeSave->setCheckIp(0);
			    				$empTimeSave->save();
			    			}else{
			    				$empTimeSave->setCheckIp(1);
			    				$empTimeSave->save();
			    				// $mailer = new Mailer();
			    				// $send = $mailer->sendOutOfOfficeEmailToAdmin();

			    			}
			    		}
				    	$empTimeSave->save();
				    	echo $retval;
					}else if(!is_null($timein_data) && is_null($timeout_data)){
						$time_out = EmpTimePeer::retrieveByPk($id_data);
						$stat = 'WORKING 2';
						$retval = 2;
				    	$time_out->setTimeOut($current_date);
				    	$time_out->setIpAdd($matchedip);
				    	$time_out->setEmpAccAccId($this->getUser()->getId());
				    	$time_out->save();
				    	echo $retval;
					}
				}else{
					$ip_add = ListIpPeer::getAllIp();
					$stat = 'WORKING 3';
					$retval = 1;
			    	$empTimeSave->setTimeIn($current_date);
			    	$empTimeSave->setIpAdd($matchedip);
			    	$empTimeSave->setDate($datetoday);
			    	$empTimeSave->setEmpAccAccId($this->getUser()->getId());
			    	for($ctr = 0; $ctr < sizeof($ip_add); $ctr++){
			    			$allowedip = $ip_add[$ctr]->getAllowedIp();
			    			if($allowedip == $matchedip){
			    				$empTimeSave->setCheckIp(0);
			    				$empTimeSave->save();

			    			}else{
			    				$empTimeSave->setCheckIp(1);
			    				$empTimeSave->save();
			    				// $mailer = new Mailer();
			    				// $send = $mailer->sendOutOfOfficeEmailToAdmin();



			    			}
			    		}
			    	$empTimeSave->save();
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

		//employee profile information
		$data = EmpProfilePeer::getInformation($id);

		$fname = $data->getFname();
		$lname = $data->getLname();
		$mname = $data->getMname();
		$bday = $data->getBday();
		$bday = date_format($bday, 'd/m/y');
		$address = $data->getAddress();
		$img = $data->getImgPath();
		$datejoined = $data->getDateJoined();
		$datejoined = date_format($datejoined, 'd/m/y');
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
		$data = EmpLeaveQuery::create()
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

    	$overhour = 9;
		$overtime = 0;
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
    	$overtime = date('H:i:s', $currenttimein + strtotime('+9 hours'));
    	$datetoday = date('Y-m-d');
    	$emp_time = EmpTimePeer::getTime($id);
    	$currenttime = sizeof($emp_time) - 1;
    	$timein_data = $emp_time[$currenttime]->getTimeIn();
		$timeout_data = $emp_time[$currenttime]->getTimeOut();
		$checkipdata = $emp_time[$currenttime]->getCheckIp();
    		for ($ctr = 0; $ctr < sizeof($timedata); $ctr++) {
    			$checkdate = $timedata[$ctr]->getDate();
    			
    			if($checkdate->format('Y-m-d') == $datetoday && !is_null($timeout_data)){
    				$timeflag = 1;
				}elseif($checkdate->format('Y-m-d') == $datetoday && is_null($timeout_data)){
					$timeflag = 0;
				}elseif($checkdate->format('Y-m-d') != $datetoday){
					$timeflag = 0;
				}
    				
    		}
    	}
    	$firstchar = $fname[0];
    	if(!empty($timeoutdata)){
    		$timedata = EmpTimePeer::getTime($id);
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

		
				// echo'<pre>';var_dump($manhours);
				
			}
    	}
    	
    	$ip_add = ListIpPeer::getValidIP($this->container->get('request')->getClientIp());
		$userip = $this->container->get('request')->getClientIp();
		if(!is_null($ip_add)){
		$matchedip = $ip_add->getAllowedIp();
		}
    	else{
    		$matchedip = '';
    	}
				
				
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
         	'profileId' => $profileid,
         	'data' => $data,
         	'contacttype' => $contacttype,
         	'conEmailId' => $conEmailId,
         	'conMobileId' => $conMobileId,
         	'conTeleId' => $conTeleId,
         	'getDept' => $getDept,
         	'empnumber' => $empnumber,
         	'deptid' => $deptid,
         	'late' =>$late,
         	'overtime' => $overtime,
         	'timeflag' => $timeflag,
         	'currenttimein' => $currenttimein,
         	'currenttimeout' => $currenttimeout,
         	'firstchar' => $firstchar,
         	'matchedip' => $matchedip,
         	'userip' => $userip,
     		'checkipdata' => $checkipdata,

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


		$data = EmpLeaveQuery::create()
		->filterByStatus('Pending')
		->find()->count();

		$requestData = array();
		$requestGet = EmpLeavePeer::getAllLeave();
		

		//echo '<pre>';var_dump($requestGet);exit;		
		
		
       return $this->render('AdminBundle:Default:request.html.twig', array(
        	'name' => $name,
        	'page' => $page,
         	'role' => $role,
        	'user' => $user,
          	'timename' => $timename,
          	'data' => $data,
          	'allrequest' => $requestGet,
          	'userid' =>$id,
        	));
		}	
    }



    public function statusAcceptAction($id, $id2){
		$accept = EmpLeavePeer::retrieveByPk($id);
		if(isset($accept) && !empty($accept)) {
			$accept->setAdminId($id2);
			$accept->setStatus('Accepted');
			
			if($accept->save()){
				$response = array('response' => 'success');
			}else{
				$response = array('response' => 'not saved');
			}
			
		} else {
			$response = array('response' => 'not found');
		}
		
		$leavetype = $accept->getListLeaveTypeId();
		if($leavetype <= 3){

		}
		echo json_encode($response);
		exit;
    }


    public function profileTelUpdateAction($id, $id2)
    {
   
    	$datacontact = EmpContactPeer::retrieveByPk($id);
		$datacontact->setContact($id2);
		$datacontact->save();
		echo 1;
		exit;
    }

    public function profileCellUpdateAction($id, $id2)
    {
   
    	$datacontact = EmpContactPeer::retrieveByPk($id);
		$datacontact->setContact($id2);
		$datacontact->save();
		echo 1;
		exit;
    }

    public function profileAddressUpdateAction($id, $id2)
    {
   
    	$datacontact = EmpProfilePeer::retrieveByPk($id);
		$datacontact->setAddress($id2);
		$datacontact->save();
		echo 1;
		exit;
    }

    public function profileEmailUpdateAction($id, $id2)
    {
   		$user = $this->getUser();
    	$role = $user->getRole();

   		if((strcasecmp($role, 'employee') == 0)){
			return $this->redirect($this->generateUrl('profile_page'));
		}	
		else{
    	$datacontact = EmpContactPeer::retrieveByPk($id);
		$datacontact->setContact($id2);
		$datacontact->save();
		echo 1;
		exit;
		}
    }

    public function profileBdayUpdateAction($id, $id2)
    {
    	$user = $this->getUser();
    	$role = $user->getRole();

   		if((strcasecmp($role, 'employee') == 0)){
			return $this->redirect($this->generateUrl('profile_page'));
		}	
		else{
		$datacontact = EmpProfilePeer::retrieveByPk($id);
		$datacontact->setBday($id2);
		$datacontact->save();
		echo 1;
		exit;	
		}	
    }

    public function profileDeptUpdateAction($id, $id2)
    {
    	$user = $this->getUser();
    	$role = $user->getRole();

   		if((strcasecmp($role, 'employee') == 0)){
			return $this->redirect($this->generateUrl('profile_page'));
		}	
		else{
	
		$datacontact = EmpProfilePeer::retrieveByPk($id);
		$datacontact->setListDeptDeptId($id2);
		$datacontact->save();
		echo 1;
		exit;	
		}	
    }

    public function profileUpdateAction(Request $request){
   		$profileId = $request->request->get('profileId');
   		// $emailId = $request->request->get('emailId');
   		$telId = $request->request->get('teleId');
   		$mobileId = $request->request->get('mobileId');

   		$user = $this->getUser();
    	$role = $user->getRole();
   		
   		if((strcasecmp($role, 'employee') == 0)){
			return $this->redirect($this->generateUrl('profile_page'));
		}
		else{

	   		if(!empty($profileId)){
	   			$updateprofile = EmpProfilePeer::retrieveByPk($profileId);
		   		// $updateprofile->setEmployeeNumber($request->request->get('empnumber'));
		   		// $updateprofile->setBday($request->request->get('bday'));
		   		$updateprofile->setAddress($request->request->get('address'));
		   		// $updateprofile->setListDeptDeptId($request->request->get('dept'));
		   		$updateprofile->save();

		  //  		$updateemail = EmpContactPeer::retrieveByPk($emailId);
				// $updateemail->setContact($request->request->get('email'));
				// $updateemail->save();

				$updatetel = EmpContactPeer::retrieveByPk($telId);
				$updatetel->setContact($request->request->get('telephone'));
				$updatetel->save();

				$updatecell = EmpContactPeer::retrieveByPk($mobileId);
				$updatecell->setContact($request->request->get('cellphone'));
				$updatecell->save();

		   		$referer = $request->headers->get('referer');
		        return new RedirectResponse($referer);
	   		}else{
	   			$response = array('Missing Input');
				echo json_encode($response);
				exit;
	   		}
	   	}
   		
    }

    public function manageAction(){
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

		//echo '<pre>';var_dump($getEmployee);exit;

		$data = EmpLeaveQuery::create()
		->filterByStatus('Pending')
		->find()->count();
		return $this->render('AdminBundle:Default:manage.html.twig', array(
        	'name' => $name,
        	'page' => $page,
         	'role' => $role,
        	'user' => $user,
          	'timename' => $timename,
          	'data' => $data,
          	'getEmployee' => $getEmployee,
          	'getPos' => $getPos,
          	'getDept' => $getDept,
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

		    date_default_timezone_set('Asia/Manila');
		    $current_date = date('Y-m-d H:i:s');
	  		
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
			    	$addacc->save();
			    	$newacc = $addacc->getId();

			    	//add new profile
		    		$addemp = new EmpProfile();
			    	$addemp->setEmpAccAccId($newacc);
			    	$addemp->setEmployeeNumber($request->request->get('empnum'));
			    	$addemp->setFname($request->request->get('fname'));
			    	$addemp->setLname($request->request->get('lname'));
			    	$addemp->setAddress($request->request->get('address'));
			    	$addemp->setBday($request->request->get('bday'));
			    	$addemp->setDateJoined($current_date);
			    	$addemp->setListDeptDeptId($request->request->get('dept'));
			    	$addemp->setListPosPosId($request->request->get('pos'));
			    	$addemp->setStatus($request->request->get('status'));
			    	$addemp->save();
			    	$empid = $addemp->getId();

			    	$empcontact = new EmpContact();
			    	$empcontact->setEmpProfileId($empid);
			    	$empcontact->setListContTypesId(1);
			    	$empcontact->setContact($request->request->get('email'));
			    	$empcontact->save();

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

			    	return $this->redirect($this->generateUrl('manage_employee'));	
	    			}

	    	}else{
		    		$response = array('Missing Input');
					echo json_encode($response);
					exit;
		    	}
			}
    }

    public function sendRequestAction($value){

    	$user = $this->getUser();
    	$id = $user->getId();

		//employee profile information
		$data = EmpProfilePeer::getInformation($id);
		$name = $data->getFname(). " " .$data->getLname();
		$profileid = $data->getId();
		
		//employee contact information
		$datacontact = EmpContactPeer::getContact($profileid);		
		$conEmail = '';

		if(!is_null($datacontact)){
			for ($ct = 0; $ct < sizeof($datacontact); $ct++) {
    			// $contactArr[$ct] = $datacontact[$ct]->getContact(); 
				$contacttype =  ListContTypesPeer::getContactType($datacontact[$ct]->getListContTypesId())->getContactType();
				$contactvalue =  $datacontact[$ct]->getContact();
				if(strcasecmp($contacttype, 'email') == 0){
					$conEmail = $contactvalue;
				}
   			}
		}else{
			$conEmail = null;
		}

		$subject = 'Request for Access';
		$from = array($conEmail => $name);
		$to = array(
			 'christian.fallaria@searchoptmedia.com'  => 'Recipient1 Name',
		);

		$text = $value;
		// $html = "<em>You are about to <strong>end </strong>your shift in </em>seconds";

		$transport = Swift_SmtpTransport::newInstance('smtp.gmail.com', 465, 'ssl');
		$transport->setUsername('christian.fallaria@searchoptmedia.com');
		$transport->setPassword("baddonk123");
		$swift = Swift_Mailer::newInstance($transport);

		$message = new Swift_Message($subject);
		$message->setFrom($from);
		// $message->setBody($html, 'text/html');
		$message->setBody($text, 'text/plain');
		$message->setTo($to);
		$message->addPart($text, 'text/plain');

		if ($recipients = $swift->send($message, $failures))
		{
		 echo 1;
		} else {
		 echo 0;
		}    
		exit;
	}


	public function requestMeetingAction($id){
		$requestMeeting = new EmpLeave();
		date_default_timezone_set('Asia/Manila');
    	$current_date = date('Y-m-d H:i:s');
		$requestMeeting->setRequest($id);
		$requestMeeting->setStatus('Pending');
		$requestMeeting->setDateStarted($current_date);
		$requestMeeting->setDateEnded($current_date);
		$requestMeeting->setEmpAccId($this->getUser()->getId());
		$requestMeeting->setListLeaveTypeId(4);
		$requestMeeting->save();
		echo 1;
		exit;
	}
	public function requestLeaveAction($id, $id2, $id3){
		$leaveinput = new EmpLeave();
		$leaveinput->setRequest($id);
		$leaveinput->setStatus('Pending');
		$leaveinput->setDateStarted($id2);
		$leaveinput->setDateEnded($id3);
		$leaveinput->setEmpAccId($this->getUser()->getId());
		$leaveinput->setListLeaveTypeId(1);
		$leaveinput->save();	
		echo 1;
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

		$data = EmpLeaveQuery::create()
		->filterByStatus('Pending')
		->find()->count();

		$getRequests = EmpLeavePeer::getAllLeave();

		return $this->render('AdminBundle:Default:notif.html.twig', array(
        	'name' => $name,
        	'page' => $page,
         	'role' => $role,
        	'user' => $user,
          	'timename' => $timename,
          	'data' => $data,
          	'id' => $id,
          	'getRequests' => $getRequests, 
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
		}else{
			//employee profile information
		$data = EmpProfilePeer::getInformation($id);
		$empstatus = $data->getProfileStatus();

		if($empstatus == 0){

		$empid = $data->getId();
		$fname = $data->getFname();
		$lname = $data->getLname();
		$mname = $data->getMname();
		$bday = $data->getBday();
		$bday = date_format($bday, 'd/m/y');
		$address = $data->getAddress();
		$img = $data->getImgPath();
		$datejoined = $data->getDateJoined();
		$datejoined = date_format($datejoined, 'd/m/y');
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

		$data = EmpLeaveQuery::create()
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
        return $this->render('AdminBundle:Default:empprofile.html.twig', array(
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
         	'profileId' => $profileid,
         	'data' => $data,
         	'contacttype' => $contacttype,
         	'conEmailId' => $conEmailId,
         	'conMobileId' => $conMobileId,
         	'conTeleId' => $conTeleId,
         	'getDept' => $getDept,
         	'empnumber' => $empnumber,
         	'deptid' => $deptid,
         	'empid' => $empid,
         	'late' => $late,
        	));
			}else{
			$response = array('Invalid Profile');
			echo json_encode($response);
			exit;
			}
		}
			
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

    public function declinedRequestAction($id, $id2, $id3){
    $declined = EmpLeavePeer::retrieveByPk($id);
		if(isset($declined) && !empty($declined)) {
			$declined->setAdminId($id2);
			$declined->setStatus('Declined');
			
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
    	$empip = $this->container->get('request')->getClientIp();
    	$acceptrequest = new ListIp();
    	$acceptrequest->setAllowedIp($empip);
    	$acceptrequest->setStatus('Active');
    	$acceptrequest->save();
    	echo 1;
    	exit;
    }
//end
}
