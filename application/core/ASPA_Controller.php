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
    protected array $orgData;
    public Repository_Model $Repository_Model;
    function __construct()
    {
        parent::__construct();
        $this->load->model("Repository_Model");
        $this->load->model("GoogleSheets_Model");
        $this->Repository_Model->initClass(MEMBERSHIP_SPREADSHEET_ID, MEMBERSHIP_SHEET_NAME, REGISTRATION_SPREADSHEET_ID);
        $this->allEvents = $this->Repository_Model->getAllEvents();
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

}

