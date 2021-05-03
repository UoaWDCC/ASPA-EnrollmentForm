<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require ('vendor/autoload.php');

/**
 * Class EnrollmentForm
 *
 * @property GoogleSheets_Model $GoogleSheets_Model
 * @property Verification_Model $Verification_Model
 * @property Email_Model $Email_Model
 * @property Stripe_Model $Stripe_Model
 * @property CI_Input $input
 */
class EnrollmentForm extends ASPA_Controller
{

    /**
     * @var mixed All the information for this event (retrieved from google sheet).
     */
    private $eventData;


    /**
     * EnrollmentForm constructor that runs every time before the web page loads.
     */
	function __construct() {
        parent::__construct();

        log_message('debug', "=====New Controller Function Initialized====");
        log_message('debug', "-- from IP address: ". $this->input->ip_address());

        // Load GSheets Model as this is used for everything
        $this->load->model("GoogleSheets_Model");

        // Get event details from spreadsheet from range A2 to size of spreadsheet
        $this->GoogleSheets_Model->setCurrentSheetName("CurrentEventDetails");
        $data = $this->GoogleSheets_Model->getCellContents('A2', 'C' . ($this->GoogleSheets_Model->getNumberOfRecords() + 2));

        // Important variables we care about
        $elements = ['time', 'date', 'location', 'title', 'tagline', 'price', 'acc_num', 'desc', 'gsheet_name', 'form_enabled'];

        // If the data from spreadsheet contains event details we are looking for, set them.
        for ($i = 0; $i < sizeof($data); $i++) {
            if (in_array($data[$i][0], $elements)) {
                $this->eventData[$data[$i][0]] = $data[$i][2];
            }
        }

        if ($this->eventData['gsheet_name']) {
            $this->GoogleSheets_Model->setCurrentSheetName($this->eventData['gsheet_name']);
        } else {
            // disable form if no event sheet is found.
            $this->eventData["form_enabled"] = False;
        }
	}

    public function loadData(){
        $eventTemp;
        // Load GSheets Model as this is used for everything
        $this->load->model("GoogleSheets_Model");

        // Get event details from spreadsheet from range A2 to size of spreadsheet
        $this->GoogleSheets_Model->setCurrentSheetName("CurrentEventDetails");
        $data = $this->GoogleSheets_Model->getCellContents('A2', 'C' . ($this->GoogleSheets_Model->getNumberOfRecords() + 2));

        // Important variables we care about
        $elements = ['time', 'date', 'location', 'title', 'tagline', 'price', 'acc_num', 'desc', 'gsheet_name', 'form_enabled'];

        // If the data from spreadsheet contains event details we are looking for, set them.
        for ($i = 0; $i < sizeof($data); $i++) {
            if (in_array($data[$i][0], $elements)) {
                $eventTemp[$data[$i][0]] = $data[$i][2];
            }
        }

        if ( $eventTemp['gsheet_name']) {
            $this->GoogleSheets_Model->setCurrentSheetName( $eventTemp['gsheet_name']);
        } else {
            // disable form if no event sheet is found.
            $eventTemp["form_enabled"] = False;
        }
        return  $eventTemp;
    }

    /**
     * The "home" page.
     */
	public function index()	{
        log_message('debug', "-- Index Function called");
        if (filter_var($this->eventData["form_enabled"], FILTER_VALIDATE_BOOLEAN)) {
            $this->load->view('EnrollmentForm', $this->eventData);
        } else {
            // TODO: Load a disabled view.
            echo "This ASPA form is currently disabled.";
        }
	}

