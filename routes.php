<?php


// Routes


// 404
$router->get('/404', 'NotFoundController@index');


// Log in
$router->get('/login', 'LoginController@index');
$router->post('/api/account/login', 'LoginController@login');


// Log out
$router->get('/logout', 'LogoutController@index');


// Account
$router->get('/account', 'AccountController@index');
$router->get('/api/account/details/get', 'AccountController@getDetails');
$router->post('/api/account/details/update', 'AccountController@updateDetails');
$router->post('/api/account/password', 'PasswordController@changePass');


// Forgot password
$router->get('/forgot', 'ForgotController@index');
$router->post('/api/account/forgot', 'ForgotController@forgot');
$router->get('/password/reset/:ID/:tempPass', 'ForgotController@resetPass');


// Dashboard
$router->get('/', 'DashboardController@index');


// Change password
$router->get('/password', 'PasswordController@index');


// Traffic
$router->post('/api/statistics/traffic/views/days', 'TrafficController@getDaysViews');
$router->post('/api/statistics/traffic/views/devices', 'TrafficController@getDevicesViews');


// Categories
$router->get('/categories', 'CategoriesController@index');
$router->get('/categories/new', 'CategoriesController@newCategory');
$router->post('/api/categories/list', 'CategoriesController@list');
$router->post('/api/categories/create', 'CategoriesController@create');

// Orders
$router->post('/api/orders/list', 'OrdersController@list');


// Products CRUD
$router->post('/api/products/product/create', 'ProductsController@create');
$router->get('/api/products/product/read/:ID', 'ProductsController@read');
$router->put('/api/products/product/update', 'ProductsController@update');
$router->delete('/api/products/product/delete', 'ProductsController@delete');




?>