<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require ('vendor/autoload.php');
use \Firebase\JWT\JWT;

/**
 * Handles all admin-checkup app related endpoints and views.
 *
 * @property GoogleSheets_Model $GoogleSheets_Model
 * @property Verification_Model $Verification_Model
 * @property CI_Input $input
 * @property CI_Output $output

 */
class Admin extends ASPA_Controller
{

    // constant for cookie name used in authenticate() and checkCookie()
    const AUTH_COOKIE_NAME = "aspa_admin_authentication";

    /**
     * Loads the main admin dashboard view.
     */
    public function index() {
        if (self::checkCookie()) {
            $this->load->view('Admin');
        } else {
            $this->authenticate();
        }
    }

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
            $this->createResponse(412, '412: Precondition failed');
            return;
        }

        // Get the cell with priority on email, and then UPI - if both are not found, then $cell is null
        $cell = $email ? $this->GoogleSheets_Model->getCellCoordinate($email, 'B')
            : $this->GoogleSheets_Model->getCellCoordinate($upi, 'E');

        if (!$cell) {
            $this->createResponse(404, '404: Attendee not found');
            return;
        }

        // Split up the cell column and row
        list(, $row) = $this->GoogleSheets_Model->convertCoordinateToArray($cell);

        // Check if the cell is coloured, if not highlight the cell with pink :)
        $cellColour = $this->GoogleSheets_Model->getCellColour($cell);
        if ($cellColour == '000000' || $cellColour == 'ffffff') {
            // Highlight this row since it is paid, placed inside this code block to prevent unnecessary calls
            $this->GoogleSheets_Model->highlightRow($row ,[0.968, 0.670, 0.886]);
            $this->GoogleSheets_Model->markAsPresent($this->eventData["gsheet_name"], $email, $upi);
            // Return HTTP status code 200, to signify that it has successfully marked attendee as paid
            $this->createResponse(200, '200: Successfully, marked attendee as paid');
        }
    }

    /**
     * Checks an input key against a key stored in a file. If it matches, store a cookie on the users browser.
     */
    public function authenticate() {

        // Gets a key from the URL from the form admin/authenticate?key=xyz
        $urlKey = $this->input->get('key');

        // Check if it matches a key we have stored in auth_props.json
        if ($urlKey != ADMIN_AUTH_PASSKEY) {
            echo("Key is incorrect");
            return false;
        }

        $payload = array(
            "key" => $urlKey,
            "iat" => microtime(),
        );

        $jwt = JWT::encode($payload, ADMIN_AUTH_JWTKEY);

        setcookie(self::AUTH_COOKIE_NAME, $jwt);

        echo 'Cookie set';
        return true;
    }


    /**
     * Check if a user has a specific cookie, and if they do, allow them to do something
     */
    public function checkCookie() {

        if(!isset($_COOKIE[self::AUTH_COOKIE_NAME])) {
            return false;
        }

        $jwt = $_COOKIE[self::AUTH_COOKIE_NAME];

        try {
            $decoded = JWT::decode($jwt, ADMIN_AUTH_JWTKEY, array('HS256'));
        } catch (Exception $e) {
            return false;
        }

        return $decoded->key == ADMIN_AUTH_PASSKEY;
    }

     /**
     * Checks the current payment status of the user with
     * their email or UPI through [GET].
     */
    public function paymentStatus() {
        $this->load->model('GoogleSheets_Model');

        // ONE OF THEM IS REQUIRED, EITHER.
        // get the members email and upi
        $email = $this->input->get('email');
        $upi = $this->input->get('upi');

        // If email and UPI both don't exist, return 412 to signify query params are not correct
        if (!$email && !$upi) {
            $this->createResponse(412, 'Queries not specified');
            return;
        }

        // Get the cell with priority on email, and then UPI - if both are not found, then $cell is null
        $cell = $email ? $this->GoogleSheets_Model->getCellCoordinate($email, 'B')
            : $this->GoogleSheets_Model->getCellCoordinate($upi, 'E');

        if (!$cell) {
            $this->createResponse(404, 'No user with email address/UPI found');
            return;
        }

        $cellColour = $this->GoogleSheets_Model->getCellColour($cell);

        // User has paid if cell colour is not white or uncoloured
        $hasUserPaid = $cellColour != "000000" && $cellColour != "ffffff";

        // Get attendance cell value
        $attendanceCellId = 'G' . $this->GoogleSheets_Model->convertCoordinateToArray($cell)[1];
        $attendanceCell = $this->GoogleSheets_Model->getCellContents($attendanceCellId, $attendanceCellId);
        $attendance = $attendanceCell ? $attendanceCell[0][0] : null;

        /**
         * 200 – OK, paymentMade = true` if `green` and `attendance=false` from the registration sheet
         * (this means the attendee has paid)
         */
        if ($hasUserPaid && $attendance != 'P') {
            $this->createResponse(200, 'User has paid', [ 'paymentMade' => true ]);
            $this->GoogleSheets_Model->markAsPresent($this->eventData["gsheet_name"], $email, $upi);
            return;
        }

        /**
         * 409 CONFLICT` if `green` and `attendance=true`, this means there is a duplicate email used
         */
        if ($hasUserPaid && $attendance == 'P') {
            $this->createResponse(409, 'Duplicate email used');
        }

        /**
         * 200 - OK, paymentMade = false` if `uncoloured` and `attendance=false` from the registration sheet
         * (this means the user has not paid)
         */
        if (!$hasUserPaid) {
            $this->createResponse(200, 'payment not made', [ 'paymentMade' => false ]);
        }

    }

}
