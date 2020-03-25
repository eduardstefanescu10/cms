<?php


// Default options
$options = array();
// Basics
$options['SITE_NAME']       = 'CMS';
$options['SITE_SUB_FOLDER'] = '/cms';
$options['SITE_LANG']       = 'en_US';
$options['URL']             = 'http://localhost' . $options['SITE_SUB_FOLDER'];
$options['VERSION']         = '1.0.0';
// Mail
$options['SERVER_MAIL_NAME']       = 'smtp.gmail.com'; // smtp.gmail.com
$options['SERVER_MAIL_ENCRYPTION'] = 'ssl'; // ssl
$options['SERVER_MAIL_PASS']       = ''; // password
$options['SERVER_MAIL_USER']       = ''; // your.email@domain.com
$options['SERVER_MAIL_PORT']       = 465; // 465
$options['SERVER_EMAIL_ADDRESS']   = ''; // your.email@domain.com

// Define OPTIONS array as global
define('OPTIONS', $options)


?>