<?php

namespace CoreBundle\Utilities;

use CoreBundle\Model\BiometricPendingLogsQuery;
use CoreBundle\Model\BiometricProcessedLogs;
use CoreBundle\Model\BiometricProcessedLogsQuery;
use CoreBundle\Model\EmpProfileQuery;
use CoreBundle\Model\EmpTime;
use CoreBundle\Model\EmpTimeQuery;

class Biometrics
{
	protected $_biometricProcessedLogs;
	protected $_biometricPendingLogs;

	public function __construct()
	{
		$this->_biometricProcessedLogs = BiometricProcessedLogsQuery::create();
		$this->_biometricPendingLogs = BiometricPendingLogsQuery::create()->toArray();
	}

	private function decodeDate($datestring = "")
	{
		$datestringArray = str_split($datestring,2);
		return
			array(
				'month' => (int)$datestringArray[0],
				'day' => (int)$datestringArray[1],
				'year' => (int)$datestringArray[2]+2000
			);
	}

	private function decodeTime($timeinteger = "")
	{
		$timestringArray = str_split($timeinteger,2);
		return
			array(
				'hour' => (int)$timestringArray[0],
				'minute' => (int)$timestringArray[1],
				'second' => (int)$timestringArray[2]
			);
	}

	private function decodeEmp($employeeNumber = "")
	{
		$employeeNumberArray = explode('-',$employeeNumber);
		$processedEmployeeNumber = $employeeNumberArray[0].$employeeNumberArray[1];
		$currentEmployee = EmpProfileQuery::create()
			->findOneByEmployeeNumber($processedEmployeeNumber);
		return $currentEmployee;
	}


	public function updateLogs()
	{
		if(count($this->_biometricPendingLogs) != 0)
		{
			$pendingLogIds = array();
			foreach($this->_biometricPendingLogs as $pendingLog)
			{
				$logDate = $this->decodeDate($pendingLog['C_Date']);
				$logTime = $this->decodeTime($pendingLog['C_Time']);
				$employee = $this->decodeEmp($pendingLog['L_UID']);
				if($employee != null)
				{
					$processedLogId = 0;
					$newDateTime = date('m/d/Y h:i:s a', time());
					$newDateTime = date_date_set(
						$newDateTime,
						$logDate['year'],
						$logDate['month'],
						$logDate['day']);
					$currentDate = $newDateTime;
					$newDateTime = date_time_set(
						$newDateTime,
						$logTime['hour'],
						$logTime['minute'],
						$logTime['second']);
					$noTimeoutLog = EmpTimeQuery::create()
						->filterByDate($currentDate)
						->filterByEmpAccAccId($employee->getEmpAccAccId())
						->findOneByTimeOut(NULL,\Criteria::EQUAL);
					if($noTimeoutLog == null)
					{
						$freshLog = new EmpTime();
						$freshLog
							->setEmpAccAccId($employee->getEmpAccAccId())
							->setDate($currentDate)
							->setTimeIn($newDateTime)
							->save();
						$processedLogId = $freshLog
							->getId();
					}
					$processedLog = new BiometricProcessedLogs();
					$processedLog
						->setC_Date($logDate)
						->setC_Time($logTime)
						->setemp_time_id($processedLogId)
						->save();
					array_push($pendingLogIds,$pendingLog['id']);
				}
			}
			BiometricPendingLogsQuery::create()
				->filterByid($pendingLogIds)
				->delete();
		}
	}


}