<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Handles all admin-checkup app related endpoints and views.
 * @property GoogleSheets_Model $GoogleSheets_Model
 * @property Verification_Model $Verification_Model
 * @property Email_Model $Email_Model
 * @property Stripe_Model $Stripe_Model
 * @property CI_Input $input
 */
class Admin extends ASPA_Controller
{
    public function markAsPaid() {
        include('./controllers/EnrollmentForm.php');
        //get google sheets
        //$this->load->model("GoogleSheets_Model");
        // //get verification model
        $this->load->model("Verification_Model");
        // //get stripe mode
        $this->load->model("Stripe_Model");
        
        // TODO: ASPA-31
        //get the members email and upi 
        //ONE OF THEM IS REQUIRED, EITHER.
        $email = $this->input->get('email');
        $upi = $this->input->get('upi');
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
        $isEmail = ($this->Verification_Model->isEmailOnSheet($email, REGISTRATION_SPREADSHEET_ID, $this->eventData['gsheet_name']));
        $isUpi = ($this->Verification_Model->isUpiOnSheet($upi, REGISTRATION_SPREADSHEET_ID, $this->eventData['gsheet_name']));
        // //if email or upi is not found in the google sheets.
        echo $isEmail; 
        echo $isUpi;
        if(!($isEmail) || !($isUpi)){
            $this->output->set_status_header(404, "Not found")->_display ("Attendee not found");
        }
        // if($email || $upi){
        //      $this->output->set_status_header(200, "it works")->_display ("Successfully, marked attendee's as paid");
        // }
        // else{
        //     $this->output->set_status_header(412, "queries not specified")->_display ("Queries not specified");
        // }
    }
    public function paymentStatus() {
        // TODO: ASPA-14
    }
}