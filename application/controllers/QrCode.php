<?php
defined('BASEPATH') or exit('No direct script access allowed');
require('vendor/autoload.php');

class QrCode extends ASPA_Controller
{
  public function index()
  {
    $this->load->view('QrCode');
  }
}