		{% image {'@MainBundle/Resources/public/images/' ~ img} %}
		<img src="{{ asset_url }}" alt="imageeeee"/>
		{% endimage %}
		<p>{{datejoined }}</p>



<?php

namespace Propelrr\CoreBundle\Model\Wing;

use Propelrr\CoreBundle\Model\Wing\om\BaseWingLeadsPeer;

use Propelrr\CoreBundle\Utilities\Constant as C;

use \Criteria;

class WingLeadsPeer extends BaseWingLeadsPeer
{
	public static function getLeadsByVerificationCode($code, $id, Criteria $c = null)
	{
		if (is_null($c)) {
			$c = new Criteria();
		}

		$c->add(self::STATUS_ID, 1, Criteria::EQUAL);
		$c->add(self::VERIFICATION_CODE, $code, Criteria::EQUAL);
		$c->add(self::ID, $id, Criteria::EQUAL);

		$_self = self::doSelectOne($c);

		return $_self ? $_self : null;
	}

	public static function getAll(Criteria $c = null)
	{
		if (is_null($c)) {
			$c = new Criteria();
		}

		$_self = self::doSelect($c);

		return $_self ? $_self : array();
	}

	public static function getAllDates(Criteria $c = null)
	{
		if (is_null($c)) {
			$c = new Criteria();
		}

		$c->addSelectColumn(self::DATE_APPLIED);
		$c->addGroupByColumn(self::DATE_APPLIED);

		$_self = self::doSelectStmt($c);
		$dates = $_self->fetchAll();
		$_self = array();

		foreach ($dates as $date) {
            $_self[] = $date[0];
        }

		return $_self ? $_self : array();
	}

	public static function getAllByStatus($status, Criteria $c = null)
	{
		if (is_null($c)) {
			$c = new Criteria();
		}

		$c->add(self::STATUS_ID, $status, Criteria::EQUAL);

		$_self = self::doSelect($c);

		return $_self ? $_self : array();
	}

	public static function getAllCurrency(Criteria $c = null)
	{
		if (is_null($c)) {
			$c = new Criteria();
		}

		$c->addSelectColumn(self::CURRENCY);
		$c->addGroupByColumn(self::CURRENCY);

		$_self = self::doSelectStmt($c);
		$currencies = $_self->fetchAll();
		$_self = array();

		foreach ($currencies as $currency) {
            $_self[] = $currency[0];
        }

		return $_self ? $_self : array();
	}
}
