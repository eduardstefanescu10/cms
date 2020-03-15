<?php


// Namespaces
use CMS\Http\Request;
use CMS\Http\Response;
use CMS\Routing\Router;


// Get configuration
require '..' . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'config.php';


// Register the autoloader
require VENDOR . 'autoload.php';


// Get helpers
require CMS_SRC .'Helpers' . DS . 'helpers.php';


// Create new Request and Response objects
$request  = new Request();
$response = new Response();


// Set headers
$response->setHeader('Access-Control-Allow-Origin: ' . OPTIONS['URL']);
$response->setHeader('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');


// Create new Router object
$router = new Router($request->getUrl(), $request->getMethod());


// Get routes
require_once '..' . DS . 'routes.php';


// Run application
$router->run();


// Send response
$response->send();


?>