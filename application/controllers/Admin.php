<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require ('vendor/autoload.php');
/**
 * Handles all admin-checkup app related endpoints and views.
 *  @property GoogleSheets_Model $GoogleSheets_Model
 */
class Admin extends ASPA_Controller
{

    public function markAsPaid() {
        // TODO: ASPA-31

        $this->load->model('GoogleSheets_Model');
        //get verification model
        $this->load->model('Verification_Model');
        //get stripe model
        $this->load->model('Stripe_Model');
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
        // $this->load->library('../controllers/EnrollmentForm.php');
        // echo $this->EnrollmentForm->loadEventData();
        $eventData;
        $data = $this->GoogleSheets_Model->getCellContents('A2', 'C' . ($this->GoogleSheets_Model->getNumberOfRecords() + 2));

        // Important variables we care about
        $elements = ['time', 'date', 'location', 'title', 'tagline', 'price', 'acc_num', 'desc', 'gsheet_name', 'form_enabled'];

        // If the data from spreadsheet contains event details we are looking for, set them.
        for ($i = 0; $i < sizeof($data); $i++) {
            if (in_array($data[$i][0], $elements)) {
                $this->eventData[$data[$i][0]] = $data[$i][2];
            }
        }
        
        $eventData;

        // $this->GoogleSheets_Model->setCurrentSheetName("CurrentEventDetails");
        // $data = $this->GoogleSheets_Model->getCellContents('A2', 'C' . ($this->GoogleSheets_Model->getNumberOfRecords() + 2));

        // // Important variables we care about
        // $elements = ['time', 'date', 'location', 'title', 'tagline', 'price', 'acc_num', 'desc', 'gsheet_name', 'form_enabled'];

        // // If the data from spreadsheet contains event details we are looking for, set them.
        // for ($i = 0; $i < sizeof($data); $i++) {
        //     if (in_array($data[$i][0], $elements)) {
        //         $this->eventData[$data[$i][0]] = $data[$i][2];
        //     }
        // }

        // if ($this->eventData['gsheet_name']) {
        //     $this->GoogleSheets_Model->setCurrentSheetName($this->eventData['gsheet_name']);
        // } else {
        //     // disable form if no event sheet is found.
        //     $this->eventData["form_enabled"] = False;
        // }
        
        // $isEmail = $this->Verification_Model->isEmailOnSheet($email, REGISTRATION_SPREADSHEET_ID, $this->eventData['gsheet_name']);
        // $isUpi = $this->Verification_Model->isUpiOnSheet($upi, REGISTRATION_SPREADSHEET_ID, $this->eventData['gsheet_name']);

        if($email || $upi ){
            //if email or upi is not found in the google sheets.
            if(!$isEmail){
                $this->output->set_status_header(404, "error")->_display("Attendee not found");
            
            }
            $this->output->set_status_header(200)->_display("Successfully, marked attendee as paid");
        }
        else{
            $this->output->set_status_header(412)->_display("Queries not specified");

        }
    }

    public function paymentStatus() {
        // TODO: ASPA-14
    }

}
