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

		$_self = self::doSelect($c);

		return $_self ? $_self : array();
	}	

	public static function getAllTime(Criteria $c = null)
	{
		if(is_null($c)){
			$c = new Criteria();
		}
		
		$_self = self::doSelect($c);

		return $_self ? $_self : array();
	}

	public static function getEmployeeTimes(Criteria $c = null)
	{
		if(is_null($c)){
			$c = new Criteria();
		}

		$c->addJoin(self::EMP_ACC_ACC_ID, EmpAccPeer::ID, Criteria::INNER_JOIN);
		$c->add(self::DATE, date('Y-m-d 00:00:00'), Criteria::LESS_THAN);
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

		$c->add(self::EMP_ACC_ACC_ID, $id, Criteria::EQUAL)->addDescendingOrderByColumn(self::TIME_IN);

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
