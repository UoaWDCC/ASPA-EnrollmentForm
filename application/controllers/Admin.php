<?php
defined('BASEPATH') OR exit('No direct script access allowed');
<<<<<<< HEAD

=======
require ('vendor/autoload.php');
>>>>>>> ed23162 (add http status header)
/**
 * Handles all admin-checkup app related endpoints and views.
 *  @property GoogleSheets_Model $GoogleSheets_Model
 */
class Admin extends ASPA_Controller
{

    public function markAsPaid() {
        // TODO: ASPA-31
<<<<<<< HEAD
        //get the members email and upi 
        //ONE OF THEM IS REQUIRED, EITHER.
        $email = $this->input->get('email');
        $upi = $this->input->get('UPI');

        //get google sheets
        $this->load-model('GoogleSheets_Model');
        //get verification model
        $this->load-model('Verficiation_Model');


=======

        $this->load->model('GoogleSheets_Model');
        //get verification model
        $this->load->model('Verification_Model');
        //get stripe model
        $this->load->model('Stripe_Model');
>>>>>>> ed23162 (add http status header)
        /** 
         * TODO LIST:
         * 
         * 1) IF Attendee is not found in the registration sheet return CODE 404
         * 2) CHECK IF EMAIL & UPI is NOT NULL and on sheets
         * 3) IF NOT RETURN HTTP CODE 412
         * 4) IF THEY DO, highlight the row as marked. RETURN HTTP CODE 200
         * 
         * 
         * SIDE NOT: POSSIBLY USE PAYMENT STATUS()
         * 
         * CODE 200: everything worked
         * CODE 412: Precondition failed if both query parameters were no specified 
         * CODE 404: NOT FOUND (if attendee was not found in the registration )
         * */ 

        //code 404
        //get the members email and upi 
        //ONE OF THEM IS REQUIRED, EITHER.
        $email = $this->input->get('email');
        $upi = $this->input->get('upi');
        //get google sheets
        $this->load->library('../controllers/EnrollmentForm.php');
        echo $this->EnrollmentForm->loadEventData();

        
        $isEmail = $this->Verification_Model->isEmailOnSheet($email, REGISTRATION_SPREADSHEET_ID, $this->eventData['gsheet_name']);
        $isUpi = $this->Verification_Model->isUpiOnSheet($upi, REGISTRATION_SPREADSHEET_ID, $this->eventData['gsheet_name']);

        //if email or upi is not found in the google sheets.
        if(!$isEmail){
            $this->output->set_status_header(404, "error")->_display("Attendee not found");
        }
        else{
            echo "found";
        }
        if($email || $upi ){
            $this->output->set_status_header(200)->_display("Successfully, marked attendee as paid");
        }
<<<<<<< HEAD

         
        if($email !== null && $upi !== null){
            // Get the row of the specific email from google sheets
            $cell = $this->GoogleSheets_Model->getCellCoordinate($email, 'B');
            if (!isset($cell))
            {
                show_error("Something went wrong, your email was not found in the ASPA member list. Error Code: 002","500");
            }

            // Split up the cell column and row
            list(, $row) = $this->GoogleSheets_Model->convertCoordinateToArray($cell);

            $alreadyHighlighted = $this->Verification_Model->hasUserPaidEvent($email, $this->eventData['gsheet_name']);
            if (!$alreadyHighlighted) {
                // Highlight this row since it is paid, placed inside this code block to prevent unnecessary calls
                $this->GoogleSheets_Model->highlightRow($row ,[0.69803923, 0.8980392, 0.69803923]);
                return var_dump(http_response_code(200));
            }
=======
        else{
            $this->output->set_status_header(412)->_display("Queries not specified");

>>>>>>> ed23162 (add http status header)
        }
    }

    public function paymentStatus() {
        // TODO: ASPA-14
    }

}
