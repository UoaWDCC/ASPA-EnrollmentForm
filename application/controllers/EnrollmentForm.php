<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class EnrollmentForm extends ASPA_Controller
{
    private $eventData = null;


    // Function that runs before web page loads
	function __construct() {
		parent::__construct();

        // Loading GSIM model into controller
        $this->load->model("Gsheet_Interface_Model");


        // Get event details from spreadsheet from range A2 to size of spreadsheet;
        $this->Gsheet_Interface_Model->set_spreadsheetId(SPREADSHEETID, "CurrentEventDetails");
        $data = $this->Gsheet_Interface_Model->get_from_sheet('A2', 'C' . ($this->Gsheet_Interface_Model->get_sheet_size() + 2));

        // Important variables we care about
        $elements = ['time', 'date', 'location', 'title', 'tagline', 'price', 'acc_num', 'desc', 'gsheet_name', 'form_enabled'];

        // If the data from spreadsheet contains event details we are looking for, set them.
        for ($i = 0; $i < sizeof($data); $i++) {
            if (in_array($data[$i][0], $elements)) {
                $this->eventData[$data[$i][0]] = $data[$i][2];
            }
        }

        // Sets spreadsheet ID back to

        if ($this->eventData['gsheet_name']) {
            $this->Gsheet_Interface_Model->set_spreadsheetId(SPREADSHEETID, $this->eventData['gsheet_name']);
        } else {
            // disable form if no event sheet is found.
            $this->eventData["form_enabled"] = False;
        }
	}


	public function index()
	{
        if (filter_var($this->eventData["form_enabled"], FILTER_VALIDATE_BOOLEAN)) {
            $this->load->view('EnrollmentForm', $this->eventData);
        } else {
            // TODO: Load a disabled view.
            echo "This ASPA form is currently disabled.";
        }
	}

	/**
	 * validate() is called in assets/js/enrollmentForm.js via ajax POST method.
	 * The functionality is to determine if the inputted email is of the correct:
	 *  - email format
	 *  - is an email on the email spreadsheet
	 */
	public function validate() {
        $emailAddress = $this->input->post('emailAddress');	

        if(!isset($emailAddress)){
            $this->create_json('False', '', 'Error: Email not specified');
        }

        $this->load->model('Verification_Model');
        
        // has user paid for the event already?
        if ($this->Verification_Model->has_user_paid_event($emailAddress, $this->eventData['gsheet_name'])) {
            $this->create_json('False', '', 'Error: already paid for event');
            return;
        }
        
        // has user paid for membership?
		if ($this->Verification_Model->has_user_paid_membership($emailAddress)) {	
			$this->create_json('True', '', 'Success');	
			return;	
		}	
		if ($this->Verification_Model->is_email_on_sheet($emailAddress, MEMBERSHIP_SPREADSHEETID, MEMBERSHIP_SHEETNAME)){	
			$this->create_json('False', '', 'Error: signed up but not paid');	
		} else {	
			$this->create_json('False', '', 'Error: not signed up');	
        }	
	}

    public function makeStripePayment()
    {
        $this->load->model('Gsheet_Interface_Model');
        $this->load->model('Verification_Model');

        // Receive data from form, method=POST
        $data['name'] = $this->input->post('name');
        $data['email'] = $this->input->post('email');

        // Stopping direct access to this method
        if ( !isset($data['name']) || !isset($data['email']) )
        {
            show_error("Sorry, this page you are requesting is either not found or you don't have permission to access this page. Error Code:001","404");
        }
        $paid_member = ($this->Verification_Model->has_user_paid_membership($data['email']));
        if ( !$paid_member )
        {
            show_error("Something went wrong, your email was not found in the ASPA member list or haven't paid. Error Code: 002","500");
        }

        // only record if the email is not found
        if (!($this->Verification_Model->is_email_on_sheet($data['email'], SPREADSHEETID, $this->eventData['gsheet_name']))) {
            $this->Gsheet_Interface_Model->record_to_sheet($data['email'],$data['name'],'Stripe',FALSE);
        } else {
            // email is found, so find the cell
            // then edit the "How would you like your payment" to be of Stripe payment
            // Get the row of the specific email from google sheets
            $cell = $this->Gsheet_Interface_Model->get_cellrange($data['email'], 'B');
            if (!isset($cell)) 
            { 
                show_error("Something went wrong, your email was not found in the ASPA member list.Error Code: 002","500");
            }

            // Split up the cell column and row 
            list(, $row) = $this->Gsheet_Interface_Model->split_column_row($cell);
            // Edit Payment method column (Column F)
            $this->Gsheet_Interface_Model->update_payment_method($row, 'Stripe');
        }

        //Generating the session id
        $this->load->model('Stripe_Model');
        $data['session_id'] = $this->Stripe_Model->GenSessionId($data['email'], $this->eventData);

        // Initiate the stripe payment
        $this->load->view('stripe.php', $data);

    }

    public function StripePaymentSuccessful()
    {
        $this->load->model('Stripe_Model');
        $this->load->model('Gsheet_Interface_Model');
        $this->load->model('Verification_Model');

        $data['session_id'] = $this->input->get('session_id');

        // Check if there is a session ID, or else redirect back to index
        if (!$data['session_id']) {
            show_error("Error occurred during redirection. If your payment was processed correctly, please contact uoa.wdcc@gmail.com. Error Code: 001","500");
        }

        // Sets boolean to whether payment was made
        $data['has_paid'] = $this->Stripe_Model->CheckPayment($data['session_id']);

        // Checking if payment was made to their session and obtain their email
        $data['email'] = $this->Stripe_Model->GetEmail($data['session_id']);

        // Check if this is the first time StripePaymentSuccessful is shown
        // Boolean variable used to determine whether we should send an email
        $alreadyHighlighted = $this->Verification_Model->has_user_paid_event($data['email'], $this->eventData['gsheet_name']);

        if ($data['has_paid'])
        {
            // HighLight the row (get the user's email)
            // Get the row of the specific email from google sheets
            $cell = $this->Gsheet_Interface_Model->get_cellrange($data['email'], 'B');
            if (!isset($cell))
            {
                show_error("Something went wrong, your email was not found in the ASPA member list. Error Code: 002","500");
            }

            // Split up the cell column and row
            list(, $row) = $this->Gsheet_Interface_Model->split_column_row($cell);
            // Highlight this row sicne it is paid
            $this->Gsheet_Interface_Model->highlight_row($row ,[0.69803923, 0.8980392, 0.69803923]);

            // Send a confirmation email only if it wasn't previously highlighted
            if (!$alreadyHighlighted)
            {
            $this->load->model('EmailModel');
            $this->EmailModel->sendEmail($data['email'], "online", $this->eventData);
            }

            //Redirect to the page with green tick
            $this->load->view('PaymentSuccessful.php', array_merge($this->eventData, $data));
        }
        else {
            show_error("Something went wrong, your payment wasn't processed correctly. Please contact uoa.wdcc@gmail.com. Error Code: 003","500");
        }
    }

    public function LoadOfflinePayment()
    {
        $data['has_paid'] = false;
        $data['name'] = $this->input->post("name");
        $data["email"] = $this->input->post("email");
        $data['paymentMethod'] = $this->input->post("paymentMethod");

        if (!isset($data['name']) || !isset($data["email"]) || !isset($data['paymentMethod'])) 
        {
            show_error("Something went wrong. Please contact uoa.wdcc@gmail.com. Error Code: 001","500");
        }

        $this->load->model("Gsheet_Interface_Model");
        $this->load->model("Verification_Model");
        $this->load->model('EmailModel');

        // only record if the email is not found
        if (!($this->Verification_Model->is_email_on_sheet($data['email'], SPREADSHEETID, $this->eventData['gsheet_name']))) {
            $this->Gsheet_Interface_Model->record_to_sheet($data['email'], $data['name'], ucfirst($data['paymentMethod']), $data['has_paid']);
        } else {
            // email is found, so find the cell
            // then edit the "How would you like your payment" to be of Offline payment
            // Get the row of the specific email from google sheets
            $cell = $this->Gsheet_Interface_Model->get_cellrange($data['email'], 'B');

            // Split up the cell column and row 
            list(, $row) = $this->Gsheet_Interface_Model->split_column_row($cell);
            // Edit Payment method column (Column F)
            $this->Gsheet_Interface_Model->update_payment_method($row, $data['paymentMethod']);
        }

        // Send offline confirmation email
        $this->EmailModel->sendEmail($data["email"], $data['paymentMethod'], $this->eventData);

		//Redirect to the page with grey tick
        $this->load->view('PaymentSuccessful.php', array_merge($this->eventData, $data));
    }
}

/* End of file EnrollmentForm.php */
/* Location: ./application/controllers/EnrollmentForm.php */
