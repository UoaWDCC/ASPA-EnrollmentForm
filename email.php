<?php 

$EMAIL_RECIEVER
$EMAIL_SENDER
$EMAIL_SUBJECT
$TICK_IMAGE
$EVENT_NAME
$EVENT_TIME
$EVENT_LOCATION


$to = $EMAIL_RECIEVER;

$subject = $EMAIL_SUBJECT;

$header = "MINE-Version: 1.0" . "\r\n";
$header .= "Content-type:text/html;charset=UTF-8" . "\r\n";
$header .= "From: " . $EMAIL_SENDER . "\r\n";

$message = "
<html>
<head>
	<title></title>
	<h1>
		ASPA 2020
	</h1>
</head>
<body>
	<img src=\"ASPA_logo.png\">
	<div> 
		<h1>
			Payment Successful!
		</h1>
		<img src=\"" . $TICK_IMAGE . "\"
	</div>
	<p>
		Thank you for signing up to" . $EVENT_NAME . "\r\n
		Time: " . $EVENT_TIME . "\r\n
		Location: " . $EVENT_LOCATION . "\r\n
		Please present this email to ASPA executive team member at the event.
	</p>
</body>
</html>";

mail($to,$subject,$message,$headers);

?>