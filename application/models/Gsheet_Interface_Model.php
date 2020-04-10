<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Gsheet_Interface_Model extends CI_Model {

    private $service = null;
    private $spreadsheetId = null;
    private $sheetName = null;

    // Setting up current client
    function __construct()
    {
        $this->service = $this->service_setup();
        $this->spreadsheetId = SPREADSHEETID;
        $this->sheetName = SHEETNAME;
    }

    public function set_spreadsheetId($spreadsheetId, $sheetName=SHEETNAME) {
        $this->spreadsheetId = $spreadsheetId;
        $this->sheetName = $sheetName;
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
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $dir = shell_exec('echo %cd%');
        } else {
            $dir = shell_exec('pwd');
        }

        $stripped = trim($dir);
        return $stripped;
    }

    // Function to highlight a row a specific colour
    // IN: row_num (int), colour ([red, green, blue]) where values between 0 and 1
    function highlight_row($row_num, $colour) {

        $format = [
            "backgroundColor" => [
                "red" => $colour[0],
                "green" => $colour[1],
                "blue" => $colour[2]
            ]
        ];

        $formatRange = [
            "startRowIndex" => $row_num - 1,
            "endRowIndex" => $row_num,
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

        return TRUE;
    }

    // Recording a new user to the sheet, with format
    // record_to_sheet(email_address, full_name, uoa_id, uoa_upi, payment_type=CASH, BANK, ONLINE, paymentmade=TRUE/FALSE)
    function record_to_sheet($email, $fullname, $paymenttype, $paymentmade)
    {
        $size = $this->get_sheet_size() + 2;
        $newrange = $this->sheetName . '!A' . $size;

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
            $this->highlight_row($size, [0.69803923, 0.8980392, 0.69803923]);
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
    // IN:  $cell = 'A1'            OUT: 'ff0000'
    function get_cell_colour($cell)
    {
        $range = $this->sheetName . "!" . $cell;
        $query = [
            'ranges' => $range,
            'includeGridData' => True
        ];

        $response = $this->service->spreadsheets->get($this->spreadsheetId, $query);
        $spreadsheet = $response->getSheets();
        $colour_format = $spreadsheet[0]['data'][0]['rowData'][0]['values'][0];

        $rgb = [0, 0, 0];
        if ($colour_format["userEnteredFormat"]) {
            $rgb[0] = $colour_format["userEnteredFormat"]["backgroundColor"]["red"];
            $rgb[1] = $colour_format["userEnteredFormat"]["backgroundColor"]["green"];
            $rgb[2] = $colour_format["userEnteredFormat"]["backgroundColor"]["blue"];
        }

        $hex_string = sprintf("%02x%02x%02x", $rgb[0] * 255, $rgb[1] * 255, $rgb[2] * 255);

        return $hex_string;
    }

    // Get value from sheet, e.g get_from_sheet('A1', 'B5')
    function get_from_sheet($leftcorner, $rightcorner)
    {
        $range = $this->sheetName . '!' . $leftcorner . ":" . $rightcorner;

        $response = $this->service->spreadsheets_values->get($this->spreadsheetId, $range);

        // Display value received for testing and verification purposes – comment out for final build
        $values = $response->getValues();
        if (empty($values)) {
            return NULL;
        } else {

            // Values returned as an array...
            // e.g input: ('A1', 'B3'), output: [[A1, A2, A3], [B1, B2, B3]]
            return $values;
        }
    }

    // Returns [column, row] array from coordinate
    // IN: 'B4'     OUT: ['B', 4],   IN: 'AA45'   OUT: ['AA', 45]
    function split_column_row($range) {
        $column = '';
        for ($i = 0; $i < strlen($range); $i++) {
            if (ctype_alpha($range[$i])) {
                $column = $column . $range[$i];
            }
        }

        $row = '';
        for ($i = strlen($range) - 1; $i >= 0; $i--) {
            if (!ctype_alpha($range[$i])) {
                $row = $range[$i] . $row;
            }
        }

        return [$column, (int) $row];
    }


    // Gets the cell range value by finding an email match
    // IN: 'bobsmith@gmail.com', 'B'         OUT: 'B4'
    function get_cellrange($check_str, $column) {
        $check_str = strtolower($check_str);

        $range = [$column . '2', $column . ($this->get_sheet_size() + 1)];
        $emails_arr = $this->get_from_sheet($range[0], $range[1]);

        // Will return the cell for the first instance of email
        for ($i = 0; $i < sizeof($emails_arr); $i++) {
            if (strtolower($emails_arr[$i][0]) == $check_str) {
                // echo $emails_arr[$i][0] . ' = ' . $emails_str . "<br />";
                return $column . ($i + 2);
            }
        }

        // If email does not exist in spreadsaheet
        return NULL;
    }
}
