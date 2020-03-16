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
        $this->Gsheet_Interface_Model->google_test();
	}

}
