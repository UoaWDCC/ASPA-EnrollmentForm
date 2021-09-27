<?php
defined('BASEPATH') or exit('No direct script access allowed');
require('./application/models/entities/Organisation.php');


/**
 * BaseController so that all controllers inherit some basic information that's needed across the application.
 *
 * @property GoogleSheets_Model $GoogleSheets_Model
 * @property Repository_Model $Repository_Model
 */
class ASPA_Controller extends CI_Controller
{

    /**
     * @var array All the information for this event (retrieved from google sheet).
     */
    protected array $eventData;
    protected array $orgData;

    function __construct()
    {
        parent::__construct();
        $this->load->model("Repository_Model");
        $this->load->model("GoogleSheets_Model");
        $this->Repository_Model->initClass(MEMBERSHIP_SPREADSHEET_ID, MEMBERSHIP_SHEET_NAME, REGISTRATION_SPREADSHEET_ID);
        $this->allEvents = $this->Repository_Model->getAllEvents();
        $this->eventData = $this->loadEventData("id2");
        $this->orgData = $this->Repository_Model->getOrganisation("")->toArray();
    }

    /**
     * This function constructs the json output.
     *
     * @param integer $statusCode The HTTP status code
     * @param string $message A message to return
     * @param string $payload Any extra data
     */
    public function createResponse(int $statusCode, $message = null, $payload = null)
    {
        $array = [
            'message' => $message ?? "(empty message)",
            'payload' => $payload ?? [],
        ];


        $this->output
            ->set_status_header($statusCode)
            ->set_content_type('application/json')
            ->set_output(json_encode($array, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
            ->_display();
        exit;
    }

    /**
     * This function handles the loading of event data from google sheets.
     * @param string $eventId The input eventId for repository 
     *
     * @return array
     */
    private function loadEventData(string $eventId): array
    {
        // $test = $this->Repository_Model->$events;
        // print_r($test);
        $eventtest = $this->Repository_Model->getEventById($eventId)->toArray();
        
        // print_r($eventtest);
        // echo $eventtest[1];
        // Get event details from spreadsheet from range A2 to size of spreadsheet\
        $this->GoogleSheets_Model->setCurrentSheetName($eventtest[0]);

        // Variables in the event google sheet. Could be automated but for now stay in manuel changes
        // Make sure the $elements order is the same on the google sheet
        $elements = ['id', 'title', 'tagline', 'description', 'time', 'duration_mins', 'location', 'price', 'email_banner_img', 'form_enabled'];
        for ($i = 0; $i < sizeof($eventtest); $i++) {
            $eventtest[$elements[$i]] = $eventtest[$i];
            unset($eventtest[$i]);
        }



        if ($eventtest['id']) {
            $this->GoogleSheets_Model->setCurrentSheetName($eventtest['id']);
        } else {
            // disable form if no event sheet is found.
            $eventtest["sign_ups_open"] = False;
        }
        // print_r($eventtest);
        return $eventtest;



        // // Get event details from spreadsheet from range A2 to size of spreadsheet\
        // $this->GoogleSheets_Model->setCurrentSheetName("CurrentEventDetails");
        // $data = $this->GoogleSheets_Model->getCellContents('A2', 'C' . ($this->GoogleSheets_Model->getNumberOfRecords() + 2));

        // // Important variables we care about
        // $elements = ['time', 'date', 'location', 'title', 'tagline', 'price', 'acc_num', 'desc', 'gsheet_name', 'form_enabled'];

        // // If the data from spreadsheet contains event details we are looking for, set them.
        // for ($i = 0; $i < sizeof($data); $i++) {
        //     if (in_array($data[$i][0], $elements)) {
        //         $eventTemp[$data[$i][0]] = $data[$i][2];
        //     }
        // }

        // if ($eventTemp['gsheet_name']) {
        //     $this->GoogleSheets_Model->setCurrentSheetName($eventTemp['gsheet_name']);
        // } else {
        //     // disable form if no event sheet is found.
        //     $eventTemp["form_enabled"] = False;
        // }
        // return $eventTemp;
    }

}

