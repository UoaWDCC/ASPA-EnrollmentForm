<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class EnrollmentForm extends ASPA_Controller 
{

	function __construct() {
		parent::__construct();
	}

	public function index()
	{
		$this->load->view('EnrollmentForm');
	}
}

/* End of file EnrollmentForm.php */
/* Location: ./application/controllers/EnrollmentForm.php */