<?php
defined('BASEPATH') or exit('No direct script access allowed');
require('vendor/autoload.php');

// require("assets/fpdf/fpdf.php");
// require("assets/phpqrcode/qrlib.php");
require("assets/tcpdf/tcpdf.php");

/**
 * Class EnrollmentForm
 *
 * @property GoogleSheets_Model $GoogleSheets_Model
 * @property Verification_Model $Verification_Model
 * @property Repository_Model $Repository_Model
 * @property Email_Model $Email_Model
 * @property Stripe_Model $Stripe_Model
 * @property CI_Input $input
 */
class EnrollmentForm extends ASPA_Controller
{

    /**
     * EnrollmentForm constructor that runs every time before the web page loads.
     */
    function __construct()
    {
        parent::__construct();

        log_message('debug', "=====New Controller Function Initialized====");
        log_message('debug', "-- from IP address: " . $this->input->ip_address());
    }

     public function test() {
        //  $this->load->model("Repository_Model");
        //  $this->Repository_Model->initClass(MEMBERSHIP_SPREADSHEET_ID, MEMBERSHIP_SHEET_NAME, REGISTRATION_SPREADSHEET_ID);
        //  $event = new Event(
        //      "id3",
        //      "name",
        //      "tagline",
        //      "desc",
        //      "location",
        //      "NOT EMAIl",
        //      "datetime",
        //      10,
        //      10.2,
        //      true);

        // $record = new Record(
        //   "ray@email.email",
        //   "id1",
        //   "this->timestamp,",
        //   "this->fullName",
        //   "this->upi",
        //   "this->paymentMethod",
        //   "this->paymentDate",
        //   TRUE,
        //   TRUE,
        // );

        // print_r("This is a test function."/*$this->Repository_Model->getOrganisation("1")*/);

        // create new PDF document
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Nicola Asuni');
        $pdf->SetTitle('TCPDF Example 050');
        $pdf->SetSubject('TCPDF Tutorial');
        $pdf->SetKeywords('TCPDF, PDF, example, test, guide');

        // set default header data
        $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE . ' 050', PDF_HEADER_STRING);

        // set header and footer fonts
        $pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        // set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

        // set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        // set some language-dependent strings (optional)
        if (@file_exists(dirname(__FILE__) . '/lang/eng.php')) {
            require_once(dirname(__FILE__) . '/lang/eng.php');
            $pdf->setLanguageArray($l);
        }

        // ---------------------------------------------------------

        // NOTE: 2D barcode algorithms must be implemented on 2dbarcode.php class file.

        // set font
        $pdf->SetFont('helvetica', '', 11);

        // add a page
        $pdf->AddPage();

        // print a message
        $txt = "You can also export 2D barcodes in other formats (PNG, SVG, HTML). Check the examples inside the barcode directory.\n";
        $pdf->MultiCell(70, 50, $txt, 0, 'J', false, 1, 125, 30, true, 0, false, true, 0, 'T', false);


        $pdf->SetFont('helvetica', '', 10);
        
        // -----------------------------------------------------------------

        // set style for barcode
        $style = array(
            'border' => 2,
            'vpadding' => 'auto',
            'hpadding' => 'auto',
            'fgcolor' => array(0, 0, 0),
            'bgcolor' => false, //array(255,255,255)
            'module_width' => 1, // width of a single module in points
            'module_height' => 1 // height of a single module in points
        );

        // QRCODE,H : QR-CODE Best error correction
        $pdf->write2DBarcode('www.tcpdf.org', 'QRCODE,H', 20, 210, 50, 50, $style, 'N');
        $pdf->Text(20, 205, 'QRCODE H');

        // new style
        $style = array(
            'border' => 2,
            'padding' => 'auto',
            'fgcolor' => array(0, 0, 255),
            'bgcolor' => array(255, 255, 64)
        );

        // QRCODE,H : QR-CODE Best error correction
        $pdf->write2DBarcode('www.tcpdf.org', 'QRCODE,H', 80, 210, 50, 50, $style, 'N');
        $pdf->Text(80, 205, 'QRCODE H - COLORED');

        // new style
        $style = array(
            'border' => false,
            'padding' => 0,
            'fgcolor' => array(128, 0, 0),
            'bgcolor' => false
        );

        // QRCODE,H : QR-CODE Best error correction
        $pdf->write2DBarcode('www.tcpdf.org', 'QRCODE,H', 140, 210, 50, 50, $style, 'N');
        $pdf->Text(140, 205, 'QRCODE H - NO PADDING');

        // ---------------------------------------------------------

