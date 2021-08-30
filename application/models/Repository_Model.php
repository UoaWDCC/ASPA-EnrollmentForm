<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require('vendor/autoload.php');
require('./application/models/entities/Event.php');
require('./application/models/entities/Member.php');


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
    public function initClass(String $membershipSpreadsheetId, String $membershipSheetName)
    {
        //  GET ALL EVENT
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
    public function getMembers() {
        return $this->members;
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
