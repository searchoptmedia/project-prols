<?php

namespace CoreBundle\Model;

use CoreBundle\Model\om\BaseListDeptPeer;

use \Criteria;

class ListDeptPeer extends BaseListDeptPeer{
	public static function getDept($id, Criteria $c = null){
		if (is_null($c)) {
			$c = new Criteria();
		}	

		$c->add(self::ID, $id, Criteria::EQUAL);

		$_self = self::doSelectOne($c);

		return $_self ? $_self : null;

	}		

	public static function getAllDept(Criteria $c = null)
	{
		if(is_null($c)){
			$c = new Criteria();
		}
		
	$_self = self::doSelect($c);

	return $_self ? $_self : array();
	}	
}
