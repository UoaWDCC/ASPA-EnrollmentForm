<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Handles email sending and formatting.
 */
class Email_Model extends CI_Model {

    /**
     * Sends the confirmation email to the `emailAddress` with specified
     * `eventData` and the `paymentMethod`.
     *
     * @param string $recipientEmail The email address to send confirmation to.
     * @param string $paymentMethod The payment method used.
     * @param array $eventData Any event information.
     */
    public function sendConfirmationEmail(string $recipientName, string $recipientEmail, string $paymentMethod, array $eventData)
    {
        // Email details
        $EMAIL_RECEIVER = $recipientEmail;
        $EMAIL_SENDER = "uoawdcc@gmail.com";
        //receiver's full name
        $RECIPIENT_NAME = $recipientName;
        // event details on body
        $EVENT_NAME = $eventData["title"];
        $EVENT_TIME = $eventData["date"] . ', ' . $eventData["time"];

        // event details on right hand side
        $EVENT_MONTH = explode(" ", $eventData["date"])[2];
        $EVENT_DAY = substr(explode(" ", $eventData["date"])[1], 0, 2);

        $EVENT_DATETIME = explode(" ", $eventData["date"])[1] . ' ' . explode(" ", $eventData["date"])[2] . "<br />" . $eventData["time"];
        $EVENT_LOCATION = $eventData["location"];
        $EVENT_IMAGE = "https://user-images.githubusercontent.com/19633284/115980245-417e0500-a5df-11eb-9741-3b7a10499ef5.png";

        // transfer details
        $TRANSFER_AMOUNT = "$" . (string) number_format((float) $eventData["price"], 2, '.', '');
        $TRANSFER_ACCOUNT = $eventData["acc_num"];

        // default colour of the payment method shown on email (red)
        $MSG_COLOUR = "#ff0000";

        // change email details based on different payment method
        if ($paymentMethod == "online") {
            $EMAIL_SUBJECT = "Payment Confirmation - " . $eventData["title"];
            $TICK_IMAGE = "assets/images/Green_Tick.png";
            $PAYMENT_DETAIL = "ONLINE PAYMENT";
            $MSG_COLOUR = "#00ff00";
            $TRANSFER_DETAIL = "";
        } elseif ($paymentMethod == "cash") {
            $EMAIL_SUBJECT = "Event Registration - " . $eventData["title"];
            $TICK_IMAGE = "assets/images/Grey_Tick.jpg";
            $PAYMENT_DETAIL = "CASH";
            $TRANSFER_DETAIL = "";
        } else {
            $EMAIL_SUBJECT = "Event Registration - " . $eventData["title"];
            $TICK_IMAGE = "assets/images/Grey_tick.png";
            $PAYMENT_DETAIL = "TRANSFER";
            $TRANSFER_DETAIL = "Please transfer " . $TRANSFER_AMOUNT . " to our bank account - " . $TRANSFER_ACCOUNT . "\r\n";
        }

        # QR code url link
        $QR_CODE_URL = $_SERVER['HTTP_HOST'] . "/qrCode?email=" . $EMAIL_RECEIVER . "&event=" . $EVENT_NAME;

        // Body of email in HTML format (Extracted from mailchimp template)
        // UPDATED NOTE: The new email template is in views/ParseTemplate.php
        
        // NOTE: (OUTDATED)It is important all quote marks used inside this email body are double quotes" 
        $this->load->library('parser');
        $htmlTemplate = $this->load->view('EmailTemplate', NULL, TRUE);

        // Change the varibales here and make sure it matches with the {Var} in the template
        $data = array('$EVENT_NAME' => $EVENT_NAME, '$EVENT_IMAGE' => $EVENT_IMAGE, '$MSG_COLOUR' => $MSG_COLOUR, '$RECIPIENT_NAME' => $RECIPIENT_NAME, '$EVENT_TIME' => $EVENT_TIME, '$EVENT_LOCATION' => $EVENT_LOCATION, '$PAYMENT_DETAIL' => $PAYMENT_DETAIL, '$TRANSFER_DETAIL' => $TRANSFER_DETAIL, '$EVENT_MONTH' => $EVENT_MONTH, '$EVENT_DAY' => $EVENT_DAY, '$EVENT_DATETIME' => $EVENT_DATETIME, '$EVENT_LOCATION' => $EVENT_LOCATION);


        $message = $this->parser->parse('EmailTemplate', $data, true);
        
        $cmdlineArgs = [
                self::sanitize(MAIL_AUTH_EMAIL),
                self::sanitize(MAIL_AUTH_PASSWORD),
                self::sanitize($recipientEmail),
                self::sanitize($recipientName),
                self::sanitize($EMAIL_SUBJECT),
                self::sanitize(self::cleanString($message))
        ];

        // Build the command that will be executed
        $command = 'php -f ' . getProjectDir() . '/scripts/SendEmail.php ' . implode(" ", $cmdlineArgs);

        // Run the command, and run in the background by appending dev/null stuff
        exec($command . '  > /dev/null 2> /dev/null &');
    }

    /**
     * Escapes single quote so that when an email is sent to the user, 
     * the email body won't get cut off.
     * 
     * @param $stringCheck
     * 
     * @return string
     */
    private static function cleanString($stringCheck)
    {
        // escape apostrophe
        return str_replace("'", "&apos;", $stringCheck);
    }

    /**
     * Adds single quotes in front of every string.
     *
     * @param string $str
     *
     * @return string
     */
    private static function sanitize(string $str): string
    {
        return "'" . str_replace("'", "", $str) . "'";
    }

}
