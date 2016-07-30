<?php

namespace CoreBundle\Model;

use CoreBundle\Model\om\BaseListLeaveTypePeer;

use \Criteria;
class ListLeaveTypePeer extends BaseListLeaveTypePeer
{
	public static function getLeaveType($id, Criteria $c = null){
		if (is_null($c)) {
			$c = new Criteria();
		}	

		$c->add(self::ID, $id, Criteria::EQUAL);

		$_self = self::doSelectOne($c);

		return $_self ? $_self : null;

	}
}
