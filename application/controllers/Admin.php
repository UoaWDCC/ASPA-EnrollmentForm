<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require ('vendor/autoload.php');
use \Firebase\JWT\JWT;

/**
 * Handles all admin-checkup app related endpoints and views.
 *
 * @property GoogleSheets_Model $GoogleSheets_Model
 * @property CI_Input $input
 * @property CI_Output $output

 */
class Admin extends ASPA_Controller
{

    /**
     * Marks the attendee as paid by highlighting their row. 
     * It checks if either the email or upi is found in the spreadsheet. 
     * If either is found, it highlights the specified row.
     */
    public function markAsPaid() {
        $this->load->model('GoogleSheets_Model');

        // ONE OF THEM IS REQUIRED, EITHER.
        // get the members email and upi
        $email = $this->input->get('email');
        $upi = $this->input->get('upi');

        // If email and UPI both don't exist, return 412 to signify query params are not correct
        if (!$email && !$upi) {
            $this->output->set_status_header(412, "Queries not specified")->_display("412: Precondition failed");
            return;
        }

        // Get the cell with priority on email, and then UPI - if both are not found, then $cell is null
        $cell = $email ? $this->GoogleSheets_Model->getCellCoordinate($email, 'B')
            : $this->GoogleSheets_Model->getCellCoordinate($upi, 'E');

        if (!$cell) {
            $this->output->set_status_header(404, "error")->_display("404: Attendee not found");
            return;
        }

        // Split up the cell column and row
        list(, $row) = $this->GoogleSheets_Model->convertCoordinateToArray($cell);

        // Check if the cell is coloured, if not highlight the cell with pink :)
        $cellColour = $this->GoogleSheets_Model->getCellColour($cell);
        if ($cellColour == '000000' || $cellColour == 'ffffff') {
            // Highlight this row since it is paid, placed inside this code block to prevent unnecessary calls
            $this->GoogleSheets_Model->highlightRow($row ,[0.968, 0.670, 0.886]);

            // Return HTTP status code 200, to signify that it has successfully marked attendee as paid
            $this->output->set_status_header(200)->_display("200: Successfully, marked attendee as paid");
        }
    }

    /**
     * Checks an input key against a key stored in a file. If it matches, store a cookie on the users browser.
     */
    public function authenticate() {
        $jwtKey = 'JWTKEY';

        $key = $this->input->get('key');
        
        if ($key != 'key') {
            return false;
        }

        $cookieName = 'admin_authentication';

        $payload = array(
            "key" => $key,
        );

        $jwt = JWT::encode($payload, $jwtKey);

        setcookie($cookieName , $jwt); 
        return true;
    }


    /**
     * Check if a user has a specific cookie, and if they do allow them to do different things.
     */
    public function checkCookie() {
        $jwtKey = 'JWTKEY';
        $cookieName = 'admin_authentication';

        if(!isset($_COOKIE[$cookieName])) {
            echo 'Doesn\'t exist';
            return false;
        }

        $jwt = $_COOKIE[$cookieName];

        try {
        $decoded = JWT::decode($jwt, $jwtKey, array('HS256'));
        } catch (Exception $e) {
            echo 'Caught exception: ',  $e->getMessage(), "\n";
            return false;   
        }

        if ($decoded->key == "key") {
            echo 'Exists, and matches';
            return true;
        }
        else
        {
            echo 'Exists, but doesn\'t match';
            return false;
        }
    }

    public function paymentStatus() {
        // TODO: ASPA-14
    }

}
