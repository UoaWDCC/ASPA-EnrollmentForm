<?php


class Record
{
    //Properties
    public String $email;
    public String $eventID;
    public String $timestamp;
    public String $fullName;
    public String $upi;
    public String $paymentMethod; //str.enum
    public String $paymentDate; //Date
    public boolean $paymentMade;
    public boolean $attendance;

    /**
 * Record constructor.
 * @param String $email
 * @param String $eventID
 * @param String $timestamp
 * @param String $fullName
 * @param String $upi
 * @param String $paymentMethod
 * @param String $paymentDate
 * @param bool $paymentMade
 * @param bool $attendance
 */public function __construct(string $email, string $eventID, string $timestamp, string $fullName, string $upi, string $paymentMethod, string $paymentDate, bool $paymentMade, bool $attendance)
{
    $this->email = $email;
    $this->eventID = $eventID;
    $this->timestamp = $timestamp;
    $this->fullName = $fullName;
    $this->upi = $upi;
    $this->paymentMethod = $paymentMethod;
    $this->paymentDate = $paymentDate;
    $this->paymentMade = $paymentMade;
    $this->attendance = $attendance;
}



}
