<?php

define('STORE_ID', 'aamarpaytest');
define('SIGNATURE_KEY', 'dbb74894e82415a2f7ff0ec3a97e4183');

define('API_DOMAIN', 'https://sandbox.aamarpay.com');
define('API_URL', API_DOMAIN . '/jsonpost.php');
define('VERIFY_URL', API_DOMAIN . '/api/v1/trxcheck/request.php'); 

define('BASE_URL', 'http://localhost/Projects/pharmacy-managment-system/'); 

define('SUCCESS_URL', BASE_URL . '/success.php');
define('FAIL_URL', BASE_URL . '/fail.php');
define('CANCEL_URL', BASE_URL . '/checkout.php');
?>