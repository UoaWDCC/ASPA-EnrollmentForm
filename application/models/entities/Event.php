<?php


class Event
{

    //Properties
    public string $id;
    public string $name;
    public string $tagline;
    public string $description;
    public string $location;
    public string $emailBannerImg;
    public String $datetime;
    public int $durationMins;
    public float $priceNZD;
    public bool $signUpsOpen;


    public function __construct(
      string $id,                          
      string $name,
      string $tagline,
      string $description,
      string $location,
      string $emailBannerImg,
      String $datetime,
      int $durationMins,
      float $priceNZD,
      bool $signUpsOpen
    )
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

    public function toArray() : array {
      return [
        $this->id, 
        $this->name, 
        $this->tagline, 
        $this->description, 
        $this->datetime,
        $this->durationMins,
        $this->location,
        $this->priceNZD,
        $this->emailBannerImg,
        $this->signUpsOpen,
        ];
    }
}
