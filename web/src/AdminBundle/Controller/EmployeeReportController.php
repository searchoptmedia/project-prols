<?php
/**
 * Created by PhpStorm.
 * User: Propelrr-AJ
 * Date: 08/08/16
 * Time: 1:10 PM
 */

namespace AdminBundle\Controller;

use CoreBundle\Model\EmpAccPeer;
use CoreBundle\Model\EmpProfilePeer;
use CoreBundle\Model\EmpRequestPeer;
use CoreBundle\Model\EmpTimePeer;
use CoreBundle\Model\ListDeptPeer;
use CoreBundle\Model\ListEventsPeer;
use CoreBundle\Model\ListPosPeer;
use CoreBundle\Utilities\Constant;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
//use Symfony\Component\BrowserKit\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\HttpFoundation\Request;


class EmployeeReportController extends Controller 
{

    public function getRecord()
    {
        $c = new \Criteria();
        $deptid = $_REQUEST['deptid'];
        $empname = $_REQUEST['empname'];
        $startdateinput = $_REQUEST['start'];
        $enddateinput = $_REQUEST['end'];

        $startdate = date('Y-m-d', strtotime($startdateinput));
        $enddate = date('Y-m-d', strtotime($enddateinput));


        if($startdateinput == 'Invalid Date' || $enddateinput == 'Invalid Date')
        {
            $c->addAscendingOrderByColumn(EmpTimePeer::DATE);
            $results = EmpTimePeer::getEmployeeTimes($c);
            if($deptid != 'null')
            {
                $c->add(EmpProfilePeer::LIST_DEPT_ID, $deptid, \Criteria::EQUAL);
                $c->addAscendingOrderByColumn(EmpTimePeer::DATE);
                $results = EmpTimePeer::getEmployeeTimes($c);
            }
            if($empname != 'null')
            {
                $c->add(EmpTimePeer::EMP_ACC_ACC_ID, $empname, \Criteria::EQUAL);
                $c->addAscendingOrderByColumn(EmpTimePeer::DATE);
                $results = EmpTimePeer::getEmployeeTimes($c);
            }
            return $results;
        }
        if($empname != 'null')
        {
            $c->add(EmpTimePeer::EMP_ACC_ACC_ID, $empname, \Criteria::EQUAL);
            $c->add(EmpTimePeer::DATE, $startdate, \Criteria::GREATER_EQUAL);
            $c->addAnd(EmpTimePeer::DATE, $enddate, \Criteria::LESS_EQUAL);
            $c->addAscendingOrderByColumn(EmpTimePeer::DATE);
            $results = EmpTimePeer::getEmployeeTimes($c);
            return $results;
        }

//        $c->addAscendingOrderByColumn($startdate, $enddate, \Criteria::GREATER_THAN);
        if($deptid == 'null')
        {

//          $results = EmpTimePeer::getEmployeeTimes();
            $c->add(EmpTimePeer::DATE, $startdate, \Criteria::GREATER_EQUAL);
            $c->addAnd(EmpTimePeer::DATE, $enddate, \Criteria::LESS_EQUAL);
            $c->addAscendingOrderByColumn(EmpTimePeer::DATE);
            $results = EmpTimePeer::getEmployeeTimes($c);
        }else
        {
            $c->add(EmpProfilePeer::LIST_DEPT_ID, $deptid, \Criteria::EQUAL);
            $c->add(EmpTimePeer::DATE, $startdate, \Criteria::GREATER_EQUAL);
            $c->addAnd(EmpTimePeer::DATE, $enddate, \Criteria::LESS_EQUAL);
            $c->addAscendingOrderByColumn(EmpTimePeer::DATE);
            $results = EmpTimePeer::getEmployeeTimes($c);
        }
        return $results;
    }

