<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include 'Gsheet_Interface_model.php';

class Verification extends CI_Model {
    // include_once 'class.verifyEmail.php';

    private $addresses = array();

    //pass in emailAddress as a string
    //returns a boolean value for if email is in correct format
    function correct_email_format($emailAddress){

        //removes all illegal characters from email
        $emailAddress = filter_var($emailAddress, FILTER_SANITIZE_EMAIL);

        //returns bool variable for whether the sanitised email is valid
        if (filter_var($emailAddress, FILTER_VALIDATE_EMAIL)){
            return true;
        } else {
            return false;
        }

    }

    //pass in emailAddress as a string
    //returns a boolean value for whether an email address is considered to be on the sheet
    //if email user hasn't paid for memebership they are considered to be not on the sheet
    function is_email_on_sheet($emailAddress){

        if (!($this->correct_email_format($emailAddress))){
            return false;
        }

        // require_once('Gsheet_Interface_Model.php');
        $this->load->model('Gsheet_Interface_Model');

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


    function has_user_paid($emailAddress){

        if (!($this->is_email_on_sheet($emailAddress))){
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
