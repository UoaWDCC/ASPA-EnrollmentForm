<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Gsheet_Interface_Model extends CI_Model {

    private $service = FALSE;
    private $spreadsheetId = SPREADSHEETID;
    private $sheetName = SHEETNAME;

    // Setting up current client
    function __construct()
    {
        $this->service = $this->service_setup();
    }

    // Setting up service function
    function service_setup()
    {
        require $this->getCurrentWorkingDir() . '/vendor/autoload.php';

        $client = new Google_Client();
        $client->setApplicationName('Google Sheets and PHP');
        $client->setScopes([\Google_Service_Sheets::SPREADSHEETS]);
        $client->setAccessType('offline');
        $client->setAuthConfig($this->getCurrentWorkingDir() . '/credentials.json');

        $service = new Google_Service_Sheets($client);

        return $service;
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
        $newrange = $this->sheetName . '!A' . ($size + 2);

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
                    "red" => 0.69803923,
                    "green" => 0.8980392,
                    "blue" => 0.69803923
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
        $range = $this->sheetName . '!A2:A';
        $response = $this->service->spreadsheets_values->get($this->spreadsheetId, $range);
        return sizeof($response->getValues());
    }

    // Get colour of cell from sheet
    // IN:  $cell = 'A1'
    // OUT: 'ff0000'
    function get_cell_colour($cell)
    {
        $range = $this->sheetName . "!" . $cell;
        $query = [
            'ranges' => $range,
            'includeGridData' => True
        ];

        $response = $this->service->spreadsheets->get($this->spreadsheetId, $query);
        $spreadsheet = $response->getSheets();
        $colour_format = $spreadsheet[0]['data'][0]['rowData'][0]['values'][0]["userEnteredFormat"]["backgroundColor"];

        $rgb = [$colour_format["red"], $colour_format["green"], $colour_format["blue"]];

        $hex_string = sprintf("%02x%02x%02x", $rgb[0] * 255, $rgb[1] * 255, $rgb[2] * 255);

        return $hex_string;
    }

    // Get value from sheet, e.g get_from_sheet('A1', 'B5').
    // NB: row can be string or text input
    function get_from_sheet($leftcorner, $rightcorner)
    {
        $range = $this->sheetName . '!' . $leftcorner . ":" . $rightcorner;

        $response = $this->service->spreadsheets_values->get($this->spreadsheetId, $range);

        // Display value received for testing and verification purposes – comment out for final build
        $values = $response->getValues();
        if (empty($values)) {
            return FALSE;
        } else {

            // Values returned as an array...
            // e.g input: ('A1', 'B3'), output: [[A1, A2, A3], [B1, B2, B3]]
            return $values;
        }
    }
}
