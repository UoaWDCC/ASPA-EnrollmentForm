<?php


class Event
{
    //Properties
    private String $sheets_model;
    private array $events;
    private array $members;
    private array $records;


    /**
     * Repository constructor.
     * @param GoogleSheets_Model $model
     */
    public function __construct(GoogleSheets_Model $model)
    {
        $this->sheets_model = $model;

        $this->setCurrentSheetName("Events");

        $records = $model->getNumberOfRecords();

        for ($i = 2; $i < $records + 2; $i++) {
          $id = $model->getCellContents($this->sheetName . "!B" . $i);
          $name = $model->getCellContents($this->sheetName . "!C" . $i);
          $tagline = $model->getCellContents($this->sheetName . "!D" . $i);
          $description = $model->getCellContents($this->sheetName . "!E" . $i);
          $location = $model->getCellContents($this->sheetName . "!F" . $i);
          $emailBannerImg = $model->getCellContents($this->sheetName . "!G" . $i);
          $datetime = $model->getCellContents($this->sheetName . "!H" . $i);
          $durationMins = $model->getCellContents($this->sheetName . "!I" . $i);
          $priceNZD = $model->getCellContents($this->sheetName . "!J" . $i);
          $signUpsOpen =  $model->getCellContents($this->sheetName . "!K" . $i);

          $events[$id] = Event($id, $name, $tagline, $description, $location, $emailBannerIng, $datetime, $durationMins, $priceNZD, $signUpsOpen);
        }

        //   THIS IS NOT GOING TO WORK
        $this->setCurrentSheetName("Sheet1");

        $records = $model->getNumberOfRecords();

        for ($i = 2; $i < $records + 2; $i++) {
          $email = $model->getCellContents($this->sheetName . "!B" . $i);
          $fullName = $model->getCellContents($this->sheetName . "!C" . $i);
          $upi = $model->getCellContents($this->sheetName . "!H" . $i);
          $signUpDate = $model->getCellContents($this->sheetName . "!I" . $i);
          $feePaid = $model->getCellContents($this->sheetName . "!J" . $i);

          $members[$email] = Member($email, $fullName, $upi, $signUpDate, $feePaid);
        }


    }

    /**
     * Get a member from their email
     * @param string the email of the member
     * @return Member the member object 
     */
    public function getMemberByEmail(string $memberEmail) {
      return $members[$memberEmail];

    }

    /**
     * @return Member[] a list of all members
     */
    private function getMembers() {
      return $members;
    }

    /**
     * @return Event the event object that corrosponds to the event ID
     */
    public function getEventById(string $eventId) {
      return $events[$eventId];
    }

    /**
     * Gets an organization
     */
    public function getOrganisation(string $orgId) {
      return NULL;
    }

    /**
     * Get a member's record from an event using their email and the event id
     */
    public function getRecord(string $memberEmail, string $eventId) {}

    /**
     * Get all the records for an event
     */
    public function getRecordsByEvent(string $eventId) {

    }

    public function saveEvent(Event $event) {}

    public function saveRecord(Record $record) {}
  }
