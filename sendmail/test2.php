<?php
/* set zona waktu */
date_default_timezone_set('Asia/Jakarta');

include_once("easy.mail.class.php");

$email = new easyMail;

$message = "teste";

$email->addSMTPHost("mocha3035.mochahost.com");
$email->addSMTPPort(2525);
$email->addSMTPUsername("ppgep@gemad.net");
$email->addSMTPPassword(".poez%B^X4N8");
$email->addSMTPAuth("tls");
$email->addDomain("gemad.net");

$email->addTo("rafaelbuback@hotmail.com","Another User Name");
//$email->addTo("another.mail2@another-domain.com","Another User Name 2");
//$email->addCc("another.mail3@another-domain.com","Another User Name 3");
$email->addFrom("ppgep@gemad.net","User Name");
//$email->addBcc("another.mail4@another-domain.com","Another User Name 4");
$email->addSubject("[GPS-PG]");
$email->addMessage($message);
//$email->addAttachment("log/smtp-20210917.log");

$send = $email->Send(1);

if($send===false) {
	echo "Email is not sent<br /><br />";

	$report = array_merge($email->errorReport(),$email->SMTPDebugReport());
	sort($report);

	foreach($report as $report) {
		$report = explode("|",$report);
		echo "<b>".$report[0]." <span style='color: #f00'>".$report[1]."</span></b><br />";
	}
} else {
	echo "Email has been sent";
}

?>