<?php

namespace CoreBundle\Model;

use CoreBundle\Model\om\BaseEmpWorkPeer;

use \Criteria;

class EmpWorkPeer extends BaseEmpWorkPeer{	
	public static function getWork($id, Criteria $c = null){
		if (is_null($c)) {
			$c = new Criteria();
		}	

		$c->add(self::EMP_ACC_ACC_ID, $id, Criteria::EQUAL);

		$_self = self::doSelectOne($c);

		return $_self ? $_self : null;

	}

	public static function getAllEmployee(Criteria $c = null)
	{
		if(is_null($c)){
			$c = new Criteria();
		}
		
	$_self = self::doSelect($c);

	return $_self ? $_self : array();
	}	
}
