<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class EnrollmentForm extends CI_Controller {

	function __construct() {
		parent::__construct();
		// $this->load->helper();
		// $this->load->model();
	}

	public function index()
	{
		$this->load->view('EnrollmentForm');
	}

	public function sheetsapi_test()
	{
        $this->load->model('Gsheet_Interface_Model');


        // Records a user to spreadsheet
        // $this->Gsheet_Interface_Model->record_to_sheet('testemail@gmail.com', 'Test Person', 'CASH', TRUE);


        // Example read request
        $values = $this->Gsheet_Interface_Model->get_from_sheet('B1', 'B30');

        foreach ($values as $row) {
            if (empty($row)) {

            } else {
                foreach ($row as $value) {
                    echo $value . "<br />";
                }
            }
        }

        echo $this->Gsheet_Interface_Model->get_sheet_size();


	}

    public function send_email()
    {
        $emailAddress = "willyzhysh@gmail.com";
        $paymentMethod = "cash";

        $this->load->model('EmailModel');
        $this->EmailModel->sendEmail($emailAddress, $paymentMethod);
    }
}
