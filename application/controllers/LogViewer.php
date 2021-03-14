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
        $whitelist = array('127.0.0.1', '::1', '118.92.25.130');

        // Determine my IP Address
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }

        if (!in_array($ip, $whitelist)) {
            show_404('Log page with IP:'.$ip,TRUE);
        } 
        
        echo $this->logViewer->showLogs();
        return;
    }
}