<?php
require("./application/models/GoogleSheets_Model.php");

class Repository extends CI_Model
{
    //Properties
    private GoogleSheets_Model $sheets_model;
    private array $events;
    private array $members;

    /**
     * Repository constructor.
     * @param GoogleSheets_Model $model
     */
    public function __construct(GoogleSheets_Model $model)
    {
      //  GET ALL EVENTS
      $this->sheets_model = $model;

      $this->setCurrentSheetName("Events");

      $records = $model->getNumberOfRecords();

      $array2d = $model->getCellContents($this->sheetName . "!A2", $this->sheetName . "!K" . ($records + 2));

      for ($i = 0; $i < $records; $i++) {
        $events[$array2d[0][i]] = Event($array2d[0][i], $array2d[1][i], $array2d[2][i], $array2d[3][i], $array2d[4][i], $array2d[5][i], $aarray2d[6][i], $array2d[7][i], $array2d[8][i], $array2d[9][i]);
      }

      //   GET ALL MEMBERS
      $this->setCurrentSheetName("Sheet1");

      $records = $model->getNumberOfRecords();

      $array2d = $model->getCellContents($this->sheetName . "!B2", $this->sheetName . "!C" . ($records + 2));
      $array2dInfo = $model->getCellContents($this->sheetName . "!H2", $this->sheetName . "!J" . ($records + 2));

      for ($i = 0; $i < $records; $i++) {
        $members[$array2d[0][i]] = Member($array2d[0][i], $array2d[1][i], $array2dInfo[0][i], $array2dInfo[1][i], $array2dInfo[2][i]);
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
     * @return Record the member's record, or NULL
     */
    public function getRecord(string $memberEmail, string $eventId) {
      $this->setCurrentSheetName($eventId);

      $records = $model->getNumberOfRecords();

      $array2d = $model->getCellContents($this->sheetName . "!A2", $this->sheetName . "!K" . ($records + 2));

      $allRecords = [];

      $index = -1;

      for ($i = 0; $i < $records; $i++) {
        if ($array2d[1][i] == $memberEmail) {
          $index = $i;
          break;
        }
      }

      if (index >= 0)
        return Record($array2d[1][index], $eventId, $array2d[0][index], $array2d[2][index], $array2d[4][index], $array2d[5][index], $array2d[10][index], $array2d[9][index], $array2d[6][index] = "P" ? true : false);
      else
        return NULL;
    }

    /**
     * Get all the records for an event
     * @return Record[] a list of all records for an event
     */
    public function getRecordsByEvent(string $eventId) {
      $this->setCurrentSheetName($eventId);

      $records = $model->getNumberOfRecords();

      $array2d = $model->getCellContents($this->sheetName . "!A2", $this->sheetName . "!K" . ($records + 2));

      $allRecords = [];

      for ($i = 0; $i < $records; $i++) {
        $allRecords[$array2d[1][i]] = Record($array2d[1][i], $eventId, $array2d[0][i], $array2d[2][i], $array2d[4][i], $array2d[5][i], $array2d[10][i], $array2d[9][i], $array2d[6][i] = "P" ? true : false);
      }

      return $allRecords;
    }

    public function saveEvent(Event $event) {}

    public function saveRecord(Record $record) {}
  }