    /**
     * Generate employee time report
     * @return StreamedResponse
     */
    public function generateReportAction(Request $req)
    {
        $response = new StreamedResponse();
        $response->setCallback(function()
        {
            $handle = fopen('php://output', 'w+');
            // Add the header of the CSV file

            fputcsv($handle, array('Employee ID', 'Name', 'Time in', 'Time out', 'Date', 'Work in Office', 'Total hours (decimal)', 'Overtime', 'Undertime'));

            $records = $this->getRecord();
            foreach($records as $emp) 
            {
                $status = $emp->getStatus();
                if($status >= 0) {
                    $empid = $emp->getEmpAccAccId();
                    $timeindata = $emp->getTimeIn()->format('h:i A');
                    $timeoutdata = is_null($emp->getTimeOut()) ? "" : $emp->getTimeOut()->format('h:i A');

                    $date = $emp->getDate()->format('m/d/Y');

                    $dateday = $emp->getDate()->format('D');
                    $isOffice = $emp->getCheckIp() ? 'Yes' : 'No';
                    $undertime = 0;
                    //record
                    $profile = EmpProfilePeer::getInformation($empid);
                    $fname = $profile->getFname();
                    $lname = $profile->getLname();
                    $empnum = $profile->getEmployeeNumber();

                    $totalHours = "N/A";
                    $totalHoursDec = "N/A";
                    $overtime = 0;

                    if (!empty($timeoutdata)) {
                        $in = new \DateTime($emp->getTimeIn()->format('Y-m-d H:i:s'));
                        $out = new \DateTime($emp->getTimeOut()->format('Y-m-d H:i:s'));
                        $manhours = date_diff($out, $in);

                        //compute duration


                        $totalHours = $manhours->format('%h') . ':' . $manhours->format('%i');

                        $h = $manhours->format('%h');
                        $i = intval($manhours->format('%i'));
                        $i = $i > 0 ? ($i / 60) : 0;
                        $totalHoursDec = number_format($h + $i, 2);

                        if ($totalHoursDec > 9) {
                            $overtime = $totalHoursDec - 9;
                        }
                        if ($dateday == 'Sat' || $dateday == 'Sun') {
                            $overtime = $totalHoursDec;

//                        $totalHours = 0;
//                        $totalHoursDec = 0;

                        }
                        if ($overtime < 1) {
                            $overtime = 0;
                        }
                        if ($totalHours < 9) {
                            $undertime = 1;
                        }

//                    $totalHoursDec = $emp->getManhours();
//                    $overtime = $emp->getOvertime();
                    }
                    $isUndertime = $undertime ? 'Yes' : 'No';
                    fputcsv($handle, // The file pointer
                        array("EMP-" . $empnum, $lname . ", " . $fname, $timeindata, $timeoutdata, $date, $isOffice, $totalHoursDec, $overtime, $isUndertime)
                    );
                }
            }
            fputcsv($handle, array(' '));
            fputcsv($handle, array(' '));
            fputcsv($handle, array('*********** Note: Please take note that declined out of the office logs were not included on the list above.'));
            exit;

            fclose($handle);
        });
        $filedate = date('m/d/Y');
        $response->setStatusCode(200);
        $response->headers->set('Content-Type', 'text/csv; charset=utf-8');
        $response->headers->set('Content-Disposition', 'attachment; filename="'.$filedate.'-employee_time_export.csv"');
        return $response;
    }

    /**
     * Generate employee absences
     * @param Request $req
     * @return StreamedResponse
     */
    function generateLeaveAbsencesReportAction(Request $req)
    {
        $response = new StreamedResponse();
        $response->setCallback(function()
        {
            $handle = fopen('php://output', 'w+');
            // Add the header of the CSV file
            $empid = $_REQUEST['empid'];
            $startdateinput = $_REQUEST['start'];
            $enddateinput   = $_REQUEST['end'];

            fputcsv($handle, array('Employee ID', 'Name', 'Day', 'Date', 'Status'));

            if($empid == 'all') {
                $employees = EmpAccPeer::getAllUser();
            } else {
                $employees = EmpAccPeer::getAccList($empid);
            }

            $empProfile = array();
            foreach($employees as $e) {
                $empProfile[$e->getId()] = EmpProfilePeer::getInformation($e->getId());
            }

            foreach($employees as $e) {
                $empRecord = $empProfile[$e->getId()];
                $startdate = $startDateCopy = strtotime($startdateinput);
                $enddate = strtotime($enddateinput);

                while ($startdate <= $enddate) {
                    $date = date('Y-m-d', $startdate);
                    $showDate = date('m/d/Y', $startdate);
                    $day = date('D', $startdate);

                    $timeData = EmpTimePeer::getOneByDate($date, $e->getId());
                    $request = EmpRequestPeer::getEmpByTime($date, $e->getId());

                    //step 1: check if holiday
                    $holiday = ListEventsPeer::getOneByDate($date);

                    if($holiday) {
                        fputcsv($handle, array('EMP-' . $empRecord->getEmployeeNumber(), $empRecord->getFname() . ' ' . $empRecord->getLname(), $day, $showDate, 'Holiday'));
                    } else {

                        //step 2: check if weekend, do not include
                        if ($day != 'Sun' && $day != 'Sat') {

                            if ($request && in_array($request->getListRequestTypeId(), array(Constant::REQUEST_VLEAVE, Constant::REQUEST_SLEAVE))) {
                                $init = new InitController();
                                $leaveType = $request->getListRequestType()->getRequestType();

                                if ($timeData) {
                                    $timeOut = $timeData->getTimeOut();
                                    $timeIn = $timeData->getTimeIn()->format('Y-m-d H:i:s');

                                    if (!is_null($timeOut) && !empty($timeOut) && $timeOut) {
                                        $totalHours = $init->computeHours($timeIn, $timeOut->format('Y-m-d H:i:s'), $timeData->getDate()->format('D'), 'total_hours');
                                        if ($totalHours > 3) {
                                            $leaveType = $timeData->getTimeIn()->format('m-d-Y') . ' Half-day with ' . $leaveType;
                                        }
                                    }
                                }

                                fputcsv($handle, array('EMP-' . $empRecord->getEmployeeNumber(), $empRecord->getFname() . ' ' . $empRecord->getLname(), $day, $showDate, $leaveType));
                            } else if (!$timeData) {
                                fputcsv($handle, array('EMP-' . $empRecord->getEmployeeNumber(), $empRecord->getFname() . ' ' . $empRecord->getLname(), $day, $showDate, 'Absent'));
                            }
                        }
                    }

                    $timeData = null;
                    $startdate = strtotime("+1 day", $startdate);
                }
            }

            fputcsv($handle, array(' '));
            fputcsv($handle, array(' '));/*date('m/d/Y', $startDateCopy)  date('m/d/Y', $enddate)*/
            fputcsv($handle, array('The login system was launched on August 02, 2016.'));
            fputcsv($handle, array('Date From: ' .  $startdateinput. ' | To: ' . $enddateinput));
            fputcsv($handle, array('*********** Weekends are not included on the list.'));
            exit;

            fclose($handle);
        });
        $filedate           = date('d-m-y');
        $response->setStatusCode(200);
        $response->headers->set('Content-Type', 'text/csv; charset=utf-8');
        $response->headers->set('Content-Disposition', 'attachment; filename="'.$filedate.'-employee_leave_absences_export.csv"');
        return $response;
    }