	/**
     * POST request to validate an email.
     *
	 * Checks the following:
	 *  - Email is a valid format
	 *  - Email is on the ASPA membership spreadsheet
     *  - Email is not already a paid event member (to prevent duplicate payments)
	 */
	public function validate()
    {
        log_message('debug', "-- validate function called");
        $emailAddress = $this->input->post('emailAddress');

        if(!isset($emailAddress)) {
            $this->create_json('False', '', 'Error: Email not specified');
        }

        $this->load->model('Verification_Model');

        // Has user paid for the event already?
        if ($this->Verification_Model->hasUserPaidEvent($emailAddress, $this->eventData['gsheet_name'])) {
            $this->create_json('False', '', 'Error: already paid for event');
            return;
        }

        // Check if feature toggle for check membership payment is on
        if (CHECK_MEMBERSHIP_PAYMENT) {
            if ($this->Verification_Model->hasUserPaidMembership($emailAddress)) {
                $this->create_json('True', '', 'Success');
                return;
            } else if ($this->Verification_Model->isEmailOnSheet($emailAddress, MEMBERSHIP_SPREADSHEET_ID, MEMBERSHIP_SHEET_NAME)){
                $this->create_json('False', '', 'Error: signed up but not paid');
                return;
            } else {
                $this->create_json('False', '', 'Error: not signed up');
                return;
            }
        } else {
            if ($this->Verification_Model->isEmailOnSheet($emailAddress, MEMBERSHIP_SPREADSHEET_ID, MEMBERSHIP_SHEET_NAME)){
                $this->create_json('True', '', 'Success');
            } else {
                $this->create_json('False', '', 'Error: not signed up');
            }
        }
	}

    /**
     * When the stripe payment method is selected.
     */
    public function makeStripePayment()
    {
        log_message('debug', "-- makeStripePayment function called");
        $this->load->model('GoogleSheets_Model');
        $this->load->model('Verification_Model');

        // Receive data from form, method=POST
        $data['name'] = $this->input->post('name');
        $data['email'] = $this->input->post('email');

        // Stopping direct access to this method
        if ( !isset($data['name']) || !isset($data['email']) )
        {
            show_error("Sorry, this page you are requesting is either not found or you don't have permission to access this page. Error Code:001","404");
        }

        if (CHECK_MEMBERSHIP_PAYMENT) {
            $paid_member = ($this->Verification_Model->hasUserPaidMembership($data['email']));
            if (!$paid_member)
            {
                show_error("Something went wrong, your email was not found in the ASPA member list or haven't paid. Error Code: 002","500");
            }
        }

        // Only record if the email is not found
        if (!($this->Verification_Model->isEmailOnSheet($data['email'], REGISTRATION_SPREADSHEET_ID, $this->eventData['gsheet_name']))) {
            $this->GoogleSheets_Model->addNewRecord($data['email'],$data['name'],'Stripe');
        } else {
            // Email is found, so find the cell
            // Then edit the "How would you like your payment" to be of Stripe payment
            // Get the row of the specific email from google sheets
            $cell = $this->GoogleSheets_Model->getCellCoordinate($data['email'], 'B');
            if (!isset($cell))
            {
                show_error("Something went wrong, your email was not found in the ASPA member list.Error Code: 002","500");
            }

            // Split up the cell column and row
            list(, $row) = $this->GoogleSheets_Model->convertCoordinateToArray($cell);
            // Edit Payment method column (Column F)
            $this->GoogleSheets_Model->updatePaymentMethod($row, 'Stripe');
        }

        // Generate the stripe session ID
        $this->load->model('Stripe_Model');
        $data['session_id'] = $this->Stripe_Model->generateNewSessionId($data['email'], $this->eventData);

        // Initiate the stripe payment
        $this->load->view('stripe.php', $data);
    }

