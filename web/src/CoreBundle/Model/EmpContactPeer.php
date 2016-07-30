<?php

namespace CoreBundle\Model;

use CoreBundle\Model\om\BaseEmpContactPeer;

use \Criteria;

class EmpContactPeer extends BaseEmpContactPeer{
	public static function getContact($id, Criteria $c = null){
		if (is_null($c)) {
			$c = new Criteria();
		}	

		$c->add(self::EMP_PROFILE_ID, $id, Criteria::EQUAL);


		$_self = self::doSelect($c);

		return $_self ? $_self : array();
	}
}
