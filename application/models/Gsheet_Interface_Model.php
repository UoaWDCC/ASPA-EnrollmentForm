<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Gsheet_Interface_Model extends CI_Model {

    private $client = FALSE;
    private $service = FALSE;
    private $spreadsheetId = FALSE;

    // Setting up current client
    function __construct()
    {
        $this->client = $this->client_setup();
        $this->service = $this->client[0];
        $this->spreadsheetId = $this->client[1];
    }

    // Setting up client function
    function client_setup()
    {
        require $this->getCurrentWorkingDir() . '/vendor/autoload.php';

        $client = new Google_Client();
        $client->setApplicationName('Google Sheets and PHP');
        $client->setScopes([\Google_Service_Sheets::SPREADSHEETS]);
        $client->setAccessType('offline');
        $client->setAuthConfig($this->getCurrentWorkingDir() . '/credentials.json');

        $service = new Google_Service_Sheets($client);

        return [$service, SPREADSHEETID];
    }

    // Switches any numerical value (between 1 to 26)
    function num_to_alpha_switch($in) {
        if (is_numeric($in)) {
            $alpha_arr = ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm',
                            'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z'];

            if ($in <= 26) {
                return $alpha_arr[$in - 1];
            }
        }
    }

    // Finds the current root directory
    function getCurrentWorkingDir()
    {
        // For Windows
        // $dir = shell_exec('echo %cd%');

        // For MacOS / Linux / CentOS server
        $dir = shell_exec('pwd');
        $stripped = trim($dir);
        return $stripped;
    }

    // Recording a new user to the sheet, with format
    // record_to_sheet(email_address, full_name, uoa_id, uoa_upi, payment_type=CASH, BANK, ONLINE, paymentmade=TRUE/FALSE)
    function record_to_sheet($email, $fullname, $paymenttype, $paymentmade)
    {
        $size = $this->get_sheet_size();
        $newrange = 'Sheet1!A' . ($size + 2);

        // Creating an array for record
        $timestamp = strval(date("d/m/Y h:i:s"));
        $values = [[$timestamp, $email, $fullname, '','', $paymenttype]];
        $body = new Google_Service_Sheets_ValueRange(['values' => $values]);

        // Setting input option to RAW text format (i.e no format parsing)
        // NB: Risk level = MED, may need some parsing for harmful injections into gsheet document
        $params = ['valueInputOption' => 'USER_ENTERED'];

        // Appends user information to sheet
        $result = $this->service->spreadsheets_values->update($this->spreadsheetId, $newrange, $body, $params);



        // Checks if payment made (TRUE/FALSE)
        if ($paymentmade) {

            // Highlight format
            $format = [
                "backgroundColor" => [
                    "red" => 0.7,
                    "green" => 0.9,
                    "blue" => 0.7
                ]
            ];

            $formatRange = [
                "startRowIndex" => $size + 1,
                "endRowIndex" => $size + 2,
            ];

            // Request body
            $request = new Google_Service_Sheets_BatchUpdateSpreadsheetRequest([
                'requests' => [
                    new Google_Service_Sheets_Request([
                        "repeatCell" => [
                            "fields" => "userEnteredFormat.backgroundColor",
                            "range" => $formatRange,
                            "cell" => [
                                "userEnteredFormat" => $format
            ]]])]]);

            // Sends request
            $response = $this->service->spreadsheets->batchUpdate($this->spreadsheetId, $request);
        }
    }

    // Returns size of gsheet as a numerical value
    function get_sheet_size()
    {
        $range = 'Sheet1!A2:A';
        $response = $this->service->spreadsheets_values->get($this->spreadsheetId, $range);
        return sizeof($response->getValues());
    }

    // Get value from sheet, e.g get_from_sheet('A1', 'B5').
    // NB: row can be string or text input
    function get_from_sheet($leftcorner, $rightcorner)
    {
        $range = 'Sheet1!' . $leftcorner . ":" . $rightcorner;

        $response = $this->service->spreadsheets_values->get($this->spreadsheetId, $range);

        // Display value received for testing and verification purposes – comment out for final build
        $values = $response->getValues();
        if (empty($values)) {
            return "ERROR: No data!";
        } else {

            // Values returned as an array...
            // e.g input: ('A1', 'B3'), output: [[A1, A2, A3], [B1, B2, B3]]
            return $values;
        }
    }
}
