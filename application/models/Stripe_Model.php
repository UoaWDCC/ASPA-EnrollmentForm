<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Stripe_Model extends CI_Model {

    // Note if the child has no constructor then parent constructor is used
    function __construct()
    {
	    // You have to explicitly call parent Constructor
    	parent::__construct();
    }

    /**
	* This function checks if the stripe session has made it's payment successfully
	*
	* @param string    	$session_id  		The session id of this current payment sessio
	*/
    function CheckPayment($session_id)
    {
        require_once('vendor/autoload.php');
        //session_start();

        echo $session_id;
        echo "<hr>";

        $hasPaid = False;

        \Stripe\Stripe::setApiKey(SECRETKEY);

        $events = \Stripe\Event::all([
        'type' => 'checkout.session.completed',
        'created' => [
            // Check for events created in the last 3 minutes
            'gte' => time() - 1 * 3 * 60,
        ],
        ]);

        foreach ($events->autoPagingIterator() as $event) {
            //getting each session object
            $session = $event->data->object;
        
            //Checking if a paid session id matches with current session id
            if ($session->id == $session_id) {
                
                $hasPaid = True;
                break;
            }
        }
        return  $hasPaid;
    }

    function GetEmail($session_id) {
    
        \Stripe\Stripe::setApiKey(SECRETKEY);

        $session_object = Stripe\Checkout\Session::retrieve(
        $session_id
        );

        return $session_object->customer_email;

    }

    function GenSessionId ($customer_email) {

        require_once('vendor/autoload.php');

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
        'success_url' => base_url().'EnrollmentForm/StripePaymentSucessful?session_id={CHECKOUT_SESSION_ID}',
        // 'success_url' => 'http://localhost/ASPA-EnrollmentForm/EnrollmentForm/loadPaymentSucessful?session_id={CHECKOUT_SESSION_ID}',
        'cancel_url' => 'http://localhost',
        'customer_email' => $customer_email,

        ]);
        $stripeSession = array($session);
        return ($stripeSession[0]['id']);
    }
}