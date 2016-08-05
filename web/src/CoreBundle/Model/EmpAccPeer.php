<?php

namespace CoreBundle\Model;

use CoreBundle\Model\om\BaseEmpAccPeer;

use \Criteria;

class EmpAccPeer extends BaseEmpAccPeer{
	public static function getInformation($id, Criteria $c = null){
		if (is_null($c)) {
			$c = new Criteria();
		}

	}

	public static function getAllUser(Criteria $c = null)
	{
		if(is_null($c)){
			$c = new Criteria();
		}
		
	$_self = self::doSelect($c);

	return $_self ? $_self : array();
	}

	public static function getUserByEmail($email, Criteria $c = null)
	{
		if(is_null($c)){
			$c = new Criteria();
		}

		$c->add(self::EMAIL, $email, Criteria::EQUAL);
		$result = self::doCount($c);

		return $result;
	}

	public static function getUserInfo($email, Criteria $c = null)
	{
		if(is_null($c)){
			$c = new Criteria();
		}

		$c->add(self::EMAIL, $email, Criteria::EQUAL);
		$result = self::doSelectOne($c);

		return $result;
	}
	public static function getAdminInfo(Criteria $c = null)
	{
		if(is_null($c)){
			$c = new Criteria();
		}

		$c->add(self::ROLE, 'ADMIN', Criteria::EQUAL);
		$result = self::doSelect($c);

		return $result ? $result : array();

	}

}
