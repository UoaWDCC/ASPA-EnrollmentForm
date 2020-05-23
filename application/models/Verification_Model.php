<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Verification_Model extends CI_Model {
    
    private $addresses = array();
    
    //pass in emailAddress as a string
    //returns a boolean value for if email is in correct format
    function correct_email_format($emailAddress){
        
     
        // simple message to console
        //removes all illegal characters from email
        $emailAddress = filter_var($emailAddress, FILTER_SANITIZE_EMAIL);
        //returns bool variable for whether the sanitised email is valid
        if (filter_var($emailAddress, FILTER_VALIDATE_EMAIL)){
            return true;
        } else {
            return false;
        }

    }

    function is_email_on_sheet($emailAddress, $sheetId, $sheetName){
        if (!($this->correct_email_format($emailAddress))){
            return false;
        }
        $this->load->model('Gsheet_Interface_Model');
        $this->Gsheet_Interface_Model->set_spreadsheetId($sheetId, $sheetName);
        //this gets the sheet size
        $sheetSize = $this->Gsheet_Interface_Model->get_sheet_size();
        //this is an array of array of all existing emails, i.e. [[email1], [email2], [email3]]
        $this->addresses = $this->Gsheet_Interface_Model->get_from_sheet('B2', 'B' . ($sheetSize+1));
        //collapse down to a simple array
        $this->addresses = array_column($this->addresses, 0);
        //returns false to function if email does not exist in google sheet
        if (!(in_array($emailAddress, $this->addresses))){
            //echo "email does NOT exist in sheet <br><br>"
            return false;
        }

        return true;
    }

    function has_user_paid_membership($emailAddress){

        if (!($this->is_email_on_sheet($emailAddress, MEMBERSHIP_SPREADSHEETID, MEMBERSHIP_SHEETNAME))){
            return false;
        }

        //given that the email exists in the sheet
        //find its index
        $emailKey = array_search($emailAddress, $this->addresses);
        // echo "emailKey is: " . $emailKey . "<br>";
        
        //turn into sheets readable form 
        $emailIndex = 'B' . ($emailKey+2);
        // echo "emailIndex is: " . $emailIndex . "<br><br>";

        //this takes emailIndex as a parameter
        //gets hex of the colour of the cell containing the email in question
        $colourIs = $this->Gsheet_Interface_Model->get_cell_colour($emailIndex);
        
        //uncoloured cells return as 000000 (or sometimes ffffff because google sheets is extra like that)
        if ($colourIs == '000000' || $colourIs == 'ffffff'){
            return false;
        } 

        return true;
        
    }

    // Checks if the user has paid for the event in the event registration google sheets.
    // If the row is highlighted green, they have paid.
    function has_user_paid_event($emailAddress, $sheetName) {
        if (!($this->is_email_on_sheet($emailAddress, SPREADSHEETID, $sheetName))){
            return false;
        }

        //given that the email exists in the sheet
        //find its index
        $emailKey = array_search($emailAddress, $this->addresses);
        // echo "emailKey is: " . $emailKey . "<br>";
        
        //turn into sheets readable form 
        $emailIndex = 'B' . ($emailKey+2);
        // echo "emailIndex is: " . $emailIndex . "<br><br>";

        //this takes emailIndex as a parameter
        //gets hex of the colour of the cell containing the email in question
        $colourIs = $this->Gsheet_Interface_Model->get_cell_colour($emailIndex);
        
        //uncoloured cells return as 000000 (or sometimes ffffff because google sheets is extra like that)
        if ($colourIs == '000000' || $colourIs == 'ffffff'){
            return false;
        } 

        return true;
    }
}
