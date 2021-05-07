<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require ('vendor/autoload.php');

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

    /**
     * Marks the attendee as paid by highlighting their row. 
     * It checks if either the email or upi is found in the spreadsheet. 
     * If it does, it highlights the specified row.
     */
    public function markAsPaid() {
        $this->load->model('GoogleSheets_Model');
        $this->load->model('Verification_Model');

        // ONE OF THEM IS REQUIRED, EITHER.
        // get the members email and upi
        $email = $this->input->get('email');
        $upi = $this->input->get('upi');

        // If email and UPI both don't exist
        if (!$email && !$upi) {
            // Return HTTP status code 412, to signify that both queries are not specified
            $this->output->set_status_header(412, "Queries not specified")->_display("Precondition failed");
            return;
        }

        // Get the cell with priority on email, and then UPI
        $cell = $email ? $this->GoogleSheets_Model->getCellCoordinate($email, 'B')
            : $this->GoogleSheets_Model->getCellCoordinate($upi, 'E');

        if (!$cell) {
            $this->output->set_status_header(404, "error")->_display("404: Attendee not found");
            return;
        }

        // Split up the cell column and row
        list(, $row) = $this->GoogleSheets_Model->convertCoordinateToArray($cell);

        // Check if the cell is coloured, if not highlight the cell with pink :)
        $isColoured = $this->GoogleSheets_Model->getCellColour($cell);
        if ($isColoured == '000000' || $isColoured == 'ffffff') {
            // Highlight this row since it is paid, placed inside this code block to prevent unnecessary calls
            $this->GoogleSheets_Model->highlightRow($row ,[0.968, 0.670, 0.886]);

            // Return HTTP status code 200, to signify that it has successfully marked attendee as paid
            $this->output->set_status_header(200)->_display("Successfully, marked attendee as paid");
        }
    }

    public function paymentStatus() {
        // TODO: ASPA-14
    }

}
