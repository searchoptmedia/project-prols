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
	public static function getEmpTime($id, Criteria $c = null){
		if (is_null($c)) {
			$c = new Criteria();
		}	

		$c->add(self::EMP_ACC_ACC_ID, $id, Criteria::EQUAL);

		$_self = self::doSelectOne($c);

		return $_self ? $_self : null;

	}	
}
