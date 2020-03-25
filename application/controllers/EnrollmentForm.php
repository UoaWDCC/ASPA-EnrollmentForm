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

	public function send_email() 
	{
        // pass in emailAddress & paymentMethod using ajax post
        $emailAddress = $this->input->post('emailAddress');
        $paymentMethod = $this->input->post('paymentMethod');

        // load EmailModel
        $this->load->model('EmailModel');

        // send email to specified email address using sendEmail function in EmailModel
        $this->EmailModel->sendEmail($emailAddress, $paymentMethod);
	}
	
	/**
	 * validate() is called in assets/js/enrollmentForm.js via ajax POST method.
	 * The functionality is to determine if the inputted email is of the correct:
	 *  - email format
	 *  - is an email on the email spreadsheet
	 */
	public function validate() {	
		$emailAddress = $this->input->post('emailAddress');	
		$this->load->model('Verification_Model');	
			
		if ($this->Verification_Model->has_user_paid($emailAddress)) {	
			$this->create_json('True', '', 'Success');	
			return;	
		}	
		if ($this->Verification_Model->is_email_on_sheet($emailAddress)){	
			$this->create_json('False', '', 'Error: signed up but not paid');	
		} else {	
			$this->create_json('False', '', 'Error: not signed up');	
		}	
	}

        $this->load->model('Gsheet_Interface_Model');
    }
    
    public function makeStripePayment() {
        
        $this->load->view('stripe.php');

    }

    public function LoadPaymentSucessful() {
        
        $data['session_id'] = $this->input->get('session_id');
        $this->load->view('redir.php',$data);
        
    }
}

/* End of file EnrollmentForm.php */
/* Location: ./application/controllers/EnrollmentForm.php */