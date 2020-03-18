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

        // Prints any value from spreadsheet
        echo $this->Gsheet_Interface_Model->get_from_sheet("A", 1);

        // Records a user to spreadsheet
        $this->Gsheet_Interface_Model->record_to_sheet('testemail@gmail.com', 'Test Person', 'CASH', TRUE);
	}
}
