<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Custom Defined Constants for Deployment Environment
|--------------------------------------------------------------------------
| Note that constants in this file is loaded first, then the constants in the main folder
| https://codeinphp.github.io/post/development-environments-in-codeigniter/
*/
define('SPREADSHEETID','1NJ3gFsf1qP_-5NF2XyistqUzkug99S656I30oa8-iLU');
define("SHEETNAME", "Catch");

// setting the stripe publickey and secretkey
// Get the path of the stripeCredentials.json file
$stripeCredentials = trim(shell_exec('pwd'))."/stripeCredentials.json";
// Only assign to live keys if this file exists. If rolls back to test keys.
if (file_exists($stripeCredentials)) {
	$json = file_get_contents($stripeCredentials);
	// Only assign if the json decoding is carried out correctly
	if ($stripeCredentials = json_decode($json, true)) {
		if (isset($stripeCredentials["public_key"]) && isset($stripeCredentials["secret_key"])){
			define('PUBLICKEY', $stripeCredentials["public_key"]);
			define('SECRETKEY', $stripeCredentials["secret_key"]);
		}
	}
}

define("MEMBERSHIP_SPREADSHEETID", '10mwPhiOR_Vfsfw8WHereu4Y5KOsuSWkJFGhrf6Mfk9I');
define("MEMBERSHIP_SHEETNAME", 'Sheet1');
