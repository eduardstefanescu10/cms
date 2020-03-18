<?php


// Routes

// 404
$router->get('/404', 'NotFoundController@index');


// Dashboard
$router->get('/', 'DashboardController@index');


// Products CRUD
$router->post('/api/products/product/create', 'ProductsController@create');
$router->get('/api/products/product/read/:ID', 'ProductsController@read');
$router->put('/api/products/product/update', 'ProductsController@update');
$router->delete('/api/products/product/delete', 'ProductsController@delete');




?>