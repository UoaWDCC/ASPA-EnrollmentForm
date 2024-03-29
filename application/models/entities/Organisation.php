<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Organisation
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
     * Setting organisation model variables to their specific element
     *
     * @param Array $elements
     */
    function toArray()
    {
        return [
            'orgName' => $this->name,
            'orgBankAccNumber' => $this->bankAccountNumber,
            'orgId' => $this->id,
            'orgBankRefFormat' => $this->bankRefFormat,
            'orgLogoImg' => $this->logoImg,
            'orgTagLine' => $this->tagline,
            'orgSupportEmail' => $this->supportEmail
        ];
    }
}
