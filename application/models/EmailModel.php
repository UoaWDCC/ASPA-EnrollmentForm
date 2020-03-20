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
            $TICK_IMAGE = "assets/images/Green_Tick.png";
            $TRANSFER_DETAIL = "";
            
        }
        elseif ($paymentMethod = "cash") {
            $EMAIL_SUBJECT = "Event Registration - ASPA 2020";
            $TICK_IMAGE = "assets/images/Grey_Tick.jpg";
            $TRANSFER_DETAIL = "";
        }
        else {
            $EMAIL_SUBJECT = "Event Registration - ASPA 2020";
            $TICK_IMAGE = "assets/images/Grey_tick.png";
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
                <img src=\"cid:ASPA_logo\">
                ASPA 2020
            </h1>
        </head>
        <body>
            <div> 
                <h1>
                    <img src=\"cid:Tick\">
                    Payment Successful!
                </h1>
            </div>
            <p>
                Thank you for signing up to" . $EVENT_NAME . "\r\n
                Time: " . $EVENT_TIME . "\r\n
                Location: " . $EVENT_LOCATION . "\r\n
                Please present this email to ASPA executive team member at the event. \r\n" . $TRANSFER_DETAIL . "
            </p>
        </body>
        </html>";

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

                                                                <h1 style="display:block;margin:0;padding:0;color:#888888;font-family:Helvetica;font-size:30px;font-style:normal;font-weight:bold;line-height:100%;letter-spacing:normal;text-align:left"><span style="font-size:38px"><span style="color:#303030">ASPA\'s 2020 Welcoming Event</span></span></h1>

                                                                <h3 style="display:block;margin:0;padding:0;color:#303030;font-family:Helvetica;font-size:16px;font-style:normal;font-weight:bold;line-height:125%;letter-spacing:normal;text-align:left">Proudly presented by Auckland Student Pool Association &amp; Consulting Club</h3>

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


                                                                <img align="center" alt="" src="https://ci6.googleusercontent.com/proxy/a-Y11PhU3wrOroceUvdi72htBcLGu4AMrj_uQLxEOBIb625yGxPz--IvpzkXXchctvtb-6PidAISL-jD42bJG3bz6gYPRT43qLPjPbEvdaJfBl4hxU3sIvPZHd7mVyGPvQihYeCFxdJS-0pVVpXZN_TiJvJgGQ=s0-d-e1-ft#https://mcusercontent.com/7144f488626ff8b60ce740739/images/c27ed99d-749a-46a7-8fb8-173e52c2fe2c.png" width="590" style="max-width:920px;padding-bottom:0;display:inline!important;vertical-align:bottom;border:0;height:auto;outline:none;text-decoration:none" class="m_-6544744198641712840mcnImage CToWUd a6T" tabindex="0"><div class="a6S" dir="ltr" style="opacity: 0.01; left: 557px; top: 378px;"><div id=":6z" class="T-I J-J5-Ji aQv T-I-ax7 L3 a5q" role="button" tabindex="0" aria-label="Download attachment " data-tooltip-class="a1V" data-tooltip="Download"><div class="aSK J-J5-Ji aYr"></div></div></div>


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

                                                                                <p style="text-align:left;margin:10px 0;padding:0;color:#505050;font-family:Helvetica;font-size:16px;line-height:150%">ASPA is back bigger and better than ever before!&nbsp;</p><div>
                                                                                    <div class="adm"><div id="q_54" class="ajR h4"><div class="ajT"></div></div></div><div class="h5">

                                                                                        <p style="margin:10px 0;padding:0;color:#505050;font-family:Helvetica;font-size:16px;line-height:150%;text-align:left">
                                                                                            As for the start of 2020 we will be kicking off our first event with a casual night where new and old members can gather together to socialize over a few rounds of pool! <br>
                                                                                            &nbsp;
                                                                                        </p>

                                                                                        <p style="text-align:center;margin:10px 0;padding:0;color:#505050;font-family:Helvetica;font-size:16px;line-height:150%">
                                                                                            <strong>When: </strong><br>
                                                                                            Thursday 12th March 6:30pm - 8:00pm
                                                                                        </p>

                                                                                        <p style="text-align:center;margin:10px 0;padding:0;color:#505050;font-family:Helvetica;font-size:16px;line-height:150%">
                                                                                            <strong>Where:</strong><br>
                                                                                            Orange Pool Club (Corner of Liverpool St and City Rd)
                                                                                        </p>

                                                                                        <p style="text-align:center;margin:10px 0;padding:0;color:#505050;font-family:Helvetica;font-size:16px;line-height:150%">
                                                                                            <strong>Entry Fee:</strong><br>
                                                                                            3$ For ASPA Members (5$ Membership Fee)<br>
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
                                                                        <div>12</div>
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
                                                                                                            10:00 to 18:00<br>
                                                                                                            Room 405-460
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
                                                                    <td valign="top" style="padding-top:9px">



                                                                        <table align="left" border="0" cellpadding="0" cellspacing="0" style="max-width:100%;min-width:100%;border-collapse:collapse" width="100%" class="m_-6544744198641712840mcnTextContentContainer">
                                                                            <tbody>
                                                                                <tr>

                                                                                    <td valign="top" class="m_-6544744198641712840mcnTextContent" style="padding-top:0;padding-right:18px;padding-bottom:9px;padding-left:18px;word-break:break-word;color:#505050;font-family:Helvetica;font-size:12px;line-height:150%;text-align:left">

                                                                                        Sign up today!
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
                                                                    <td style="padding-top:0;padding-right:18px;padding-bottom:18px;padding-left:18px" valign="top" align="center">
                                                                        <table border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse:separate!important;border:5px solid #ffffff;border-radius:0px;background-color:#1c5a9a">
                                                                            <tbody>
                                                                                <tr>
                                                                                    <td align="center" valign="middle" style="font-family:Helvetica;font-size:14px;padding:15px">
                                                                                        <a class="m_-6544744198641712840mcnButton" title="REGISTER" href="https://wdcc.us4.list-manage.com/track/click?u=7144f488626ff8b60ce740739&amp;id=8ebc1f0e31&amp;e=719a5ef366" style="font-weight:bold;letter-spacing:normal;line-height:100%;text-align:center;text-decoration:none;color:#ffffff;display:block" target="_blank" data-saferedirecturl="https://www.google.com/url?q=https://wdcc.us4.list-manage.com/track/click?u%3D7144f488626ff8b60ce740739%26id%3D8ebc1f0e31%26e%3D719a5ef366&amp;source=gmail&amp;ust=1583892241057000&amp;usg=AFQjCNFYyQWnUEMqGWRhrr0GKEsprILcOw">REGISTER</a>
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


                                                                                        <img align="center" alt="" src="https://ci6.googleusercontent.com/proxy/GOFpLDJtori8DtVZ29zvNwvyIQ5hvGIVAsDbMsgx0ZgydfjQi-IHro8QVuKOxa3VZYS56a2cMSpVFSR4tEgMxoGl2-YF-HbeuQyxzSAqnmPtuPZHh2XBHECbFdef8ZD5VNp_BdutUrkGofWYVPCqfISKkvtGJw=s0-d-e1-ft#https://mcusercontent.com/7144f488626ff8b60ce740739/images/86f579ab-d6b3-4b1e-ae4b-77671157aea0.png" width="150" style="max-width:256px;padding-bottom:0;display:inline!important;vertical-align:bottom;border:0;height:auto;outline:none;text-decoration:none" class="m_-6544744198641712840mcnImage CToWUd a6T" tabindex="0"><div class="a6S" dir="ltr" style="opacity: 0.01; left: -8px; top: -8px;"><div id=":70" class="T-I J-J5-Ji aQv T-I-ax7 L3 a5q" title="Download" role="button" tabindex="0" aria-label="Download attachment " data-tooltip-class="a1V"><div class="aSK J-J5-Ji aYr"></div></div></div>


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
                            <td align="right" valign="top" style="padding-top:18px">
                                <img src="https://ci6.googleusercontent.com/proxy/GOEk0t9O_cxYfhnbeXSqh1aNOaru0XtX8pFCSTqCKmJDzJ0QI0PfRneq_8i-PsQcrhx6jqXtie0JVF6mYy6IngjYTLT4n_pfNjE0mMwvlwATXnSh6Zu8gIq5IOgXF1Hp7-plNoEbyCXuVZydbioEsozoGBs=s0-d-e1-ft#https://cdn-images.mailchimp.com/template_images/gallery/03c9e5d8-4a2f-471e-b646-37327134c2b0.png" height="30" width="15" style="display:block;border:0;height:auto;outline:none;text-decoration:none" class="CToWUd">
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
            $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      // Enable verbose debug output
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

