<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/*
|--------------------------------------------------------------------------
| Custom Defined Constants
|--------------------------------------------------------------------------
|
*/

/**
 * The event registration sheet we are storing our information on (i.e. adhoc database).
 */
const REGISTRATION_SPREADSHEET_ID = '1YvLmK7DbVuf5O3UDKlYaNOH1Zd8ohg_eEibzwfh2IME';

/**
 * Stripe public and private keys for development.
 */
if (!defined('STRIPE_PUBLIC_KEY')) {
    define('STRIPE_PUBLIC_KEY', 'pk_test_A4wjqVPPn530rgAXv6sHKgSl00opCMVX9A');
    define('STRIPE_PRIVATE_KEY', 'sk_test_OMC00A11yJUakUU4kx6KoGTp0028EYnLBa');
}

/**
 * Google sheet ID and sheet name for ASPA's membership spreadsheet.
 */
const MEMBERSHIP_SPREADSHEET_ID = '10mwPhiOR_Vfsfw8WHereu4Y5KOsuSWkJFGhrf6Mfk9I';
const MEMBERSHIP_SHEET_NAME = 'Sheet1';
