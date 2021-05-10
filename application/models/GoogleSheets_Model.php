<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Handles interactions with the Google Sheets API.
 */
class GoogleSheets_Model extends CI_Model {

    private $service;
    private $spreadsheetId;
    private $sheetName = null;


    /**
     * @throws \Google\Exception
     */
    function __construct()
    {
        parent::__construct();

        $this->service = $this->service_setup();
        $this->spreadsheetId = REGISTRATION_SPREADSHEET_ID;
    }

    /**
     * Sets the current sheet ID that this model will query data from.
     *
     * @param string $spreadsheetId The Google Sheet ID.
     */
    public function setSpreadsheetId($spreadsheetId)
    {
        $this->spreadsheetId = $spreadsheetId;
    }

    /**
     * Sets the current sheet name that this model will query from.
     *
     * @param string $sheetName The human readable string of the sheet's name.
     */
    public function setCurrentSheetName($sheetName)
    {
        $this->sheetName = $sheetName;
    }

    /**
     * Highlights a specific row a specific colour.
     *
     * @param integer $row_num The row to highlight.
     * @param int[] $colour A colour array array [red, green, blue].
     *
     * @return bool
     */
    public function highlightRow($row_num, $colour)
    {
        $format = [
            "backgroundColor" => [
                "red" => $colour[0],
                "green" => $colour[1],
                "blue" => $colour[2]
            ]
        ];

        $formatRange = [
            "sheetId" => $this->getSheetId($this->sheetName),
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

    /**
     * Records a new user to the registration sheet.
     *
     * @param string $email The email of the user to record.
     * @param string $fullName The full name of the user.
     * @param string $paymentMethod The type of payment the user is selecting.
     */
    public function addNewRecord($email, $fullName, $paymentMethod)
    {
        $size = $this->getNumberOfRecords() + 2;
        $range = $this->sheetName . '!A' . $size;

        // Creating an array for record
        $timestamp = strval(date("d/m/Y h:i:s"));
        $values = [[$timestamp, $email, $fullName, '','', $paymentMethod]];
        $body = new Google_Service_Sheets_ValueRange(['values' => $values]);

        // Setting input option to RAW text format (i.e no format parsing)
        // NB: Risk level = MED, may need some parsing for harmful injections into document
        $params = ['valueInputOption' => 'USER_ENTERED'];

        // Appends user information to sheet
        $result = $this->service->spreadsheets_values->update($this->spreadsheetId, $range, $body, $params);
    }

    /**
     * Mark an attendees attendance in the google sheets
     *
     * @param string $eventName The name of the event
     * @param string $email The email of the user to record.
     * @param string @upi The upi of the user if it exists
     */
    public function markAsPresent($eventName, $email = null, $upi = null)
    {
        // This should probably be in a try/catch block
        if (!$email && !$upi) {
            throw new Exception("You need to enter an email or UPI.");
        }
        
        //  Navigate to the correct spreadsheet
        $this->setCurrentSheetName($eventName);

        //  If they do exist, find the cell coordinates where you want to place a 'P'
        $emailCell = $this->getCellCoordinate($email, 'B');
        $upiCell = $this->getCellCoordinate($upi, 'E');

        // If either $emailCell or $upiCell is defined, then get the row number with priority on email. Otherwise, set $row to null
        list(, $row) = ($emailCell || $upiCell) ? $this->convertCoordinateToArray($emailCell ?? $upiCell) : null;

        // If row is not defined (i.e. no user row was found), return false
        if (!$row) {
            return false;
        }

        $range = $this->sheetName . "!G" . $row;
        $values = [["P"]];

        $requestBody = new Google_Service_Sheets_ValueRange(['values' => $values]);

        // Setting input option to RAW text format (i.e no format parsing)
        // NB: Risk level = MED, may need some parsing for harmful injections into gsheet document
        $params = ['valueInputOption' => 'USER_ENTERED'];

        // Appends user information to sheet
        $response = $this->service->spreadsheets_values->update($this->spreadsheetId, $range, $requestBody, $params);

        return true;
    }

    /**
     * Returns the number of records in the registration sheet.
     *
     * @return int Number of records.
     */
    public function getNumberOfRecords()
    {
        $range = $this->sheetName . '!A2:A';
        $response = $this->service->spreadsheets_values->get($this->spreadsheetId, $range);

        if ($response->getValues()) {
            return sizeof($response->getValues());
        } else {
            return 0;
        }
    }

    /**
     * Returns a set of values (as a 2D array) from the sheet based on the two
     * corners selected (inclusive).
     *
     * @param string $leftCorner The left corner as a Google coordinate (e.g. A1).
     * @param string $rightCorner The right corner as a Google coordinate (e.g. C5).
     *
     * @return array|null Array returned is a 2D array with the values.
     */
    public function getCellContents($leftCorner, $rightCorner)
    {
        $range = $this->sheetName . '!' . $leftCorner . ":" . $rightCorner;

        $response = $this->service->spreadsheets_values->get($this->spreadsheetId, $range);

        // Display value received for testing and verification purposes – comment out for final build
        $values = $response->getValues();
        if (empty($values)) {
            return NULL;
        } else {

            // Values returned as an array...
            // e.g input: ('A1', 'B3'), output: [[A1, A2, A3], [B1, B2, B3]]
            return $values;
        }
    }

    /**
     * Splits a Google defined coordinate string (e.g. B4) to a array with
     * the x alphabetical and integer coordinates split.
     *
     * @param string $range The google defined coordinate (e.g. B4)
     *
     * @return array (e.g. ['B', 4]).
     */
    function convertCoordinateToArray($range)
    {
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

    /**
     * Gets the cell range value by finding an email match.
     *
     * @param string $check_str The value to check for.
     * @param string $column The column letter to check in (e.g. B).
     *
     * @return string|null The Google coordinate (e.g. B4).
     */
    public function getCellCoordinate($check_str, $column)
    {
        $check_str = strtolower($check_str);

        // This will return an array of values in the column we are checking in
        $columnCells = $this->getCellContents($column . '2', $column . ($this->getNumberOfRecords() + 1));

        // Will return the cell for the first instance of email
        for ($i = 0; $i < sizeof($columnCells); $i++) {
            // If there is no content in a cell, the cell will have a length of 0.
            if (sizeof($columnCells[$i]) > 0) {
                if (strtolower($columnCells[$i][0]) === $check_str) {
                    return $column . ($i + 2);
                }
            }
        }

        // If email does not exist in this sheet
        return NULL;
    }

    /**
     * Updates the cell for payment method to be of $paymentType. This is called
     * in EnrollmentForm.php when the email is found in the google sheets.
     *
     * No duplicate entries are allowed and so the payment method is edited to the chosen type.
     *
     * @param integer $row The number of the row to change payment method for.
     * @param string $paymentMethod The type of payment (i.e. Cash, Transfer, Stripe, etc.).
     */
    public function updatePaymentMethod($row, $paymentMethod)
    {
        $range = $this->sheetName . "!F" . $row;
        $values = [[$paymentMethod]];
        $requestBody = new Google_Service_Sheets_ValueRange(['values' => $values]);

        // Setting input option to RAW text format (i.e no format parsing)
        // NB: Risk level = MED, may need some parsing for harmful injections into gsheet document
        $params = ['valueInputOption' => 'USER_ENTERED'];

        // Appends user information to sheet
        $response = $this->service->spreadsheets_values->update($this->spreadsheetId, $range, $requestBody, $params);
    }

    /**
     * Returns the google generated Sheet ID by passing `sheetName`.
     *
     * @param $sheetName
     *
     * @return string|null
     */
    public function getSheetId($sheetName)
    {
        // Gets all sheets from spreadsheet
        $sheets = $this->service->spreadsheets->get($this->spreadsheetId)["sheets"];

        // Iterates over sheets, returning first sheetId with matching name
        foreach ($sheets as $sheet) {
            if ($sheet['properties']['title'] == $sheetName) {
                return $sheet['properties']['sheetId'];
            }
        }

        return null;
    }

    /**
     * Returns the colour (in hex) of a cell.
     *
     * @param string $cell The cell to select in coordinate form (e.g. "A1").
     *
     * @return string Cell colour in hex format (e.g. "ff0000").
     */
    public function getCellColour($cell)
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

        return sprintf("%02x%02x%02x", $rgb[0] * 255, $rgb[1] * 255, $rgb[2] * 255);
    }

    /**
     * Sets up the GoogleSheets service we will make requests with.
     *
     * @return Google_Service_Sheets
     * @throws \Google\Exception
     */
    private function service_setup()
    {
        $client = new Google_Client();
        $client->setApplicationName('Google Sheets and PHP');
        $client->setScopes([\Google_Service_Sheets::SPREADSHEETS]);
        $client->setAccessType('offline');
        $client->setAuthConfig(getProjectDir() . '/private_keys/google_credentials.json');

        return new Google_Service_Sheets($client);
    }
}