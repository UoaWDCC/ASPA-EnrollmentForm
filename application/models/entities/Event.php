<?php


class Event
{
    //Properties
    public String $id;
    public String $name;
    public String $tagline;
    public String $description;
    public String $location;
    public String $emailBannerImg;
    public int $datetime;
    public int $durationMins;
    public float $priceNZD;
    public boolean $signUpsOpen;

    /**
     * Event constructor.
     * @param String $id
     * @param String $name
     * @param String $tagline
     * @param String $description
     * @param String $location
     * @param String $emailBannerImg
     * @param int $datetime
     * @param int $durationMins
     * @param float $priceNZD
     * @param bool $signUpsOpen
     */
    public function __construct(string $id, string $name, string $tagline, string $description, string $location, string $emailBannerImg, int $datetime, int $durationMins, float $priceNZD, bool $signUpsOpen)
    {
        $this->id = $id;
        $this->name = $name;
        $this->tagline = $tagline;
        $this->description = $description;
        $this->location = $location;
        $this->emailBannerImg = $emailBannerImg;
        $this->datetime = $datetime;
        $this->durationMins = $durationMins;
        $this->priceNZD = $priceNZD;
        $this->signUpsOpen = $signUpsOpen;
    }


}
