<?php

namespace CoreBundle\Model;

use CoreBundle\Model\om\BaseEmpLeavePeer;
use \Criteria;
class EmpLeavePeer extends BaseEmpLeavePeer
{
	public static function getAllLeave(Criteria $c = null)
	{
		if(is_null($c)){
			$c = new Criteria();
		}

		$c->addDescendingOrderByColumn(self::ID);
		$c->setLimit(20);

	$_self = self::doSelect($c);

	return $_self ? $_self : array();
	}
}

