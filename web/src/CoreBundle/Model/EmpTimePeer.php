<?php

namespace CoreBundle\Model;

use CoreBundle\Model\om\BaseEmpTimePeer;

use \Criteria;

class EmpTimePeer extends BaseEmpTimePeer{
	public static function getTime($id, Criteria $c = null){
		if (is_null($c)) {
			$c = new Criteria();
		}
		
		$c->add(self::EMP_ACC_ACC_ID, $id, Criteria::EQUAL);
		$c->add(self::STATUS, -1, Criteria::NOT_EQUAL);

		$_self = self::doSelect($c);

		return $_self ? $_self : array();
	}

	public static function getTimeDescendingOrder($id, Criteria $c = null){
		if (is_null($c)) {
			$c = new Criteria();
		}

		$c->add(self::STATUS, -1, Criteria::NOT_EQUAL);
		$c->add(self::EMP_ACC_ACC_ID, $id, Criteria::EQUAL);
		$c->addDescendingOrderByColumn(self::DATE);
		$_self = self::doSelect($c);

		return $_self ? $_self : array();
	}

	public static function getAllTime(Criteria $c = null)
	{
		if(is_null($c)){
			$c = new Criteria();
		}
		$c->addDescendingOrderByColumn(self::DATE);
		$_self = self::doSelect($c);

		return $_self ? $_self : array();
	}

	public static function getAllTimeToday($date, Criteria $c = null)
	{
		if(is_null($c))
		{
			$c = new Criteria();
		}
		$c->add(self::DATE, $date, Criteria::EQUAL);
		$_self = self::doSelect($c);

		return $_self ? $_self : array();
	}

	public static function getEmployeeTimes(Criteria $c = null)
	{
		if(is_null($c)){
			$c = new Criteria();
		}
		
		$c->addJoin(self::EMP_ACC_ACC_ID, EmpProfilePeer::EMP_ACC_ACC_ID, Criteria::INNER_JOIN);

//		$c->addSelectColumn(EmpProfilePeer::LIST_DEPT_ID);

//		$c->add(self::DATE, date('Y-m-d 00:00:00'), Criteria::LESS_THAN);
		//$c->addDescendingOrderByColumn(EmpAccPeer::USERNAME);

		$rec = self::doSelect($c);

		return $rec;

	}
	public static function getEmpTime($id, Criteria $c = null){
		if (is_null($c)) {
			$c = new Criteria();
		}	

		$c->add(self::EMP_ACC_ACC_ID, $id, Criteria::EQUAL);

		$_self = self::doSelectOne($c);

		return $_self ? $_self : null;

	}
	public static function getEmpLastTimein($id, Criteria $c = null){
		if (is_null($c)) {
			$c = new Criteria();
		}

		$c->add(self::EMP_ACC_ACC_ID, $id, Criteria::EQUAL)
			->addDescendingOrderByColumn(self::TIME_IN);

		$_self = self::doSelectOne($c);

		return $_self ? $_self : null;

	}
	public static function getLastTimeinDiffIp($id, Criteria $c = null){
		if (is_null($c)) {
			$c = new Criteria();
		}

		$c->add(self::EMP_ACC_ACC_ID, $id, Criteria::EQUAL)->addAnd(self::CHECK_IP, 0, Criteria::EQUAL)->addDescendingOrderByColumn(self::TIME_IN);

		$_self = self::doSelectOne($c);

		return $_self ? $_self : null;

	}
	public static function getEmployeeTime(Criteria $c = null){
		if(is_null($c)){
			$c = new Criteria();
		}
		

		$c->add(EmpAccPeer::ROLE, 'ADMIN', Criteria::NOT_EQUAL);
		$c->addJoin(EmpAccPeer::ID, EmpProfilePeer::EMP_ACC_ACC_ID);
		$c->addJoin(EmpProfilePeer::EMP_ACC_ACC_ID, EmpTimePeer::EMP_ACC_ACC_ID);
		$record = self::doSelect($c);

		
		return $record ? $record : array();
	}
}
