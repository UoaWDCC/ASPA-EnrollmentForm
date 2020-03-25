<HEAD><script src="https://js.stripe.com/v3/"></script></HEAD>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

<div id="session-id" style="display: none;">
<?php

//Get Email

require_once('vendor/autoload.php');
session_start();

\Stripe\Stripe::setApiKey(SECRETKEY);
$session = \Stripe\Checkout\Session::create([
  'payment_method_types' => ['card'],
  'line_items' => [[
    'name' => 'ASPA Event entry',
    'description' => 'Your entry into the next ASPA event!',
    'images' => ['https://example.com/t-shirt.png'],
    'amount' => 300,
    'currency' => 'NZD',
    'quantity' => 1,
  ]],
  'success_url' => 'http://localhost/ASPA-EnrollmentForm/EnrollmentForm/loadPaymentSucessful?session_id={CHECKOUT_SESSION_ID}',
  'cancel_url' => 'http://localhost',
  'customer_email' => 'playerEmail@gmail.com',

]);
$stripeSession = array($session);
$sessId = ($stripeSession[0]['id']);
?>
</div>


<script type="text/javascript">
  $(document).ready(function() {
    var stripe = Stripe('<?php echo PUBLICKEY ?>');
    var div = document.getElementById("session-id");
    var myData = "<?php echo $sessId ?>";

  stripe.redirectToCheckout({
  // Make the id field from the Checkout Session creation API response
  // available to this file, so you can provide it as parameter here
  // instead of the {{CHECKOUT_SESSION_ID}} placeholder.
  sessionId: myData
  }).then(function (result) {
  // If `redirectToCheckout` fails due to a browser or network
  // error, display the localized error message to your customer
  // using `result.error.message`.
  });
    
	});

</script>