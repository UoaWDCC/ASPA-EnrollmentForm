<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class EnrollmentForm extends ASPA_Controller 
{

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

        // Test out functions here

	}
}

/* End of file EnrollmentForm.php */
/* Location: ./application/controllers/EnrollmentForm.php */