    /**
     * Get employee record
     * @return array
     */
    public function getEmployeeRecord()
    {
        $c = new \Criteria();
        $deptid = $_REQUEST['deptid'];

        if($deptid == 'null')
        {
            $c->addAscendingOrderByColumn(EmpProfilePeer::ID);
            $results = EmpProfilePeer::getEmployeeList($c);
        }
        else
        {
            $c->add(EmpProfilePeer::LIST_DEPT_ID, $deptid, \Criteria::EQUAL);
            $c->addAscendingOrderByColumn(EmpProfilePeer::ID);
            $results = EmpProfilePeer::getEmployeeList($c);
        }
        return $results;
    }

    /**
     * Generate Employee List Report
     * @param Request $req
     * @return StreamedResponse
     */
    public function generateEmployeeReportAction(Request $req)
    {
        $response = new StreamedResponse();
        $response->setCallback(function()
        {
            $handle = fopen('php://output', 'w+');
            // Add the header of the CSV file

            fputcsv($handle, array('Employee ID', 'Name', 'Address', 'Birthday', 'Email', 'Department', 'Position', 'SSS', 'BIR', 'PhilHealth'));

            $records = $this->getEmployeeRecord();
            foreach($records as $emp)
            {
                $empid  = $emp->getEmpAccAccId();
                $empacc = EmpAccPeer::getAcc($empid);
                $empProfile = EmpProfilePeer::getInformation($empid);

                $email  = $empacc->getEmail();
                $fname  = $emp->getFname();
                $lname  = $emp->getLname();
                $empnum = $emp->getEmployeeNumber();
                $bday   = $emp->getBday()->format('M d Y');
                $deptid = $emp->getListDeptDeptId();
                $posid  = $emp->getListPosPosId();
                $address = $emp->getAddress();
                $sss = $empProfile->getSss();
                $bir = $empProfile->getBir();
                $philhealth = $empProfile->getPhilhealth();

                $getdept   = ListDeptPeer::getDept($deptid);
                $getpos  = ListPosPeer::getPos($posid);

                $dept = $getdept->getDeptNames();
                $pos = $getpos->getPosNames();

                fputcsv($handle, // The file pointer
                    array("EMP-" . $empnum,  $lname . ", " . $fname, $address, $bday, $email, $dept, $pos, $sss, $bir, $philhealth)
                );
            }
            exit;

            fclose($handle);
        });
        $filedate  = date('m/d/Y');
        $response->setStatusCode(200);
        $response->headers->set('Content-Type', 'text/csv; charset=utf-8');
        $response->headers->set('Content-Disposition', 'attachment; filename="'.$filedate.'-employee_list_export.csv"');
        return $response;
    }

}