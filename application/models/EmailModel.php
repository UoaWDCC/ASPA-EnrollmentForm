<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'application/PHPMailer/src/Exception.php';
require 'application/PHPMailer/src/PHPMailer.php';
require 'application/PHPMailer/src/SMTP.php';


// This model sends different emails to specified email address based on the payment method
class EmailModel extends CI_Model {
    public function sendEmail($emailAddress, $paymentMethod)
    {
        // email details
        
        $EMAIL_RECIEVER = $emailAddress;
        $EMAIL_SENDER = "uoawdcc@gmail.com";
        
        // event details
        $EVENT_NAME = "Pool Tournament";
        $EVENT_TIME = "27th March, 5:30 pm";
        $EVENT_MONTH = "March 2020";
        $EVENT_DAY = "27";
        $EVENT_LOCATION = "Orange Pool club: 9 City Road, Auckland CBD";
        $EVENT_FEE = "3$ For ASPA Members (5$ Membership Fee)";
        $EVENT_IMAGE = "https://secure.meetupstatic.com/photos/event/a/6/d/6/600_484542710.jpeg";

        // transfer details
        $TRANSFER_AMOUNT = "$3";
        $TRANSFER_ACCOUNT = "00000";

        // default colour of the payment method shown on email (red)
        $MSG_COLOUR = "#ff0000"; 
        
        // change email details based on different payment method
        if ($paymentMethod == "online") 
        {
            $EMAIL_SUBJECT = "Event Payment Confirmation - ASPA 2020";
            $TICK_IMAGE = "assets/images/Green_Tick.png";
            $PAYMENT_DETAIL = "PAID ONLINE";
            $MSG_COLOUR = "#00ff00";
		$TRANSFER_DETAIL = "";
        }
        elseif ($paymentMethod == "cash") {
            $EMAIL_SUBJECT = "Event Registration - ASPA 2020";
            $TICK_IMAGE = "assets/images/Grey_Tick.jpg";
            $PAYMENT_DETAIL = "CASH";
            $TRANSFER_DETAIL = "";
        }
        else {
            $EMAIL_SUBJECT = "Event Registration - ASPA 2020";
            $TICK_IMAGE = "assets/images/Grey_tick.png";
            $PAYMENT_DETAIL = "TRANSFER";
            $TRANSFER_DETAIL = "Please transfer " . $TRANSFER_AMOUNT . " to our bank account - " . $TRANSFER_ACCOUNT . "\r\n";
        }


        // Body of email in HTML format (Extracted from mailchimp template)
        $message = '
        <html>
        <head>
        </head>
        <body>
        <table border="0" cellpadding="0" cellspacing="0" width="100%" class="m_-6544744198641712840templateContainer" style="border-collapse:collapse;border:5px solid #ffffff;max-width:600px!important">
        <tbody>
        <tr>
        <td valign="top" id="m_-6544744198641712840templateUpperHeader" class="m_-6544744198641712840templateHeader" style="background-color:#efefef;border-top:0;border-bottom:0;padding-bottom:10px;padding-top:30px;padding-right:15px">
        <table align="left" cellpadding="0" cellspacing="0" width="100%" style="border-collapse:collapse">
        <tbody>
        <tr>
        <td align="left" valign="middle">
        <img src="https://ci5.googleusercontent.com/proxy/kgghvUQHr_Eo7Sj6cd6BRGlcTiofYc_0v79c2hdHU-tbLOQcwYCxY16cj0y8TQj97LPIKu3lCMf6UA50ookZtAFyusF7JA-YwmYBsCTkC_GY5PxgWr4JprFKNlPVFge0SVqSd7mVi8uGZULyQR5WAEpbslo=s0-d-e1-ft#https://cdn-images.mailchimp.com/template_images/gallery/47662b23-df38-45d4-8005-9b2f50193f4b.png" height="30" width="15" style="display:block;border:0;height:auto;outline:none;text-decoration:none" class="CToWUd">
        </td>
        <td align="left" valign="middle" width="100%">
        <table border="0" cellpadding="0" cellspacing="0" width="100%" style="min-width:100%;border-collapse:collapse">
        <tbody>
        <tr>
        <td valign="top" style="padding-top:9px">
        <table align="left" border="0" cellpadding="0" cellspacing="0" style="max-width:100%;min-width:100%;border-collapse:collapse" width="100%" class="m_-6544744198641712840mcnTextContentContainer">
        <tbody>
        <tr>
        <td valign="top" class="m_-6544744198641712840mcnTextContent" style="padding-top:0;padding-right:18px;padding-bottom:9px;padding-left:18px;word-break:break-word;color:#202020;font-family:Helvetica;font-size:16px;line-height:150%;text-align:left">

        <h1 style="display:block;margin:0;padding:0;color:#888888;font-family:Helvetica;font-size:30px;font-style:normal;font-weight:bold;line-height:100%;letter-spacing:normal;text-align:left"><span style="font-size:38px"><span style="color:#303030">Thank you for siging up to ' . $EVENT_NAME . '</span></span></h1>
        <h3 style="display:block;margin:0;padding:0;color:#303030;font-family:Helvetica;font-size:16px;font-style:normal;font-weight:bold;line-height:125%;letter-spacing:normal;text-align:left">Proudly presented by Auckland Student Pool Association</h3>
        </td>
        </tr>
        </tbody>
        </table>
        </td>
        </tr>
        </tbody>
        </table>
        </td>
        </tr>
        </tbody>
        </table>
        </td>
        </tr>
        <tr>
        <td valign="top" class="m_-6544744198641712840templateHeader" style="background-color:#efefef;border-top:0;border-bottom:0;padding-bottom:10px">
        <table align="left" cellpadding="0" cellspacing="0" width="100%" style="border-collapse:collapse">
        <tbody>
        <tr>
        <td align="left" valign="middle" width="100%">
        <table border="0" cellpadding="0" cellspacing="0" width="100%" style="min-width:100%;border-collapse:collapse">
        <tbody>
        <tr>
        <td valign="top" style="padding:0px">
        <table align="left" width="100%" border="0" cellpadding="0" cellspacing="0" style="min-width:100%;border-collapse:collapse">
        <tbody>
        <tr>
        <td valign="top" style="padding-right:0px;padding-left:0px;padding-top:0;padding-bottom:0;text-align:center">
        <img align="center" alt="" src="' . $EVENT_IMAGE . '" width="590" style="max-width:920px;padding-bottom:0;display:inline!important;vertical-align:bottom;border:0;height:auto;outline:none;text-decoration:none" class="m_-6544744198641712840mcnImage CToWUd a6T" tabindex="0"><div class="a6S" dir="ltr" style="opacity: 0.01; left: 557px; top: 378px;"><div id=":6z" class="T-I J-J5-Ji aQv T-I-ax7 L3 a5q" role="button" tabindex="0" aria-label="Download attachment " data-tooltip-class="a1V" data-tooltip="Download"><div class="aSK J-J5-Ji aYr"></div></div></div>
        </td>
        </tr>
        </tbody>
        </table>
        </td>
        </tr>
        </tbody>
        </table>
        </td>
        </tr>
        </tbody>
        </table>
        </td>
        </tr>
        <tr>
        <td valign="top" id="m_-6544744198641712840templateColumns" style="background-color:#efefef;border-top:0;border-bottom:0;padding-top:0;padding-bottom:9px">
        <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse:collapse">
        <tbody>
        <tr>
        <td valign="top">
        <table align="left" border="0" cellpadding="0" cellspacing="0" width="380" id="m_-6544744198641712840templateBody" style="border-collapse:collapse;background-color:#efefef;border-top:0;border-bottom:0;padding-top:0;padding-bottom:9px">
        <tbody>
        <tr>
        <td>
        <table border="0" cellpadding="0" cellspacing="0" width="100%" style="min-width:100%;border-collapse:collapse">
        <tbody>
        <tr>
        <td valign="top" style="padding-top:9px">
        <table align="left" border="0" cellpadding="0" cellspacing="0" style="max-width:100%;min-width:100%;border-collapse:collapse" width="100%" class="m_-6544744198641712840mcnTextContentContainer">
        <tbody>
        <tr>
        <td valign="top" class="m_-6544744198641712840mcnTextContent" style="padding-top:0;padding-right:18px;padding-bottom:9px;padding-left:18px;word-break:break-word;color:#505050;font-family:Helvetica;font-size:16px;line-height:150%;text-align:left">
        <p style="margin:10px 0;padding:0;color:#505050;font-family:Helvetica;font-size:16px;line-height:150%;text-align:left">Hi there!</p>
        <p style="text-align:left;margin:10px 0;padding:0;color:#505050;font-family:Helvetica;font-size:16px;line-height:150%">ASPA is back bigger and better than ever before! As for the start of 2020 we will be kicking off our first event with a casual night where new and old members can gather together to socialize over a few rounds of pool!&nbsp;</p><div>
        <div class="adm"><div id="q_54" class="ajR h4"><div class="ajT"></div></div></div><div class="h5">
        <p style="margin:10px 0;padding:0;color:#ff0000;font-family:Helvetica;font-size:16px;line-height:150%;text-align:left">
        Please present this email to ASPA executive team member at the event. <br>
        &nbsp;
        </p>
        <p style="text-align:center;margin:10px 0;padding:0;color:#505050;font-family:Helvetica;font-size:16px;line-height:150%">
        <strong>When: </strong><br>
        ' . $EVENT_TIME . '
        </p>
        <p style="text-align:center;margin:10px 0;padding:0;color:#505050;font-family:Helvetica;font-size:16px;line-height:150%">
        <strong>Where:</strong><br>
        ' . $EVENT_LOCATION . '
        </p>
        <p style="text-align:center;margin:10px 0;padding:0;color:' . $MSG_COLOUR . ';font-family:Helvetica;font-size:40px;line-height:150%">
        <strong>
        ' . $PAYMENT_DETAIL . '<br>
        ' . $TRANSFER_DETAIL . '
        <br>
        &nbsp;
        </p>
        </div>
        </div>
        </td>
        </tr>
        </tbody>
        </table>
        </td>
        </tr>
        </tbody>
        </table>
        </td>
        </tr>
        </tbody>
        </table><div>
        <div class="adm"><div id="q_52" class="ajR h4"><div class="ajT"></div></div></div><div class="h5">
        <table align="left" border="0" cellpadding="0" cellspacing="0" width="186" id="m_-6544744198641712840templateSidebar" style="border-collapse:collapse;border-top:0;border-bottom:0;padding-top:9px;padding-bottom:9px">
        <tbody>
        <tr>
        <td align="center" valign="top" style="padding-top:9px;padding-bottom:9px">
        <table border="0" cellpadding="5" cellspacing="5" width="150" id="m_-6544744198641712840calendarContainer" style="border-collapse:collapse;background-color:#ffffff;border:5px solid #ffffff">
        <tbody>
        <tr>
        <td align="center" bgcolor="#EFEFEF" valign="top" id="m_-6544744198641712840monthContainer" style="background-color:#efefef;color:#303030;font-family:Helvetica;font-size:14px;font-weight:bold;line-height:150%">
        <div>March 2020</div>
        </td>
        </tr>
        <tr>
        <td align="center" valign="top" id="m_-6544744198641712840dayContainer" style="background-color:#ffffff;color:#303030;font-family:Helvetica;font-size:72px;font-weight:bold;line-height:100%">
        <div>' . $EVENT_DAY . '</div>
        </td>
        </tr>
        </tbody>
        </table>
        </td>
        </tr>
        <tr>
        <td valign="top">
        <table border="0" cellpadding="0" cellspacing="0" width="100%" style="min-width:100%;border-collapse:collapse">
        <tbody>
        <tr>
        <td valign="top">
        <table align="left" border="0" cellpadding="0" cellspacing="0" width="100%" style="min-width:100%;border-collapse:collapse" class="m_-6544744198641712840mcnBoxedTextContentContainer">
        <tbody>
        <tr>
        <td style="padding-top:9px;padding-left:18px;padding-bottom:9px;padding-right:18px">
        <table border="0" cellspacing="0" class="m_-6544744198641712840mcnTextContentContainer" width="100%" style="min-width:100%!important;background-color:#ffffff;border-collapse:collapse">
        <tbody>
        <tr>
        <td valign="top" class="m_-6544744198641712840mcnTextContent" style="padding:18px;color:#f2f2f2;font-family:Helvetica;font-size:14px;font-weight:normal;text-align:center;word-break:break-word;line-height:150%">
        <span style="color:#303030">
        ' . $EVENT_TIME . '<br>
        ' . $EVENT_LOCATION . '
        </span>
        </td>
        </tr>
        </tbody>
        </table>
        </td>
        </tr>
        </tbody>
        </table>
        </td>
        </tr>
        </tbody>
        </table><table border="0" cellpadding="0" cellspacing="0" width="100%" style="min-width:100%;border-collapse:collapse">
        <tbody>
        <tr>
        <td valign="top" style="padding:9px">
        <table align="left" width="100%" border="0" cellpadding="0" cellspacing="0" style="min-width:100%;border-collapse:collapse">
        <tbody>
        <tr>
        <td valign="top" style="padding-right:9px;padding-left:9px;padding-top:0;padding-bottom:0;text-align:center">
        <img align="center" alt="" src="https://scontent.fhlz1-1.fna.fbcdn.net/v/t1.0-9/37003567_653981724937409_7887348173679624192_n.png?_nc_cat=103&_nc_sid=85a577&_nc_oc=AQlCpgJ-i9yxbnHkDPi4_GoH7Hf9tgHw1J4-3KsiZ0To0-dQUBozDgzlQXRrXP1wyay4hQEM5K8FvSNhdiVbjJlB&_nc_ht=scontent.fhlz1-1.fna&oh=40672df287143b36758e79ac1c0fb1b3&oe=5E9C5099" width="150" style="max-width:256px;padding-bottom:0;display:inline!important;vertical-align:bottom;border:0;height:auto;outline:none;text-decoration:none" class="m_-6544744198641712840mcnImage CToWUd a6T" tabindex="0"><div class="a6S" dir="ltr" style="opacity: 0.01; left: -8px; top: -8px;"><div id=":70" class="T-I J-J5-Ji aQv T-I-ax7 L3 a5q" title="Download" role="button" tabindex="0" aria-label="Download attachment " data-tooltip-class="a1V"><div class="aSK J-J5-Ji aYr"></div></div></div>
        </td>
        </tr>
        </tbody>
        </table>
        </td>
        </tr>
        </tbody>
        </table>
        </td>
        </tr>
        </tbody>
        </table>
        </div>
        </div>
        </td>
        </tr>
        </tbody>
        </table>
        </td>
        </tr>
        </tbody>
        </table>
        </body>
        </html>';

        // mail($to,$subject,$message,$headers);


        // Load Composer's autoloader
        // require 'vendor/autoload.php';

        // Instantiation and passing `true` enables exceptions
        $mail = new PHPMailer(true);

        try {
            //Server settings
            //$mail->SMTPDebug = SMTP::DEBUG_SERVER;                      // Enable verbose debug output
            $mail->isSMTP();                                            // Send using SMTP
            $mail->Host       = 'smtp.gmail.com';                    // Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
            $mail->Username   = 'uoawdcc@gmail.com';                     // SMTP username
            $mail->Password   = 'orkyhxoabrnpqeri';                               // SMTP password
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
            // $mail->AddEmbeddedImage('assets/images/ASPA_logo.png','ASPA_logo');
            // $mail->AddEmbeddedImage($TICK_IMAGE,'Tick');
            // $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
            // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

            // Content
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = $EMAIL_SUBJECT;
            $mail->Body    = $message;
            $mail->AltBody = 'Thank you for signing up to ASPA event. These email is shown due to the your device restriction.';

            $mail->send();
        } catch (Exception $e) {
        }
    }
}

