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
            else {
                echo "error";
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
}