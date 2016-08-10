<?php
/**
 * Created by PhpStorm.
 * User: Propelrr-AJ
 * Date: 08/08/16
 * Time: 1:10 PM
 */

namespace AdminBundle\Controller;

use CoreBundle\Model\EmpProfilePeer;
use CoreBundle\Model\EmpTimePeer;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
//use Symfony\Component\BrowserKit\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\HttpFoundation\Request;

class EmployeeReportController extends Controller {

    public function getRecord()
    {
        $c = new \Criteria();
        $deptid = $_REQUEST['deptid'];

        $startdateinput = $_REQUEST['start'];
        $enddateinput = $_REQUEST['end'];

        $startdate = date('Y-m-d', strtotime($startdateinput));
        $enddate = date('Y-m-d', strtotime($enddateinput));

        $c->addAscendingOrderByColumn($startdate, $enddate, \Criteria::GREATER_THAN);
        if($deptid == 'null'){
            $results = EmpTimePeer::getEmployeeTimes();
        }else{
            $c->add(EmpProfilePeer::LIST_DEPT_ID, $deptid, \Criteria::EQUAL);
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
        $response->setCallback(function() {
            $handle = fopen('php://output', 'w+');
            // Add the header of the CSV file
            fputcsv($handle, array('Employee ID', 'Name', 'Time in', 'Time out', 'Date', 'Work in Office', 'Total hours (time)', 'Total hours (decimal)', 'Overtime'));
            $records = $this->getRecord();
            foreach($records as $emp) {
                $empid          = $emp->getEmpAccAccId();
                $timeindata     = $emp->getTimeIn()->format('h:i A');
                $timeoutdata    = is_null($emp->getTimeOut()) ? "" : $emp->getTimeOut()->format('h:i A');
                $date           = $emp->getDate()->format('d/m/Y');
                $dateday        = $emp->getDate()->format('D');
                $isOffice       = $emp->getCheckIp() ? 'Yes':'No';

                //record
                $profile = EmpProfilePeer::getInformation($empid);
                $fname   = $profile->getFname();
                $lname   = $profile->getLname();
                $empnum  = $profile->getEmployeeNumber();

                $totalHours = "n/a";
                $totalHoursDec = "n/a";
                $overtime = 0;

                if(! empty($timeoutdata)) {
                    $in = new \DateTime($emp->getTimeIn()->format('Y-m-d H:i:s'));
                    $out = new \DateTime($emp->getTimeOut()->format('Y-m-d H:i:s'));
                    $manhours = date_diff($out, $in);

                    $totalHours = $manhours->format('%h') . ':' . $manhours->format('%i');

                    $h = $manhours->format('%h');
                    $i = intval($manhours->format('%i'));
                    $i = $i > 0 ? ($i/60) : 0;
                    $totalHoursDec = number_format($h + $i, 2);

                    if($totalHoursDec > 9) {
                        $overtime = $totalHoursDec - 9;
                    }
                    if($dateday == 'Sat' || $dateday == 'Sun'){
                        $overtime = $totalHoursDec;
                        $totalHours = 0;
                        $totalHoursDec = 0;
                    }
                    if($overtime < 1){
                        $overtime = 0;
                    }
//                    $totalHoursDec = $emp->getManhours();
//                    $overtime = $emp->getOvertime();
                }

                fputcsv($handle, // The file pointer
                    array("EMP-" . $empnum,  $lname . ", " . $fname, $timeindata, $timeoutdata, $date, $isOffice, $totalHours, $totalHoursDec, $overtime)
                );
            }
            exit;

            fclose($handle);
        });

        $response->setStatusCode(200);
        $response->headers->set('Content-Type', 'text/csv; charset=utf-8');
        $response->headers->set('Content-Disposition', 'attachment; filename="employee_export.csv"');
        return $response;
    }

}