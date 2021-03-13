<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once 'vendor/autoload.php';

class LogViewer extends CI_Controller
{
    private $logViewer;

    public function __construct() {
        parent::__construct(); 
        $this->logViewer = new \CILogViewer\CILogViewer();
        log_message('debug', "=====New Log Controller Initialized====");
    }

    public function index() {
        $whitelist = array('127.0.0.1', '::1');
        if (!in_array($_SERVER['REMOTE_ADDR'], $whitelist)) {
            show_404('Log page with IP:'.$_SERVER['REMOTE_ADDR'],TRUE);
        } 
        echo $this->logViewer->showLogs();
        return;
    }
}