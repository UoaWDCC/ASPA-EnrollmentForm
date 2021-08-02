<?php
defined('BASEPATH') or exit('No direct script access allowed');

// Modified base CI controller
// So that all controllers can inherit common controller functions

/**
 * Class ASPA_Controller
 *
 * @property GoogleSheets_Model $GoogleSheets_Model
 */
class ASPA_Controller extends CI_Controller
{

    /**
     * @var array All the information for this event (retrieved from google sheet).
     */
    protected $eventData;

    function __construct()
    {
        parent::__construct();
        // $this->load->helper();
		// $this->load->model();
        $this->eventData = $this->loadEventData();
    }

	/**
	* This function constructs the json output.
	*
	* @param string    	$flag  		To determine if the request was processed successfully
	* @param string 	$message 	A brief description of the output
	* @param string 	$extra 		Any extra information
	*/
    protected function create_json($flag = '', $message = '', $extra = [])
    {
        $array = array(
            'is_success' => $flag,
            'message' => $message,
            'extra' => $extra
        );
        $this->output
            ->set_status_header(200)
            ->set_content_type('application/json')
            ->set_output(json_encode($array, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
            ->_display();
        exit;
    }

    /**
     * This function handles the loading of event data from google sheets.
     *
     * @return array
     */
    private function loadEventData() {
        $this->load->model("GoogleSheets_Model");


        $eventTemp = [];

        // Get event details from spreadsheet from range A2 to size of spreadsheet
        $this->GoogleSheets_Model->setCurrentSheetName("CurrentEventDetails");
        $data = $this->GoogleSheets_Model->getCellContents('A2', 'C' . ($this->GoogleSheets_Model->getNumberOfRecords() + 2));

        // Important variables we care about
        $elements = ['time', 'date', 'location', 'title', 'tagline', 'price', 'acc_num', 'desc', 'gsheet_name', 'form_enabled'];

        // If the data from spreadsheet contains event details we are looking for, set them.
        for ($i = 0; $i < sizeof($data); $i++) {
            if (in_array($data[$i][0], $elements)) {
                $eventTemp[$data[$i][0]] = $data[$i][2];
            }
        }

        if ($eventTemp['gsheet_name']) {
            $this->GoogleSheets_Model->setCurrentSheetName($eventTemp['gsheet_name']);
        } else {
            // disable form if no event sheet is found.
            $eventTemp["form_enabled"] = False;
        }
        return $eventTemp;
    }
}

/* End of file ASPA_Controller.php */
/* Location: ./application/controllers/ASPA_Controller.php */
