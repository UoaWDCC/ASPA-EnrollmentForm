<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class EmailModel extends CI_Model {
    public function sendEmail($emailAddress, $paymentMethod)
	{
        // email details
        $EMAIL_RECIEVER = $emailAddress;
        $EMAIL_SENDER = "uoawdcc@gmail.com";
        
        // event details
        $EVENT_NAME = "Pool Tournament";
        $EVENT_TIME = "27th March, 5:30 pm";
        $EVENT_LOCATION = "Orange Pool club: 9 City Road, Auckland CBD";

        // transfer details
        $TRANSFER_AMOUNT = "$3";
        $TRANSFER_ACCOUNT = "00000";
        
        if ($paymentMethod = "online") 
        {
            $EMAIL_SUBJECT = "Event Payment Confirmation - ASPA 2020";
            $TICK_IMAGE = "Green_tick.png";
            $TRANSFER_DETAIL = "";
            
        }
        elseif ($paymentMethod = "cash") {
            $EMAIL_SUBJECT = "Event Registration - ASPA 2020";
            $TICK_IMAGE = "Grey_tick.png";
            $TRANSFER_DETAIL = "";
        }
        else {
            $EMAIL_SUBJECT = "Event Registration - ASPA 2020";
            $TICK_IMAGE = "Grey_tick.png";
            $TRANSFER_DETAIL = "Please transfer " . $TRANSFER_AMOUNT . " to our bank account - " . $TRANSFER_ACCOUNT . "\r\n";
        }
        
        


        $to = $EMAIL_RECIEVER;

        $subject = $EMAIL_SUBJECT;

        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= "From: <" . $EMAIL_SENDER . ">\r\n";

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
                Please present this email to ASPA executive team member at the event. \r\n" . $TRANSFER_DETAIL . "
            </p>
        </body>
        </html>";

        if (mail($to,$subject,$message,$headers)) {
            echo "Success";
        } else {
            echo "Failed attempt";
        }
	}
}
