<?php
defined('BASEPATH') or exit('No direct script access allowed');
require('vendor/autoload.php');

class Home extends ASPA_Controller
{
    public function index()
    {
        // print_r($this->allEvents);
        // print_r($this->eventData);

        $this->load->view('Home', $this);
    }
}
