<?php

require('./vendor/autoload.php');

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;


class MailService
{

    private $mail;


    /**
     * MailService constructor.
     *
     * @param string $authUsername The username to authenticate SMTP with.
     * @param string $authPassword The password to authenticate SMTP with.
     *
     * @throws Exception
     */
    public function __construct(string $authUsername, string $authPassword) {
        $this->mail = new PHPMailer(true);

        // Server settings – do not change these
        $this->mail->isSMTP();
        $this->mail->Host = 'smtp.gmail.com';
        $this->mail->Port = 465;
        $this->mail->SMTPAuth = true;
        $this->mail->Username = $authUsername;
        $this->mail->Password = $authPassword;
        $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;

        // Set the email to be sent from the default authenticated email address
        $this->mail->setFrom($authUsername, "UOA WDCC");
    }

    /**
     * Sends an email.
     *
     * @param string $recipientEmail
     * @param string $recipientName
     * @param string $subject
     * @param string $bodyContent
     *
     * @throws Exception
     */
    public function sendEmail(string $recipientEmail, string $recipientName, string $subject, string $bodyContent) {
        // Set the recipient and their name
        $this->mail->addAddress($recipientEmail, $recipientName);

        // Set the subject and body content, then send the email
        $this->mail->isHTML(true);
        $this->mail->Subject = $subject;
        $this->mail->Body = $bodyContent;
        $this->mail->send();
    }

}


/*
 * Script execution below
 */

try {
    // Take in arguments from the command line and pass them to the functions as needed
    $mailService = new MailService($argv[1], $argv[2]);
    $mailService->sendEmail($argv[3], $argv[4], $argv[5], $argv[6]);
} catch (Exception $e) {
    // TODO: Create an error file inside the logs directory and load error messages there with timestamps and details
    echo "";
}

