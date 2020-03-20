<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include 'Gsheet_Interface_model.php';

class Verification extends CI_Model {
    // include_once 'class.verifyEmail.php';

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


    function is_email_on_sheet($emailAddress){

        // require_once('Gsheet_Interface_Model.php');
        $this->load->model('Gsheet_Interface_Model');

        $sheetSize = $this->Gsheet_Interface_Model->get_sheet_size();

        echo $sheetSize . "<br>";

        $addresses = $this->Gsheet_Interface_Model->get_from_sheet('B2', 'B' . ($sheetSize+1));

        // echo gettype($addresses);

        // echo '<pre>';
        // print_r($addresses);
        // echo '</pre>';

        // echo gettype($addresses[1][0]);

        $addresses = array_column($addresses, 0);

        echo '<pre>';
        print_r($addresses);
        echo '</pre>';

        $emailKey = array_search($emailAddress, $addresses);
        echo "emailKey is: " . $emailKey . "<br>";

        // if ($emailKey){
        //     "email key exists <br>"
        // } else {
        //     "idk man <br>"
        // }

        $emailIndex = 'B' . ($emailKey+2);
        echo "email index is: " . $emailIndex . "<br>";

        // $testCell = $this->Gsheet_Interface_Model->get_from_sheet($emailIndex, $emailIndex);

        // echo gettype($testCell[0][0]) . "<br>";

            //this takes emailIndex as a parameter
        $colourIs = $this->Gsheet_Interface_Model->get_cell_colour('B3');

        echo gettype($colourIs) . "<br>";
        echo "colour is: " . $colourIs . "<br>";

        if ($colourIs){
            echo "colour exists <br>";
        } else {
            echo "colour doesn't exist <br>";
        }




    }



}
