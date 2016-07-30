<?php

namespace CoreBundle\Utilities;

use Swift_Mailer;
use Swift_SmtpTransport;
use Swift_Message;

class Mailer
{
	public function sendOutOfOfficeEmailToAdmin()
	{
		$to = array('dexter.loor@searchoptmedia.com' => 'Admin');
		$from = array('prols.mailer@propelrr.com' => 'Prols Mailer');
		$subject = "Test Subject";
		$message = 'Lorem ipsum';

		return self::sendMail($to, $from, $subject, $message);
	}

	private static function sendMail($to, $from, $subject, $message, $attachment1 = null, $attachment2 = null)
	{
		$transport = Swift_SmtpTransport::newInstance('smtp.gmail.com', 465, 'ssl');
		$transport->setUsername('christian.fallaria@searchoptmedia.com');
		$transport->setPassword("baddonk123");

		$swift = Swift_Mailer::newInstance($transport);

		$message = new Swift_Message($subject);
		$message->setFrom($from);
		$message->setBody($message, 'text/html');
		$message->setTo($to);

		$result = $swift->send($message, $failures);

		return $result;
	}
}

?>