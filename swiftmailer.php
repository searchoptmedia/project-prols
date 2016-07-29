<?php
    #!/xampp/php/php
	include_once "Swift-5.1.0\lib\swift_required.php";

	$subject = 'Ending Shift';
	$from = array('prols.mailer@propelrr.com' => 'Prols Mailer');
	$to = array(
		$conEmail => 'Recipient1 Name',
	);

	$text = "You are about to end your shift in 1 hour<br>Time out here: http://www.prols.local/";
	$html = "You are about to end your shift in <b>1 HOUR</b>.
	<br>Don't forget to <b>time-out</b> before you leave.
	<br>
	<br>Time out here: http://www.prols.local/";

	$transport = Swift_SmtpTransport::newInstance('smtp.gmail.com', 465, 'ssl');
	// $transport->setUsername('christian.guanlao@searchoptmedia.com');
	// $transport->setPassword("silveravalanche");
	$transport->setUsername('christian.fallaria@searchoptmedia.com');
	$transport->setPassword("baddonk123");
	$swift = Swift_Mailer::newInstance($transport);

	$message = new Swift_Message($subject);
	$message->setFrom($from);
	$message->setBody($html, 'text/html');
	$message->setTo($to);
	$message->addPart($text, 'text/plain');

	if ($recipients = $swift->send($message, $failures))
	{
	 echo 'Message successfully sent!';
	} else {
	 echo "There was an error:\n";
	 print_r($failures);
	}
?>