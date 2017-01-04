<?php

namespace CoreBundle\Model;

use CoreBundle\Model\om\BaseListContTypesPeer;

use \Criteria;

class ListContTypesPeer extends BaseListContTypesPeer{
	public static function getContactType($id, Criteria $c = null)
	{
		if (is_null($c)) {
			$c = new Criteria();
		}	

		$c->add(self::ID, $id, Criteria::EQUAL);

		$_self = self::doSelectOne($c);

		return $_self ? $_self : null;

	}	
}
