<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Organisation_Model extends CI_Model
{

    public String $bank_account_number;
    public String $id;
    public String $bank_ref_format;
    public String $logo_img;
    public String $tagline;
    public String $support_email;

    /**
     * Constructor for organisation model
     *
     * @param String $bank_account_number
     * @param String $id
     * @param String $bank_ref_format
     * @param String $logo_img
     * @param String $tagline
     * @param String $support_email
     */
    function __construct($bank_account_number, $id, $bank_ref_format, $logo_img, $tagline, $support_email)
    {
        $this->bank_account_number = $bank_account_number;
        $this->id = $id;
        $this->bank_ref_format = $bank_ref_format;
        $this->logo_img = $logo_img;
        $this->tagline = $tagline;
        $this->support_email = $support_email;
    }
}
