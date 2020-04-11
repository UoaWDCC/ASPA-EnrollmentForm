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

	public function send_email($emailAddress = null, $paymentMethod = null) 
	{
        // pass in emailAddress & paymentMethod using ajax post if it has not already been passed in.
        // This is a way of overloading the method.
        if ($emailAddress == null){
            $emailAddress = $this->input->post('emailAddress');
            $paymentMethod = $this->input->post('paymentMethod');
        }

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
    
    public function makeStripePayment() 
    {
        // Receive data from form, method=POST
        $data['name'] = $this->input->post('name');
        $data['email'] = $this->input->post('email');

        $data['session_id'] = "id";

        // Put the data into spreadsheet
        $this->load->model('Gsheet_Interface_Model');
        $this->Gsheet_Interface_Model->record_to_sheet($data['email'],$data['name'],'Stripe',FALSE);

        //Generating the session id
        $this->load->model('Stripe_Model');
        $data['session_id'] = $this->Stripe_Model->GenSessionId($data['email']);

        // Initiate the stripe payment
        $this->load->view('stripe.php', $data);

    }

    public function StripePaymentSucessful() 
    {
        $this->load->model('Stripe_Model');
        $this->load->model('Gsheet_Interface_Model');

        $data['session_id'] = $this->input->get('session_id');

        // Check if there is a session ID, or else redirect back to index
        if (!$data['session_id']) {
            redirect(base_url());
            return;
        }

        // Sets boolean to whether payment was made
        $data['has_paid'] = $this->Stripe_Model->CheckPayment($data['session_id']);

        // Checking if payment was made to their session and obtain their email
        $data['email'] = $this->Stripe_Model->GetEmail($data['session_id']);

        if ($data['has_paid'])
        {
            // HighLight the row (get the user's email)
            // Get the row of the specific email from google sheets
            $cell = $this->Gsheet_Interface_Model->get_cellrange($data['email'], 'B');
            if (!isset($cell)) 
            { 
                show_error("Something went wrong, your email was not found in the ASPA member list",'002');
            }

            // Split up the cell column and row 
            list(, $row) = $this->Gsheet_Interface_Model->split_column_row($cell);
            // Highlight this row sicne it is paid
            $this->Gsheet_Interface_Model->highlight_row($row ,[0.69803923, 0.8980392, 0.69803923]);
		
	    // load EmailModel
            $this->load->model('EmailModel');
            // send email to specified email address using sendEmail function in EmailModel
            $this->EmailModel->sendEmail($data['email'], "Stripe");

            //Redirect to the page with green tick
            $this->load->view('PaymentSuccessful.php', $data);
        }
        else {
            show_error("Something went wrong, your payment wasn't processed correctly. Please contact uoa.wdcc@gmail.com",'001');
        }
    }

    public function IEPayPaymentSucessful() 
    {

        //Redirect to the page with green tick
        $this->load->view('PaymentSuccessful.php',$data);
        
    }

    public function HighlightGSheet()
    {

        
    }

    public function LoadOfflinePayment()
    {
        $data['has_paid'] = false;
        $data['name'] = $this->input->post("name");
        $data["email"] = $this->input->post("email");
        $data['paymentMethod'] = $this->input->post("paymentMethod");

        $this->load->model("Gsheet_Interface_Model");
        $this->Gsheet_Interface_Model->record_to_sheet($data['name'], $data['email'], ucfirst($data['paymentMethod']), $data['has_paid']);

		//Redirect to the page with grey tick
        $this->load->view('PaymentSuccessful.php', $data);
    }
}

/* End of file EnrollmentForm.php */
/* Location: ./application/controllers/EnrollmentForm.php */
