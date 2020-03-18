<?php


// Check environment
if ($_SERVER['SERVER_NAME'] == 'localhost') {
    // Display errors
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);

    // Show all errors
    error_reporting(E_ALL);

    // Define DEBUG constant
    define('DEBUG', 1);
    define('CACHE', '?v=' . rand(9999, 999999));
} else {
    // Do not show errors
    ini_set('display_errors', 0);
    ini_set('display_startup_errors', 0);

    define('DEBUG', 0);
    define('CACHE', '');
}


?>