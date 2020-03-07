<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'application/PHPMailer/src/Exception.php';
require 'application/PHPMailer/src/PHPMailer.php';
require 'application/PHPMailer/src/SMTP.php';


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

        // mail($to,$subject,$message,$headers);


        // Load Composer's autoloader
        // require 'vendor/autoload.php';

        // Instantiation and passing `true` enables exceptions
        $mail = new PHPMailer(true);

        try {
            //Server settings
            $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      // Enable verbose debug output
            $mail->isSMTP();                                            // Send using SMTP
            $mail->Host       = 'smtp.gmail.com';                    // Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
            $mail->Username   = 'uoawdcc@gmail.com';                     // SMTP username
            $mail->Password   = 'wdcc123456';                               // SMTP password
            // $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
            $mail->Port       = 587;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

            //Recipients
            $mail->setFrom('uoawdcc@gmail.com', 'WDCC');
            $mail->addAddress($EMAIL_RECIEVER);     // Add a recipient
            // $mail->addAddress('ellen@example.com');               // Name is optional
            // $mail->addReplyTo('info@example.com', 'Information');
            // $mail->addCC('cc@example.com');
            // $mail->addBCC('bcc@example.com');

            // Attachments
            // $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
            // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

            // Content
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = $subject;
            $mail->Body    = $message;
            $mail->AltBody = 'Thank you for signing up';

            $mail->send();
            echo 'Message has been sent';
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }
}

