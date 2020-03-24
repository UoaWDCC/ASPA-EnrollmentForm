<?php
defined('BASEPATH') or exit('No direct script access allowed');

// Modified base CI controller 
// So that all controllers can inherit common controller functions
class ASPA_Controller extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        // $this->load->helper();
		// $this->load->model();
    }

	/**
	* This function constructs the json output.
	*
	* @param string    	$flag  		To determine if the request was processed successfully
	* @param string 	$message 	A brief description of the output
	* @param string 	$extra 		Any extra information
	*/
    private function create_json($flag = '', $message = '', $extra = [])
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
}

/* End of file ASPA_Controller.php */
/* Location: ./application/controllers/ASPA_Controller.php */