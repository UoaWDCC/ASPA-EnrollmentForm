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
        
        //get the members email and upi 
        $this->load->model('GoogleSheets_Model');
        //get verification model
        $this->load->model('Verification_Model');
        //get stripe model
        $this->load->model('Stripe_Model');

        //code 404
        //ONE OF THEM IS REQUIRED, EITHER.
        $email = $this->input->get('email');
        $upi = $this->input->get('upi');

        $isEmail = $this->Verification_Model->isEmailOnSheet($email, REGISTRATION_SPREADSHEET_ID, $this->eventData['gsheet_name']);
        $isUpi = $this->Verification_Model->isUpiOnSheet($upi, REGISTRATION_SPREADSHEET_ID, $this->eventData['gsheet_name']);
        
        if($email || $upi ){
            //if email or upi is not found in the google sheets.
            if(!$isEmail && !$isUpi){
                $this->output->set_status_header(404, "error")->_display("Attendee not found");
                exit();
            }
            if($isEmail && !$isUpi){
                $cell = $this->GoogleSheets_Model->getCellCoordinate($email, 'B');
                $isColoured = $this->GoogleSheets_Model->getCellColour($cell);
            }
            else{
                $cell = $this->GoogleSheets_Model->getUpiCellCoordinate($upi, 'E');
                $isColoured = $this->GoogleSheets_Model->getCellColour($cell);

            }

            
            if (!isset($cell))
            {
                show_error("Something went wrong, your email was not found in the ASPA member list. Error Code: 002","500");
            }

            // Split up the cell column and row
            list(, $row) = $this->GoogleSheets_Model->convertCoordinateToArray($cell);
            
            if ($isColoured == '000000' || $isColoured == 'fffff') {

                // Highlight this row since it is paid, placed inside this code block to prevent unnecessary calls
                $this->GoogleSheets_Model->highlightRow($row ,[0.968, 0.670, 0.886]);
            }
            // $this->GoogleSheets_Model->highlightRow($row ,[0.968, 0.670, 0.886]);

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
