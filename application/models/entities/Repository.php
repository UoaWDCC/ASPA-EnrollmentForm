<?php


class Event
{
    //Properties
    public String $sheets_model;
    /**
     * Repository constructor.
     * @param GoogleSheets_Model $model
     */
    public function __construct(GoogleSheets_Model $model)
    {
        $this->sheets_model = $model;
    }

    public function getMemberByEmail(string $memberEmail) {
      //  This is already a function in verification_model

      $this->load->model("GoogleSheets_Model");

      if (!$this->isEmailOnSheet($emailAddress, MEMBERSHIP_SPREADSHEET_ID, MEMBERSHIP_SHEET_NAME)) {
          log_message("error", "The member is was not found on the sheet when recording to sheet");
      }

       // Given that the email exists in the sheet, find its index
      $emailKey = array_search($emailAddress, $this->addresses);

      // Convert key to google coordinate
      $nameIndex = 'C' . ($emailKey+2);
      $upiIndex = 'H' . ($emailKey+2);

      // Get member's full name and UPI – these are by default blank string ('') if they do not exist
      $memberFullName = $this->GoogleSheets_Model->getCellContents($nameIndex, $nameIndex)[0][0] ?? '';
      $memberUpi = $this->GoogleSheets_Model->getCellContents($upiIndex, $upiIndex)[0][0] ?? '';

      return [$memberFullName, $memberUpi];
    }

    private function getMembers() {}

    public function getEventById(string $eventId) {
      
    }

    public function getOrganisation(string $orgId) {}

    public function getRecord(string $memberEmail, string $eventId) {}

    public function getRecordsByEvent(string $eventId) {

    }

    public function saveEvent(Event $event) {}

    public function saveRecord(Record $record) {}
  }