    /**
     * When an "offline" payment method (i.e. cash and bank transfer) was selected.
     */
    public function makeOfflinePayment()
    {
        log_message('debug', "-- makeOfflinePayment function called");
        $data['has_paid'] = false;
        $data['name'] = $this->input->post("name");
        $data["email"] = $this->input->post("email");
        $data['paymentMethod'] = $this->input->post("paymentMethod");

        if (!isset($data['name']) || !isset($data["email"]) || !isset($data['paymentMethod'])) {
            show_error("Something went wrong. Please contact uoa.wdcc@gmail.com. Error Code: 001","500");
        }

        $this->load->model("GoogleSheets_Model");
        $this->load->model("Verification_Model");
        $this->load->model('Email_Model');

        // Only record if the email is not found
        if (!($this->Verification_Model->isEmailOnSheet($data['email'], REGISTRATION_SPREADSHEET_ID, $this->eventData['gsheet_name']))) {
            $this->GoogleSheets_Model->addNewRecord($data['email'], $data['name'], ucfirst($data['paymentMethod']));
        } else {
            // Email is found, so find the cell then edit the "How would you like your payment" to be of Offline payment
            // Get the row of the specific email from google sheets
            $cell = $this->GoogleSheets_Model->getCellCoordinate($data['email'], 'B');

            // Split up the cell column and row
            list(, $row) = $this->GoogleSheets_Model->convertCoordinateToArray($cell);
            // Edit Payment method column (Column F)
            $this->GoogleSheets_Model->updatePaymentMethod($row, ucfirst($data['paymentMethod']));
        }

        // Send offline confirmation email
        $this->Email_Model->sendConfirmationEmail($data["name"], $data["email"], $data['paymentMethod'], $this->eventData);

        // Redirect to the page with grey tick
        $this->load->view('PaymentSuccessful.php', array_merge($this->eventData, $data));
    }

    /**
     * When a stripe payment was successfully made.
     */
    public function stripePaymentSuccessful()
    {
        log_message('debug', "-- StripePaymentSuccessful function called");
        $this->load->model('Stripe_Model');
        $this->load->model('GoogleSheets_Model');
        $this->load->model('Verification_Model');

        $data['session_id'] = $this->input->get('session_id');

        // Check if there is a session ID, or else redirect back to index
        if (!$data['session_id']) {
            show_error("Error occurred during redirection. If your payment was processed correctly, please contact uoa.wdcc@gmail.com. Error Code: 001","500");
        }

        // Sets boolean to whether payment was made
        $data['has_paid'] = $this->Stripe_Model->checkPayment($data['session_id']);

        // Checking if payment was made to their session and obtain their email
        $data['email'] = $this->Stripe_Model->getEmail($data['session_id']);

        if ($data['has_paid']) {
            // HighLight the row (get the user's email)
            // Get the row of the specific email from google sheets
            $cell = $this->GoogleSheets_Model->getCellCoordinate($data['email'], 'B');
            if (!isset($cell))
            {
                show_error("Something went wrong, your email was not found in the ASPA member list. Error Code: 002","500");
            }

            // Split up the cell column and row
            list(, $row) = $this->GoogleSheets_Model->convertCoordinateToArray($cell);

            // Get the name from the Google Sheet
            $data['name'] = $this->GoogleSheets_Model->getCellContents(('C' . $row), ('C' . $row))[0][0];
            
            /*
             * Send confirmation email if this is the first time the user has called the stripePaymentSuccessful()
             * function, as if the user is highlighted, this means that this function has been called already. This is
             * an important check to prevent sending duplicate emails to users if they refresh the confirmation page.
             */
            $alreadyHighlighted = $this->Verification_Model->hasUserPaidEvent($data['email'], $this->eventData['gsheet_name']);
            if (!$alreadyHighlighted) {
                $this->load->model('Email_Model');
                $this->Email_Model->sendConfirmationEmail($data['name'], $data['email'], "online", $this->eventData);

                // Highlight this row since it is paid, placed inside this code block to prevent unnecessary calls
                $this->GoogleSheets_Model->highlightRow($row ,[0.69803923, 0.8980392, 0.69803923]);
            }

            // Redirect to the page with green tick
            $this->load->view('PaymentSuccessful.php', array_merge($this->eventData, $data));
        } else {
            show_error("Something went wrong, your payment wasn't processed correctly. Please contact uoa.wdcc@gmail.com. Error Code: 003","500");
        }
    }

}
