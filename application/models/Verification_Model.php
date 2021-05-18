<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Verification_Model
 *
 * @property GoogleSheets_Model $GoogleSheets_Model
 */
class Verification_Model extends CI_Model {
    
    private $addresses = array();

    /**
     * Checks if an email/user is on the spreadsheet.
     *
     * @param $emailAddress
     * @param $sheetId
     * @param $sheetName
     *
     * @return bool
     */
    // TODO: Make this more specific to the membership spreadsheet
    function isEmailOnSheet($emailAddress, $sheetId, $sheetName)
    {
        // If format of email is incorrect, return false
        if (!Verification_Model::isValidEmail($emailAddress)) {
            return false;
        }

        $this->load->model('GoogleSheets_Model');
        $this->GoogleSheets_Model->setSpreadsheetId($sheetId);
        $this->GoogleSheets_Model->setCurrentSheetName($sheetName);

        $sheetSize = $this->GoogleSheets_Model->getNumberOfRecords();

        // An array of array of all existing emails, i.e. [[email1], [email2], [email3]]
        $this->addresses = array_column($this->GoogleSheets_Model->getCellContents('B2', 'B' . ($sheetSize+1)), 0);

        // Return true if email exists in google sheet
        return in_array($emailAddress, $this->addresses);
    }

    /**
     * Checks if a user has a paid membership on the membership spreadsheet.
     *
     * @param string $emailAddress The email of the user.
     *
     * @return bool
     */
    public function hasUserPaidMembership($emailAddress)
    {
        if (!$this->isEmailOnSheet($emailAddress, MEMBERSHIP_SPREADSHEET_ID, MEMBERSHIP_SHEET_NAME)) {
            return false;
        }

        // Given that the email exists in the sheet, find its index
        $emailKey = array_search($emailAddress, $this->addresses);

        // Convert key to google coordinate
        $emailIndex = 'B' . ($emailKey+2);

        // Check the cell colour of the email cell
        $colourIs = $this->GoogleSheets_Model->getCellColour($emailIndex);
        
        // Uncoloured cells return as 000000 (or sometimes ffffff because google sheets is extra like that)
        // Members who have paid their membership fee are highlighted in a different colour from the default white
        // TODO: Correct this assumption and make this more reliable
        $hasPaidMembership = $colourIs != '000000' && $colourIs != 'ffffff';
        return $hasPaidMembership;
    }

    /**
     * Checks if the user has paid for the event in the event registration
     * google sheets. This is done through checking if the user is highlighted
     * a specific shade of green (green = paid).
     *
     * @param $emailAddress
     * @param $sheetName
     *
     * @return bool
     */
    public function hasUserPaidEvent($emailAddress, $sheetName)
    {
        if (!$this->isEmailOnSheet($emailAddress, REGISTRATION_SPREADSHEET_ID, $sheetName)) {
            return false;
        }

        // Given that the email exists in the sheet, find its index
        $emailKey = array_search($emailAddress, $this->addresses);

        // Convert key to google coordinate
        $emailIndex = 'B' . ($emailKey+2);

        log_message("debug", "INDEX: " . $emailIndex);


        // Check the cell colour of the email cell
        $colourIs = $this->GoogleSheets_Model->getCellColour($emailIndex);

        log_message("debug", "COLOUR: " . $colourIs);

        // Uncoloured cells return as 000000 (or sometimes ffffff because google sheets is extra like that)
        if ($colourIs == '000000' || $colourIs == 'ffffff') {
            return false;
        } else {
            return true;
        }
    }



    /**
     * Get user's full name, UPI and UID on the membership spreadsheet.
     *
     * @param string $emailAddress The email of the user.
     *
     * @return array [full_name, UPI]
     */
    public function getMemberInfo($emailAddress)
    {
        $this->load->model("GoogleSheets_Model");

        if (!$this->isEmailOnSheet($emailAddress, MEMBERSHIP_SPREADSHEET_ID, MEMBERSHIP_SHEET_NAME)) {
            return false;
        }

         // Given that the email exists in the sheet, find its index
        $emailKey = array_search($emailAddress, $this->addresses);

        // Convert key to google coordinate
        $nameIndex = 'C' . ($emailKey+2);
        $upiIndex = 'H' . ($emailKey+2);
        $uidIndex = 'G' . ($emailKey+2);


        // Get user's full name
        $userFullName = $this->GoogleSheets_Model->getCellContents($nameIndex, $nameIndex)[0][0];

        // Get user's UPI
        $userUpi = $this->GoogleSheets_Model->getCellContents($upiIndex, $upiIndex)[0][0] == null ? '' : $this->GoogleSheets_Model->getCellContents($upiIndex, $upiIndex)[0][0];

        return [$userFullName, $userUpi];
    }

    /*
     * Private functions
     */

    /**
     * Checks if a string is an email (by its format).
     *
     * @param $emailAddress
     *
     * @return bool `true` if string is an email format.
     */
    public static function isValidEmail($emailAddress)
    {
        // Removes all illegal characters from email
        $emailAddress = filter_var($emailAddress, FILTER_SANITIZE_EMAIL);

        // Returns bool variable for whether the sanitised email is valid
        return (bool) filter_var($emailAddress, FILTER_VALIDATE_EMAIL);
    }

}
