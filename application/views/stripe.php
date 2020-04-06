<HEAD><script src="https://js.stripe.com/v3/"></script></HEAD>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

<script type="text/javascript">
  $(document).ready(function() {
    var stripe = Stripe('<?php echo PUBLICKEY ?>');
    var div = document.getElementById("session-id");
    var myData = "<?php echo $session_id ?>";

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