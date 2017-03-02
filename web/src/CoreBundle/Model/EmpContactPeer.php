<?php

namespace CoreBundle\Model;

use CoreBundle\Model\om\BaseEmpContactPeer;

use \Criteria;

class EmpContactPeer extends BaseEmpContactPeer{
	public static function getContact($id,Criteria $c = null){
		if (is_null($c)) {
			$c = new Criteria();
		}	

		$c->add(self::EMP_PROFILE_ID, $id, Criteria::EQUAL);


		$_self = self::doSelect($c);

		return $_self ? $_self : array();
	}

	public static function getContactObject($id, $contactType, Criteria $c = null){
		if (is_null($c)) {
			$c = new Criteria();
		}

		$c->add(self::EMP_PROFILE_ID, $id, Criteria::EQUAL);
		$c->add(self::LIST_CONT_TYPES_ID, $contactType, Criteria::EQUAL);

		$_self = self::doSelectOne($c);

		return $_self ? $_self : null;
	}

	public static function getAllContact(Criteria $c = null)
	{
		if(is_null($c)){
			$c = new Criteria();
		}
		$_self = self::doSelect($c);

		return $_self ? $_self : array();
	}
}
