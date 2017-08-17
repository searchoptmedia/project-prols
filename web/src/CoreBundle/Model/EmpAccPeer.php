<?php

namespace CoreBundle\Model;

use CoreBundle\Model\om\BaseEmpAccPeer;

use \Criteria;

class EmpAccPeer extends BaseEmpAccPeer{
	public static function getAcc($id, Criteria $c = null){
		if (is_null($c)) {
			$c = new Criteria();
		}

		$c->add(self::ID, $id, Criteria::EQUAL);

		$_self = self::doSelectOne($c);

		return $_self ? $_self : null;

	}

	public static function getAccList($id, Criteria $c = null){
		if (is_null($c)) {
			$c = new Criteria();
		}

		$c->add(self::ID, $id, Criteria::EQUAL);

		$_self = self::doSelect($c);

		return $_self ? $_self : null;

	}


	public static function getAllUser($status = 1, Criteria $c = null)
	{
		if(is_null($c)){
			$c = new Criteria();
		}

        if($status)
			$c->add(self::STATUS, 1, Criteria::EQUAL);

		$result = self::doSelect($c);

        return $result ? $result : array();
	}

	public static function getUserByEmail($email, Criteria $c = null)
	{
		if(is_null($c)){
			$c = new Criteria();
		}

		$c->add(self::EMAIL, $email, Criteria::EQUAL);
		$result = self::doCount($c);

		return $result ? $result : null;
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
