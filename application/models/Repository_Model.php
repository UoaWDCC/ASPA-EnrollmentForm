<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require('vendor/autoload.php');
require('./application/models/entities/Event.php');
require('./application/models/entities/Member.php');
require('./application/models/entities/Record.php');


/**
 * @property GoogleSheets_Model $GoogleSheets_Model
 */
class Repository_Model extends CI_Model
{

    private array $events = [];
    private array $members = [];


    /**
     * Repository constructor.
     * @param GoogleSheets_Model $model
     */
    public function initClass(String $membershipSpreadsheetId, String $membershipSheetName, String $registrationSheetId)
    {
        //  GET ALL EVENTS
        $this->load->model('GoogleSheets_Model');

        $this->GoogleSheets_Model->setCurrentSheetName("Events");

        $records = $this->GoogleSheets_Model->getNumberOfRecords();

        $array2d =  $this->GoogleSheets_Model->getCellContents("A2", "J" . ($records + 2));

        for ($i = 0; $i < $records; $i++) {
            $current = $array2d[$i];
            $id = $current[0];
            $name = $current[1];
            $tagline = $current[2];
            $description = $current[3];
            $datetime = intval($current[4]);
            $durationMins = intval($current[5]);
            $location = $current[6];
            $priceNzd = floatval($current[7]);
            $emailBannerImg = $current[8];
            $signUpsOpen = boolval($current[9]);

            $this->events[$id] = new Event(
                $id,
                $name,
                $tagline,
                $description,
                $location,
                $emailBannerImg,
                $datetime,
                $durationMins,
                $priceNzd,
                $signUpsOpen
            );
        }

        //   GET ALL MEMBERS
        $this->GoogleSheets_Model->setSpreadsheetId($membershipSpreadsheetId);

        $this->GoogleSheets_Model->setCurrentSheetName($membershipSheetName);

        $records = $this->GoogleSheets_Model->getNumberOfRecords();

        $array2d = $this->GoogleSheets_Model->getCellContents("A2", "I" . ($records + 2));

        for ($i = 0; $i < $records; $i++) {
            $current = $array2d[$i];

            $signUpDate = intval($current[0]);
            $email = $current[1];
            $fullName = $current[2];
            $upi = $current[7];
            $hasPaid = boolval($current[8]);

            $this->members[$email] = new Member($email, $fullName, $upi, $signUpDate, $hasPaid);
        }

        $this->GoogleSheets_Model->setSpreadsheetId($registrationSheetId);
    }

    /**
     * Get a member from their email
     * @param string the email of the member
     * @return Member the member object
     */
    public function getMemberByEmail(string $memberEmail) {
        return $this->members[$memberEmail];

    }

    /**
     * @return Member[] a list of all members
     */
    public function getMembers() {
        return $this->members;
    }

    /**
     * @return Event the event object that corrosponds to the event ID
     */
    public function getEventById(string $eventId) {
        return $this->events[$eventId];
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
        $records = $this->getRecordsByEvent($eventId);

        if (array_key_exists($memberEmail, $records)) {
            return $records[$memberEmail];
        }
        else {
            return NULL;
        }
    }

    /**
     * Get all the records for an event
     * @return Record[] a list of all records for an event
     */
    public function getRecordsByEvent(string $eventId) {
      $this->GoogleSheets_Model->setCurrentSheetName($eventId);

      $records = $this->GoogleSheets_Model->getNumberOfRecords();

      $array2d = $this->GoogleSheets_Model->getCellContents("A2","K" . ($records + 2));

      $allRecords = [];

      for ($i = 0; $i < $records; $i++) {
          $allRecords[$array2d[$i][1]] = new Record($array2d[$i][1], $eventId, $array2d[$i][0], $array2d[$i][2], $array2d[$i][4], $array2d[$i][5], $array2d[$i][10], $array2d[$i][9], isset($array2d[$i][6]));
      }

        return $allRecords;
    }

    public function saveEvent(Event $event) {}

    public function saveRecord(Record $record) {}
}
