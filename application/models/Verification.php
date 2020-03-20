<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include 'Gsheet_Interface_model.php';

class Verification extends CI_Model {
    // include_once 'class.verifyEmail.php';

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

        // echo $sheetSize . "<br>";

        //get an array of array of column with all existing emails 
        $addresses = $this->Gsheet_Interface_Model->get_from_sheet('B2', 'B' . ($sheetSize+1));

        // echo gettype($addresses);

        // echo '<pre>';
        // print_r($addresses);
        // echo '</pre>';

        // echo gettype($addresses[1][0]);

        //collapse down to simple array 
        $addresses = array_column($addresses, 0);

        // echo PHP_VERSION; 

        // echo '<pre>';
        // print_r($addresses);
        // echo '</pre>';

        //returns false to function if email does not exist in google sheet
        if (!(in_array($emailAddress, $addresses))){
            //echo "email does not exist in sheet <br><br>"
            return false;
        }

        //get index of emailAddress given that it exists 
        $emailKey = array_search($emailAddress, $addresses);

        // echo "emailKey is: " . $emailKey . "<br>";
        // $isTouch = empty($emailKey);
        // echo "ISTOUCH: " . $isTouch . "<br>";
        
        //convert to sheets readable form
        $emailIndex = 'B' . ($emailKey+2);
        // echo "email index is: " . $emailIndex . "<br>";

        //checks if email is in the sheet
        //return false for the function if not
        if (!(in_array($emailAddress, $addresses))){
            // echo "this email does NOT exist in the sheet <br><br>";
            return false;
        } 

        // echo "this email exists in the sheet <br><br>";

        //given that the email exists in the sheet
        //find its index
        $emailKey = array_search($emailAddress, $addresses);
        // echo "emailKey is: " . $emailKey . "<br>";
        
        //turn into sheets readable form 
        $emailIndex = 'B' . ($emailKey+2);
        // echo "emailIndex is: " . $emailIndex . "<br><br>";

        //this takes emailIndex as a parameter
        //gets hex of the colour of the cell containing the email in question
        $colourIs = $this->Gsheet_Interface_Model->get_cell_colour($emailIndex);

        // echo gettype($colourIs) . "<br>";
        // echo "colour is: " . $colourIs . "<br>";

        // if ($colourIs){
        //     echo "colour exists <br>";
        // } else {
        //     echo "colour doesn't exist <br>";
        // }
        
        //uncoloured cells return as 000000 (or sometimes ffffff because google sheets is extra like that)
        if ($colourIs == '000000' || $colourIs == 'ffffff'){
            return false;
        } 

        return true;
    }



}
