<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<title>ASPA Event registration form</title>
	<meta content="The online enrollment for Auckland Student Pool Association's first event">
</head>

<body>
<style>
	button {
		display: block;
	}
</style>

<button class="btn" id="button-Description">ok I'v read the description</button>
<button class="btn" id="button-NameEmailPage">ok check my name and email</button>
<br>
<button class="btn" id="button-cash">cash</button>
<button class="btn" id="description-Alipay">bank transfer</button>
<br>
<button class="btn" id="button-Wechat">WechatPay</button>
<button class="btn" id="button-alipay">Alipay</button>
<button class="btn" id="description-CreditCard">Credit Card</button>
<button class="btn" id="button-Wechat">PoLiPay</button>
<br>
<button class="btn" id="send-email" href="/EnrollmentForm/send_email">Send Email</button>


</body>

</html>