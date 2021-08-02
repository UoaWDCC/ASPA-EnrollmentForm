<?php


class Record
{
    //Properties
    public String $email;
    public String $event_id;
    public String $timestamp;
    public String $full_name;
    public String $upi;
    public String $payment_method; //str.enum
    public String $payment_date; //Date
    public boolean $payment_made;
    public boolean $attendance;

    /**
 * Record constructor.
 * @param String $email
 * @param String $event_id
 * @param String $timestamp
 * @param String $full_name
 * @param String $upi
 * @param String $payment_method
 * @param String $payment_date
 * @param bool $payment_made
 * @param bool $attendance
 */public function __construct(string $email, string $event_id, string $timestamp, string $full_name, string $upi, string $payment_method, string $payment_date, bool $payment_made, bool $attendance)
{
    $this->email = $email;
    $this->event_id = $event_id;
    $this->timestamp = $timestamp;
    $this->full_name = $full_name;
    $this->upi = $upi;
    $this->payment_method = $payment_method;
    $this->payment_date = $payment_date;
    $this->payment_made = $payment_made;
    $this->attendance = $attendance;
}



}
