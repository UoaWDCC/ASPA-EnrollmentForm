<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Gsheet_Interface_Model extends CI_Model {

    function getCurrentWorkingDir() {
        // For Windows 
        $dir = shell_exec('echo %cd%');
        // For MacOS / Linux / CentOS server
        // $dir = shell_exec('pwd');
        $stripped = trim($dir);
        return $stripped;
    }

    function google_test() {
        require $this->getCurrentWorkingDir() . '/vendor/autoload.php';

        $client = new Google_Client();
        $client->setApplicationName('Google Sheets and PHP');
        $client->setScopes([\Google_Service_Sheets::SPREADSHEETS]);
        $client->setAccessType('offline');
        $client->setAuthConfig($this->getCurrentWorkingDir() . '/credentials.json');

        $service = new Google_Service_Sheets($client);
        $spreadsheetId = '1NJ3gFsf1qP_-5NF2XyistqUzkug99S656I30oa8-iLU';

        $range = 'Sheet1!A1:A1';

        $response = $service->spreadsheets_values->get($spreadsheetId, $range);

        $values = $response->getValues();

        foreach ($values as $row) {
            if (empty($row)) {
                echo "No Data";
            } else {
                echo $row[0];
            }
        }
    }
}