        //Close and output PDF document
        $pdfEncoding = $pdf->Output('example_050.pdf', 'S');
        print_r($pdfEncoding);
     }

    /**
     * The "home" page.
     */
    public function index()
    {
        log_message('debug', "-- Index Function called");
        if (filter_var($this->eventData["form_enabled"], FILTER_VALIDATE_BOOLEAN)) {
            $this->load->view('EnrollmentForm', array_merge($this->eventData, $this->orgData));
        } else {
            $this->load->view('FormDisabled', $this->orgData);
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

        $this->load->model('Verification_Model');

        // If the email is of invalid format, return 412 error
        if (!isset($emailAddress) || !$this->Verification_Model->isValidEmail($emailAddress)) {
            $this->createResponse(412, 'Error: Format of email is invalid');
            return;
        }

        // If the email does not exist or is not on the membership spreadsheet, return 404 error
        if (!$this->Verification_Model->isEmailOnSheet($emailAddress, MEMBERSHIP_SPREADSHEET_ID, MEMBERSHIP_SHEET_NAME)) {
            $this->createResponse(404, 'Error: Email incorrect or not found on sheet');
            return;
        }

        // If the user has already paid for the event, return 409 error
        if ($this->Verification_Model->hasUserPaidEvent($emailAddress, $this->eventData['gsheet_name'])) {
            $this->createResponse(409, 'Error: already paid for event');
            return;
        }

        // If membership payment status is checked, and user's membership fee has not been paid, return 403 error
        if (CHECK_MEMBERSHIP_PAYMENT && !$this->Verification_Model->hasUserPaidMembership($emailAddress)) {
            $this->createResponse(403, "Error: signed up but not paid");
            return;
        }

        [$fullName, $UPI] = $this->Verification_Model->getMemberInfo($emailAddress);

        $this->createResponse(200, "Success", $fullName);
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
        $data['email'] = $this->input->post('email');
        [$data['name'], $data['upi']] = $this->Verification_Model->getMemberInfo($data["email"]);

        // Stopping direct access to this method
        if (!isset($data['name']) || !isset($data['email'])) {
            show_error("Sorry, this page you are requesting is either not found or you don't have permission to access this page. Error Code:001",
                       "404");
        }

        if (CHECK_MEMBERSHIP_PAYMENT) {
            $paid_member = ($this->Verification_Model->hasUserPaidMembership($data['email']));
            if (!$paid_member) {
                show_error("Something went wrong, your email was not found in the ASPA member list or haven't paid. Error Code: 002", "500");
            }
        }

        // Only record if the email is not found
        if (!($this->Verification_Model->isEmailOnSheet($data['email'], REGISTRATION_SPREADSHEET_ID, $this->eventData['gsheet_name']))) {
            $this->GoogleSheets_Model->addNewRecord($data['email'], $data['name'], $data['upi'], 'Stripe');
        } else {
            // Email is found, so find the cell
            // Then edit the "How would you like your payment" to be of Stripe payment
            // Get the row of the specific email from google sheets
            $cell = $this->GoogleSheets_Model->getCellCoordinate($data['email'], 'B');
            if (!isset($cell)) {
                show_error("Something went wrong, your email was not found in the ASPA member list.Error Code: 002", "500");
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
        $this->load->model("GoogleSheets_Model");
        $this->load->model("Verification_Model");
        $this->load->model('Email_Model');
        log_message('debug', "-- makeOfflinePayment function called");

        $this->load->model("GoogleSheets_Model");
        $this->load->model("Verification_Model");
        $this->load->model('Email_Model');

        $data['has_paid'] = false;
        $data["email"] = $this->input->post("email");
        $data['paymentMethod'] = $this->input->post("paymentMethod");
        [$data['name'], $data['upi']] = $this->Verification_Model->getMemberInfo($data["email"]);

        if (!isset($data['name']) || !isset($data["email"]) || !isset($data['paymentMethod'])) {
            show_error("Something went wrong. Please contact uoa.wdcc@gmail.com. Error Code: 001", "500");
        }

        // Only record if the email is not found
        if (!($this->Verification_Model->isEmailOnSheet($data['email'], REGISTRATION_SPREADSHEET_ID, $this->eventData['gsheet_name']))) {
            $this->GoogleSheets_Model->addNewRecord($data['email'], $data['name'], $data['upi'], ucfirst($data['paymentMethod']));
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
            show_error("Error occurred during redirection. If your payment was processed correctly, please contact uoa.wdcc@gmail.com. Error Code: 001", "500");
        }

        // Sets boolean to whether payment was made
        $data['has_paid'] = $this->Stripe_Model->checkPayment($data['session_id']);

        // Checking if payment was made to their session and obtain their email
        $data['email'] = $this->Stripe_Model->getEmail($data['session_id']);

        if ($data['has_paid']) {
            // HighLight the row (get the user's email)
            // Get the row of the specific email from google sheets
            $cell = $this->GoogleSheets_Model->getCellCoordinate($data['email'], 'B');
            if (!isset($cell)) {
                show_error("Something went wrong, your email was not found in the ASPA member list. Error Code: 002", "500");
            }

            // Split up the cell column and row
            list(, $row) = $this->GoogleSheets_Model->convertCoordinateToArray($cell);

            // Get the name from the Google Sheet
            // Default email message to "Hi there," if ASPA's membership spreadsheet does not have a member's name
            $data['name'] = $this->GoogleSheets_Model->getCellContents(('C' . $row), ('C' . $row)) === null ? 'there' : $this->GoogleSheets_Model->getCellContents(('C' . $row), ('C' . $row))[0][0];

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
                $this->GoogleSheets_Model->highlightRow($row, [0.69803923, 0.8980392, 0.69803923]);
            }

            // Redirect to the page with green tick
            $this->load->view('PaymentSuccessful.php', array_merge($this->eventData, $data));
        } else {
            show_error("Something went wrong, your payment wasn't processed correctly. Please contact uoa.wdcc@gmail.com. Error Code: 003", "500");
        }
    }
}
