<?php

namespace AdminBundle\Controller;

use CoreBundle\Model\EmpAccQuery;
use CoreBundle\Model\EmpCapabilities;
use CoreBundle\Model\EmpProfile;
use CoreBundle\Model\EmpProfileQuery;
use CoreBundle\Model\EmpRequestQuery;
use CoreBundle\Model\EmpStatusTypePeer;
use CoreBundle\Model\ListDeptPeer;
use CoreBundle\Model\ListPosPeer;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;

use CoreBundle\Model\EmpProfilePeer;
use CoreBundle\Model\EmpContactPeer;
use CoreBundle\Model\EmpContact;
use CoreBundle\Model\EmpTimePeer;
use CoreBundle\Model\ListIpPeer;
use CoreBundle\Model\EmpAccPeer;
use CoreBundle\Model\EmpTime;
use CoreBundle\Model\EmpRequestPeer;
use CoreBundle\Model\EmpTimeReject;
use CoreBundle\Model\RequestMeetingsTag;
use CoreBundle\Model\RequestMeetingsTagPeer;
use CoreBundle\Model\RequestMeetingsTagsPeer;
use CoreBundle\Model\ListIp;

class AdminController extends Controller
{
    public function indexAction(Request $request)
    {
        $user = $this->getUser();
        $id = $user->getId();

        $timename = self::timeInOut($id);
        $overtime = 0;
        $timedata = EmpTimePeer::getTime($id);
        $currenttimein = 0;
        $currenttimeout = 0;
        $timeflag = 0;
        $et = EmpTimePeer::getEmpLastTimein($id);
        $checkipdata = null;
        $datetoday = date('Y-m-d');

        //get last timed in
        if(!empty($et)) {
            $currentTimeInDate = $et->getDate('Y-m-d');

            if($datetoday == $currentTimeInDate) {
                $currenttimein = $et->getTimeIn()->format('h:i A');
                $currenttimeout = $et->getTimeOut();

                if (!empty($currenttimeout)) {
                    $currenttimeout = $currenttimeout->format('h:i A');
                }
            }
        }

//        for ($ctr = 0; $ctr < sizeof($timedata); $ctr++) {
//            $checktimein = $timedata[$ctr]->getTimeIn();
//            $checktimeout = $timedata[$ctr]->getTimeOut();
//
//            if(!is_null($checktimein) && is_null($checktimeout)) {
//                $currenttimein = $checktimein->format('h:i A');
//            } else {
//                $currenttimein = 0;
//                $currenttimeout = $checktimeout->format('h:i A');
//            }
//        }

        //check if already timed in today
        if(!empty($timedata)) {
            $overtime = date('h:i A',strtotime('+9 hours',strtotime($currenttimein)));
            $datetoday = date('Y-m-d');
            $emp_time = EmpTimePeer::getTime($id);
            $currenttime = sizeof($emp_time) - 1;
            $timein_data = $emp_time[$currenttime]->getTimeIn();
            $timeout_data = $emp_time[$currenttime]->getTimeOut();
            $checkipdata = $emp_time[$currenttime]->getCheckIp();
        }

        if(!empty($et)) {
            $emptimedate = $et->getDate();
            $empTimeout  = $et->getTimeOut();
            $lasttimein	= $et->getTimeIn()->format('M d, Y, h:i A');
            if($emptimedate->format('Y-m-d') == $datetoday) {
                $timeflag = 1;
            }

            if(! empty($empTimeout)) $isTimeOut = 'true';
        } else {
            $isTimeOut = 'true';
        }

        $systime = date('H:i A');
        $timetoday = date('h:i A');
        $afternoon = date('H:i A', strtotime('12 pm'));

        //counts number of pending requests
        $requestcount = EmpRequestQuery::_getTotalByStatusRequest(2);

        $userip     = InitController::getUserIP($this);
        $ip_add     = ListIpPeer::getValidIP($userip);
        $matchedIp  = !empty($ip_add) ? $ip_add->getAllowedIp() : null;
        $ip_checker = ($userip == $matchedIp) ? 1 : 0;

        $timedintoday   = EmpTimePeer::getAllTimeToday($datetoday);
        $allusers       = EmpProfilePeer::getAllProfile();
        $allacc         = EmpAccPeer::getAllUser();
        $userbdaynames  = array();

        foreach($allusers as $u) {
            $bday = $u->getBday()->format('m-d');

            if($bday == date('m-d')) {
                $userbdaynames[] = $u->getFname();
            }
        }

        return $this->render('AdminBundle:Default:index.html.twig', array(
            'userbdaynames' => $userbdaynames,
            'user' => $user,
            'timename' => $timename,
            'currenttimein' => $currenttimein,
            'currenttimeout' => $currenttimeout,
            'overtime' => $overtime,
            'timeflag' => $timeflag,
            'systime' => $systime,
            'afternoon' => $afternoon,
            'userip' => $userip,
            'matchedip' => $matchedIp,
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

    public function timeInAction(Request $request, $id, $passw)
    {

        date_default_timezone_set('Asia/Manila');

        //check session active
        $user = $this->getUser();
        if(empty($user))
        {
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

        if(!empty($emptime))
        {
            $currenttime = sizeof($emptime) - 1;
            $timein_data = $emptime[$currenttime]->getTimeIn();
            $timeout_data = $emptime[$currenttime]->getTimeOut();
            $id_data = $emptime[$currenttime]->getId();

            for ($ctr = 0; $ctr < sizeof($timedata); $ctr++)
            {
                $checkdate = $timedata[$ctr]->getDate();

                if($checkdate->format('Y-m-d') == $datetoday && !is_null($timeout_data))
                {
                    $timeflag = 1;
                }elseif($checkdate->format('Y-m-d') == $datetoday && is_null($timeout_data))
                {
                    $timeflag = 0;
                }elseif($checkdate->format('Y-m-d') != $datetoday)
                {
                    $timeflag = 0;
                }

            }
        }

        if($timeflag == 0)
        {
            //set employee time
            $empTimeSave = new EmpTime();

            //employee time in/out
            $emp_time = EmpTimePeer::getTime($id);
            if(isset($emp_time) && !empty($emp_time))
            {
                $currenttime = sizeof($emp_time) - 1;
                $timein_data = $emp_time[$currenttime]->getTimeIn();
                $timeout_data = $emp_time[$currenttime]->getTimeOut();
                $id_data = $emp_time[$currenttime]->getId();
                if(!is_null($timein_data) && !is_null($timeout_data))
                {
                    $ip_add = ListIpPeer::getAllIp();
                    $stat = 'WORKING 1';
                    $retval = 1;
                    $empTimeSave->setTimeIn($datetimetoday);
                    $empTimeSave->setIpAdd($matchedip);
                    $empTimeSave->setDate($datetoday);
                    $empTimeSave->setEmpAccAccId($this->getUser()->getId());
                    for($ctr = 0; $ctr < sizeof($ip_add); $ctr++)
                    {
                        $allowedip = $ip_add[$ctr]->getAllowedIp();
                        if($allowedip == $matchedip)
                        {
                            $empTimeSave->setCheckIp(1);
                        }else
                        {
                            $empTimeSave->setCheckIp(0);
                            // $mailer = new Mailer();
                            // $send = $mailer->sendOutOfOfficeEmailToAdmin();

                        }
                    }
                    if($empTimeSave->save())
                    {
                        $is_message = $request->request->get('is_message');
                        if(!is_null($is_message))
                        {
                            $email = new EmailController();
                            $sendemail = $email->sendTimeInRequest($request, $this);

                            if(! $sendemail)
                            {
                                //if error sending email
                                $retval = 5;
                            }
                            else
                            {
                                echo 1;
                                exit;
                            }
                        }
                    }
                    echo $retval;
                    exit;
                }
                else if(!is_null($timein_data) && is_null($timeout_data)){
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
            }else
            {
                $ip_add = ListIpPeer::getAllIp();
                $stat = 'WORKING 3';
                $retval = 1;
                $empTimeSave->setTimeIn($datetimetoday);
                $empTimeSave->setIpAdd($matchedip);
                $empTimeSave->setDate($datetoday);
                $empTimeSave->setEmpAccAccId($this->getUser()->getId());

                for($ctr = 0; $ctr < sizeof($ip_add); $ctr++)
                {
                    $allowedip = $ip_add[$ctr]->getAllowedIp();
                    if($allowedip == $matchedip)
                    {
                        $empTimeSave->setCheckIp(1);
                    }
                    else
                    {
                        $empTimeSave->setCheckIp(0);
                    }
                }

                if($empTimeSave->save())
                {
                    $is_message = $request->request->get('is_message');
                    if(!is_null($is_message))
                    {
                        $email = new EmailController();
                        $sendemail = $email->sendTimeInRequest($request, $this);

                        if(! $sendemail)
                        {
                            //if error sending email
                            $retval = 5;
                        }
                        else
                        {
                            echo 1;
                            exit;
                        }
                    }
                }
                echo $retval;
            }

        }
        else
        {
            $retval = 3;
            echo $retval;
        }
        exit;
    }

    public function adminEditProfileAction(Request $request)
    {
        $user = $this->getUser();
        $id = $user->getId();

        $response = array('result' => 'Profile Update Successful');
        $changes = array();
        $email = new EmailController();

        $empid = $request->request->get('empid');
        $telId = $request->request->get('telnumid');
        $mobileId = $request->request->get('celnumid');
        $empNum = $request->request->get('empnumber');
        $empFname = $request->request->get('fnameinput');
        $empLname = $request->request->get('lnameinput');
        $empMname = $request->request->get('mnameinput');
        $empGender = $request->request->get('genderinput');
        $empAddress = $request->request->get('addressinput');
        $empBday = $request->request->get('bdayinput');
        $empDept = $request->request->get('departmentinput');
        $empPos = $request->request->get('positioninput');
        $empStatus = $request->request->get('statusinput');
        $empEmail = $request->request->get('emailinput');
        $empTelNum = $request->request->get('telnuminput');
        $empCelNum = $request->request->get('celnuminput');
        $sssId = $request->request->get('sssinput');
        $bir = $request->request->get('birinput');
        $philhealth = $request->request->get('philhealthinput');

        $updateAcc = EmpAccPeer::getAcc($empid);
        //check changes
        if($updateAcc->getEmail()!=$empEmail) {
            $changes['Email Address'] = $empEmail;
        }

        $updateAcc->setEmail($empEmail);
        $updateAcc->setLastUpdatedBy($id);
        $updateAcc->save();

        $updateprofile = EmpProfilePeer::getInformation($empid);
        //check changes
        if($updateprofile->getFname()!=$empFname) {
            $changes['First Name'] = $empFname;
        }
        if($updateprofile->getLname()!=$empLname) {
            $changes['Last Name'] = $empLname;
        }
        if($updateprofile->getMname()!=$empMname) {
            $changes['Middle Name'] = $empMname;
        }
        if($updateprofile->getGender()!=$empGender) {
            $changes['Gender'] = $empGender;
        }
        if($updateprofile->getEmployeeNumber()!=$empNum) {
            $changes['Employee Number'] = $empNum;
        }
        if($updateprofile->getAddress()!=$empAddress) {
            $changes['Address'] = $empAddress;
        }
        if($updateprofile->getBday()->format('Y-m-d')!=$empBday) {
            $changes['Birthday'] = $empBday;
        }
        if($updateprofile->getSss()!=$sssId) {
            $changes['SSS'] = $sssId;
        }
        if($updateprofile->getBir()!=$bir) {
            $changes['BIR'] = $bir;
        }
        if($updateprofile->getPhilhealth()!=$philhealth) {
            $changes['Philhealth'] = $philhealth;
        }
        if($updateprofile->getStatus()!=$empStatus) {
            $statRec = EmpStatusTypePeer::retrieveByPK($empStatus);
            if($statRec) $changes['Department'] = $statRec->getName();
        }
        if($updateprofile->getListDeptDeptId()!=$empDept) {
            $deptRec = ListDeptPeer::retrieveByPK($empDept);
            if($deptRec) $changes['Department'] = $deptRec->getDeptNames();
        }
        if($updateprofile->getListPosPosId()!=$empPos) {
            $posRec = ListPosPeer::retrieveByPK($empPos);
            if($posRec) $changes['Department'] = $posRec->getPosNames();
        }

        if(count($changes))
            $sendemail = $email->adminEditEmployeeProfileEmail($request, $this, $changes);

        $updateprofile->setFname($empFname);
        $updateprofile->setLname($empLname);
        $updateprofile->setMname($empMname);
        $updateprofile->setGender($empGender);
        $updateprofile->setEmployeeNumber($empNum);
        $updateprofile->setAddress($empAddress);
        $updateprofile->setBday($empBday);
        $updateprofile->setListDeptDeptId($empDept);
        $updateprofile->setListPosPosId($empPos);
        $updateprofile->setStatus($empStatus);
        $updateprofile->setSss($sssId);
        $updateprofile->setBir($bir);
        $updateprofile->setPhilhealth($philhealth);
        $updateprofile->save();

        $profileId = $updateprofile->getId();

        $updatetel = EmpContactPeer::retrieveByPk($telId);
        if(empty($updatetel)){
            $newtel = new EmpContact();
            $newtel->setContact($empTelNum);
            $newtel->setEmpProfileId($profileId);
            $newtel->setListContTypesId(3);
            $newtel->save();
        }else{
            $updatetel->setContact($empTelNum);
            $updatetel->save();
        }
        $updatecell = EmpContactPeer::retrieveByPk($mobileId);
        if(empty($updatecell)){
            $newcel = new EmpContact();
            $newcel->setContact($empCelNum);
            $newcel->setEmpProfileId($profileId);
            $newcel->setListContTypesId(2);
            $newcel->save();
        }else {
            $updatecell->setContact($empCelNum);
            $updatecell->save();
        }

        $profile = EmpAccQuery::create()->findPk($empid);
        $capabilities = $profile->getEmpCapabilitiessJoinCapabilitiesList();
        foreach ($capabilities as $cap) {
            $cap->delete();
        }

        $capabilities = $request->request->get('capabilities');
        if($capabilities != '') {
            foreach ($capabilities as $cap) {
                $empcap = new EmpCapabilities();
                $empcap->setEmpId($empid);
                $empcap->setCapId($cap);
                $empcap->save();
            }
        }

        echo json_encode($response);
        exit;

//        $response = array('Update Successful' => 'success');
//        $email = new EmailController();
//        $sendemail = $email->adminEditEmployeeProfileEmail($request, $this);
//        echo json_encode($response);
//        exit;
    }

    public function timeInOut($id)
    {
        //time in/out information
        $datatime = EmpTimePeer::getTime($id);
        $timename = '';

        if(isset($datatime) && !empty($datatime))
        {
            $currenttime = sizeof($datatime) - 1;
            $timein_data = $datatime[$currenttime]->getTimeIn();
            $timeout_data = $datatime[$currenttime]->getTimeOut();
            if(!is_null($timein_data) && !is_null($timeout_data))
            {
                // echo 'WORKING 1';
                $timename = true;
            }else if(!is_null($timein_data) && is_null($timeout_data))
            {
                // echo 'WORKING 2';
                $timename = false;
            }
        }
        else if(isset($datatime) && empty($datatime))
        {
            // echo 'WORKING 3';
            $timename = true;
        }
        return $timename;
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

    public function declinedRequestAction(Request $req)
    {
        $declined = EmpRequestPeer::retrieveByPk($req->request->get('leaveId'));
        if(isset($declined) && !empty($declined))
        {
            $requesttype = $declined->getListRequestTypeId();
            if($requesttype == 3)
            {
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

            if($declined->save())
            {
                $response = array('response' => 'success');
            }else
            {
                $response = array('response' => 'not saved');
            }
        }
        else
        {
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
}
