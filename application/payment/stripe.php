<HEAD><script src="https://js.stripe.com/v3/"></script></HEAD>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

<div id="session-id" style="display: none;">
<?php
require_once('vendor/autoload.php');
session_start();

\Stripe\Stripe::setApiKey('sk_test_OMC00A11yJUakUU4kx6KoGTp0028EYnLBa');
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
  'success_url' => 'http://localhost/Stripe/redir.php',
  'cancel_url' => 'https://example.com/cancel',
  'customer_email' => 'example@gmail.com',
  'customer' => 'John Doe',
]);
$stripeSession = array($session);
$sessId = ($stripeSession[0]['id']);
?>
</div>


<script type="text/javascript">
  $(document).ready(function() {
    var stripe = Stripe('pk_test_A4wjqVPPn530rgAXv6sHKgSl00opCMVX9A');
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