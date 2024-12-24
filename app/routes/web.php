<?php
// Include controllers
require_once './app/controllers/HomeController.php';
require_once './app/controllers/AuthController.php';
require_once './app/controllers/EcoFacilityController.php';
require_once 'middleware.php';

// Define routes
$routes = [];

$HomeController = new HomeController();
$AuthController = new AuthController();
$EcoFacilityController = new EcoFacilityController();

route('GET', '/', [$HomeController, 'index']);

route('GET', '/login', [$AuthController, 'login']);
route('POST', '/login', [$AuthController, 'loginAction']);
route('GET', '/logout', [$AuthController, 'logout']);

route('GET', '/eco-facility', [$EcoFacilityController, 'index']);
route('GET', '/eco-facility/datatable', [$EcoFacilityController, 'datatable']);
route('GET', '/eco-facility/create', [$EcoFacilityController, 'create'], 'manager');
route('POST', '/eco-facility/create', [$EcoFacilityController, 'create'], 'manager');
route('GET', '/eco-facility/edit/(\d+)', [$EcoFacilityController, 'edit'], 'manager');
route('DELETE', '/eco-facility/delete/(\d+)', [$EcoFacilityController, 'delete'], 'manager');

// Return the routes array for dispatch
return $routes;
