<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Organisation_Model extends CI_Model
{

    public String $bankAccountNumber;
    public String $id;
    public String $bankRefFormat;
    public String $logoImg;
    public String $tagline;
    public String $supportEmail;

    /**
     * Constructor for organisation model
     *
     * @param String $bankAccountNumber
     * @param String $id
     * @param String $bankRefFormat
     * @param String $logoImg
     * @param String $tagline
     * @param String $supportEmail
     */
    function __construct($bankAccountNumber, $id, $bankRefFormat, $logoImg, $tagline, $supportEmail)
    {
        $this->bankAccountNumber = $bankAccountNumber;
        $this->id = $id;
        $this->bankRefFormat = $bankRefFormat;
        $this->logoImg = $logoImg;
        $this->tagline = $tagline;
        $this->supportEmail = $supportEmail;
    }
}
