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

        // // Records a user to spreadsheet
        // $this->Gsheet_Interface_Model->record_to_sheet('testemail@gmail.com', 'Test Person', 'CASH', FALSE);


        // // Example read request
        // $values = $this->Gsheet_Interface_Model->get_from_sheet('B1', 'B30');
        //
        // foreach ($values as $row) {
        //     if (empty($row)) {
        //
        //     } else {
        //         foreach ($row as $value) {
        //             echo $value . "<br />";
        //         }
        //     }
        // }
        // echo $this->Gsheet_Interface_Model->get_sheet_size();

        // // Highlighting a test row
        // $this->Gsheet_Interface_Model->highlight_row(4, [0.69803923, 0.8980392, 0.69803923]);


        // // Get colour of cell in hex, returns example like 'ffe100'
        // echo $this->Gsheet_Interface_Model->get_cell_colour('A21');

        // // Get cell range of an email
        // echo var_export($this->Gsheet_Interface_Model->get_cellrange('testemail@gmail.com', 'B'));
	}

	//this exists purely for testing purposes
	public function test_email_verify(){

		$this->load->model('Verification');

		// $testEmail = "startofsheet@aucklanduni.ac.nz";
		// $testEmail = "b3@gmail.com";
		// $testEmail = "b4@gmail.com";
		$testEmail = "b5@gmail.com";
		// $testEmail = "doesNotExist@gmail.com";
		// $testEmail = "wrongFormatEmail.com";
		// $testEmail = "%^&%*&illegalCharButRightFormat@gmail.com";

		echo "test email is: " . $testEmail . "<br><br>";

		if ($this->Verification->correct_email_format($testEmail)){
			echo "this is a correctly formatted email address. <br><br>";
		} else {
			echo "this is NOT a correctly formatted email address. <br><br>";
		}

		$placeholder = $this->Verification->is_email_on_sheet($testEmail);

		if ($placeholder){
			echo "EMAIL OWNER IS A MEMBER <br>";
		} else {
			echo "EMAIL OWNER IS NOT A MEMBER <br>";
		}
	}


}
