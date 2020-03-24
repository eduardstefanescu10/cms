<?php


// Routes


// 404
$router->get('/404', 'NotFoundController@index');


// Log in
$router->get('/login', 'LoginController@index');
$router->post('/api/account/login', 'LoginController@login');


// Log out
$router->get('/logout', 'LogoutController@index');


// Forgot password
$router->get('/forgot', 'ForgotController@index');
$router->post('/api/account/forgot', 'ForgotController@forgot');
$router->get('/password/reset/:ID/:tempPass', 'ForgotController@resetPass');


// Dashboard
$router->get('/', 'DashboardController@index');


// Products CRUD
$router->post('/api/products/product/create', 'ProductsController@create');
$router->get('/api/products/product/read/:ID', 'ProductsController@read');
$router->put('/api/products/product/update', 'ProductsController@update');
$router->delete('/api/products/product/delete', 'ProductsController@delete');




?>