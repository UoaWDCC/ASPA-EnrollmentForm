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
    }
    
    public function makeStripePayment() 
    {
        // Receive data from form, method=POST
        $data['name'] = $this->input->post('name');
        $data['email'] = $this->input->post('email');

        // Put the data into spreadsheet
        $this->load->model('Gsheet_Interface_Model');
        $this->Gsheet_Interface_Model->record_to_sheet($data['email'],$data['name'],'Stripe',FALSE);

        // Initiate the stripe payment
        $this->load->view('stripe.php', $data);

    }

    public function StripePaymentSucessful() 
    {

        $this->load->model('Stripe_Model');
        $this->load->model('Gsheet_Interface_Model');

        $data['session_id'] = $this->input->get('session_id');
        

        $hasPaid = $this->Stripe_Model->CheckPayment($data['session_id']);
        $data['email'] = $this->Stripe_Model->GetEmail($data['session_id']);

        if ($hasPaid) 
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

            //Redirect to the page with green tick
            $this->load->view('PaymentSuccessful.php',$data);
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
		//Redirect to the page with grey tick
        $this->load->view('OfflinePayment.php',$data);
    }
}

/* End of file EnrollmentForm.php */
/* Location: ./application/controllers/EnrollmentForm.php */