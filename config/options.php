<?php


// Default options
$options = array();
// Basics
$options['SITE_NAME']       = 'CMS';
$options['SITE_SUB_FOLDER'] = '/cms';
$options['SITE_LANG']       = 'en_US';
$options['URL']             = 'http://localhost' . $options['SITE_SUB_FOLDER'];
$options['VERSION']         = '1.0.0';


// Define OPTIONS array as global
define('OPTIONS', $options)


?>