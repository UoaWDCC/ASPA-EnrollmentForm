<?php
defined('BASEPATH') or exit('No direct script access allowed');
require('vendor/autoload.php');

class Home extends ASPA_Controller
{
    public function index()
    {
        $this->load->view('Home');
    }
}
