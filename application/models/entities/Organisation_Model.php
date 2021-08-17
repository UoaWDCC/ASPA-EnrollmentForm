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

    /**
     *
     * Setting organisation model variables to their specific element
     *
     * @param Array $elements
     */
    function array_Parameters($elements)
    {

        $organisation = [];
        $variables = array($this->name, $this->bankAccountNumber, $this->id, $this->bankRefFormat, $this->logoImg, $this->tagline, $this->supportEmail);
        // $elements = ['name', 'bankAccountNumber', 'id', 'bankRefFormat', 'logoImg', 'orgTagline', 'supportEmail'];
        // If the data from spreadsheet contains event details we are looking for, set them.
        for ($i = 0; $i < sizeof($variables); $i++) {
            $organisation[$elements[$i]] = $variables[$i];
            if (in_array($variables[$i], $elements)) {
            }
        }
        return $organisation;
    }
}
