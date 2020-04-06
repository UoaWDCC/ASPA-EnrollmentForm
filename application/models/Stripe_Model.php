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
     *  Generates a new stripe session with a customer email
     * 
     * @param string $customer_email             The email address of the session
     * 
     * @return $sessID                          The id of the newly created session
     */
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
        'cancel_url' => base_url().'EnrollmentForm',
        'customer_email' => $customer_email,

        ]);
        $stripeSession = array($session);
        $sessID = ($stripeSession[0]['id']);
        return $sessID;
    }
    
    /**
	* This function checks if the stripe session has made it's payment successfully
	*
	* @param string    	$session_id  		The session id of this current payment session
	*/
    function CheckPayment($session_id)
    {
        require_once('vendor/autoload.php');
        //session_start();

        $hasPaid = False;

        \Stripe\Stripe::setApiKey(SECRETKEY);

        $events = \Stripe\Event::all([
        'type' => 'checkout.session.completed',
        'created' => [
            // Check for events created in the last 3 minutes
            'gte' => time() - 1 * 10 * 60,
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

    /**
     * This Function returns the customers email given a session id
     * 
     * @param string $session_id             The session id of the desired email address
     * 
     * @return $email the email address of a given session
     */
    function GetEmail($session_id) {
    
        \Stripe\Stripe::setApiKey(SECRETKEY);

        $session_object = Stripe\Checkout\Session::retrieve(
        $session_id
        );

        $email = $session_object->customer_email;
        return $email;
    }
}