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
    	
    }

}