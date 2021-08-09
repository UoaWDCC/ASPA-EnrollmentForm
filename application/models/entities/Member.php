<?php


class Member
{

    // Properties
    public string $email;
    public string $fullName;
    public string $upi;
    public int $signUpDate; //PHP int holds 32 bits. Current epoch time is just under 32 bits I beleive
    public bool $feePain;

    /**
     * Member constructor.
     *
     * @param String $email
     * @param String $fullName
     * @param String $upi
     * @param int $signUpDate
     * @param bool $feePaid
     */
    public function __construct(string $email, string $fullName, string $upi, int $signUpDate, bool $feePaid)
    {
        $this->email = $email;
        $this->fullName = $fullName;
        $this->upi = $upi;
        $this->signUpDate = $signUpDate;
        $this->feePain = $feePaid;
    }

}
