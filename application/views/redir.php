<!DOCTYPE html>
<html>
<HEAD><script src="https://js.stripe.com/v3/"></script></HEAD>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<body>

<?php
require_once('vendor/autoload.php');
//session_start();

echo $session_id."AAAAAAAAAAAAAAA";
echo "<hr>";

$hasPaid = False;

\Stripe\Stripe::setApiKey('sk_test_OMC00A11yJUakUU4kx6KoGTp0028EYnLBa');

$events = \Stripe\Event::all([
  'type' => 'checkout.session.completed',
  'created' => [
    // Check for events created in the last 24 hours.
    'gte' => time() - 1 * 2 * 60,
  ],
]);

//echo count($events->data);

foreach ($events->autoPagingIterator() as $event) {
  //
  $session = $event->data->object;

  if ($session->id == $session_id) {
      
     $hasPaid = True;
     $email = $session->customer_email;
    
   //   // Fulfill the purchase...
   //   handle_checkout_session($session);

     echo "has paid";
     break;
   }
   else {
     $hasPaid = false;
     echo "error";
   }

}

?>

</body>
</html>