<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require ('vendor/autoload.php');
/**
 * Handles all admin-checkup app related endpoints and views.
 *  @property GoogleSheets_Model $GoogleSheets_Model
 */
class Admin extends ASPA_Controller
{

    /**
     * Marks the attendee as paid by highlighting their row. 
     * It checks if either the email or upi is found in the spreadsheet. 
     * If it does, it highlights the specified row
     * 
     */

    public function markAsPaid() {
    
        $this->load->model('GoogleSheets_Model');
        //get verification model
        $this->load->model('Verification_Model');

        //ONE OF THEM IS REQUIRED, EITHER.
        //get the members email and upi 
        $email = $this->input->get('email');
        $upi = $this->input->get('upi');

        //validate if email or upi is found in the sheet
        $isEmail = $this->Verification_Model->isEmailOnSheet($email, REGISTRATION_SPREADSHEET_ID, $this->eventData['gsheet_name']);
        $isUpi = $this->Verification_Model->isUpiOnSheet($upi, REGISTRATION_SPREADSHEET_ID, $this->eventData['gsheet_name']);
        
        if($email || $upi ){

            //if email or upi is not found in the google sheets.
            if(!$isEmail && !$isUpi){
                //return HTTP status code 404, to state that both queries were specified BUT are not found in the spreadsheet
                return $this->output->set_status_header(404, "error")->_display("Attendee not found");
                exit();
            }
            else if($isEmail && $isUpi){
                //This is to check if both queries were inputted
                //verify if they are on the same row number, if not return an error
                $isSameRow = $this->Verification_Model->isUserSameRow($email, $upi);
                if(!$isSameRow){
                    return $this->output->set_status_header(404, "error")->_display("Attendee not the same row");
                    exit();
                }
            }

            if($isEmail && !$isUpi){
                //If email is only found, find the cell that contains email
                $cell = $this->GoogleSheets_Model->getCellCoordinate($email, 'B');
                $isColoured = $this->GoogleSheets_Model->getCellColour($cell);
            } else {
                //If upi is only found, find the cell that contains upi
                $cell = $this->GoogleSheets_Model->getCellCoordinate($upi, 'E');
                $isColoured = $this->GoogleSheets_Model->getCellColour($cell);
            }
   
            if (!isset($cell))
            {
                show_error("Something went wrong, your email was not found in the ASPA member list. Error Code: 002","500");
            }

            // Split up the cell column and row
            list(, $row) = $this->GoogleSheets_Model->convertCoordinateToArray($cell);
            
            // Check if the cell is coloured, if not hightlight the cell with pink :)
            if ($isColoured == '000000' || $isColoured == 'fffff') {

                // Highlight this row since it is paid, placed inside this code block to prevent unnecessary calls
                $this->GoogleSheets_Model->highlightRow($row ,[0.968, 0.670, 0.886]);
            }

            //return HTTP status code 200, to signify that it has successfully marked attendee as paid
            return $this->output->set_status_header(200)->_display("Successfully, marked attendee as paid");
        } else {
            //return HTTP status code 412, to signify that both queries are not specified
            return $this->output->set_status_header(412)->_display("Queries not specified");
        }
    }

    public function paymentStatus() {
        // TODO: ASPA-14
    }

}
