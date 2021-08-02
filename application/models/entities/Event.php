<?php


class Event
{
    //Properties
    public String $id;
    public String $name;
    public String $tagline;
    public String $description;
    public String $location;
    public String $email_banner_img;
    public int $datetime;
    public int $duration_mins;
    public float $price_nzd;
    public boolean $sign_ups_open;

    /**
     * Event constructor.
     * @param String $id
     * @param String $name
     * @param String $tagline
     * @param String $description
     * @param String $location
     * @param String $email_banner_img
     * @param int $datetime
     * @param int $duration_mins
     * @param float $price_nzd
     * @param bool $sign_ups_open
     */
    public function __construct(string $id, string $name, string $tagline, string $description, string $location, string $email_banner_img, int $datetime, int $duration_mins, float $price_nzd, bool $sign_ups_open)
    {
        $this->id = $id;
        $this->name = $name;
        $this->tagline = $tagline;
        $this->description = $description;
        $this->location = $location;
        $this->email_banner_img = $email_banner_img;
        $this->datetime = $datetime;
        $this->duration_mins = $duration_mins;
        $this->price_nzd = $price_nzd;
        $this->sign_ups_open = $sign_ups_open;
    }


}
