<?php


class Member
{
    //Properties
    public String $email;
    public String$full_name;
    public String$upi;
    public int $signup_date; //PHP int holds 32 bits. Current epoch time is just under 32 bits I beleive
    public Boolean $fee_paid;

    /**
     * Member constructor.
     * @param String $email
     * @param String $full_name
     * @param String $upi
     * @param int $signup_date
     * @param bool $fee_paid
     */
    public function __construct(string $email, string $full_name, string $upi, int $signup_date, bool $fee_paid)
    {
        $this->email = $email;
        $this->full_name = $full_name;
        $this->upi = $upi;
        $this->signup_date = $signup_date;
        $this->fee_paid = $fee_paid;
    }

}
