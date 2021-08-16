<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Organisation_Model
{

    public String $name;
    public String $bankAccountNumber;
    public String $id;
    public String $bankRefFormat;
    public String $logoImg;
    public String $tagline;
    public String $supportEmail;

    /**
     * Constructor for organisation model
     *
     * @param String $name
     * @param String $bankAccountNumber
     * @param String $id
     * @param String $bankRefFormat
     * @param String $logoImg
     * @param String $tagline
     * @param String $supportEmail
     */
    function __construct($name, $bankAccountNumber, $id, $bankRefFormat, $logoImg, $tagline, $supportEmail)
    {
        $this->name = $name;
        $this->bankAccountNumber = $bankAccountNumber;
        $this->id = $id;
        $this->bankRefFormat = $bankRefFormat;
        $this->logoImg = $logoImg;
        $this->tagline = $tagline;
        $this->supportEmail = $supportEmail;
    }

    function get_name()
    {
        return $this->name;
    }

    function get_bankAccountNumber()
    {
        return $this->bankAccountNumber;
    }

    function get_id()
    {
        return $this->id;
    }

    function get_bankRefFormat()
    {
        return $this->bankRefFormat;
    }

    function get_logoImg()
    {
        return $this->logoImg;
    }

    function get_tagline()
    {
        return $this->tagline;
    }
}
