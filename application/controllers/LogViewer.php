<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once 'vendor/autoload.php';

class LogViewer extends CI_Controller
{
    private $logViewer;

    public function __construct() {
        parent::__construct(); 
        $this->logViewer = new \CILogViewer\CILogViewer();
        //...
    }

    public function index() {
        echo $this->logViewer->showLogs();
        return;
    }
}