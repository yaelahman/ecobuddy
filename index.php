<?php

require_once './app/config/database.php';
require_once './app/controllers/HomeController.php';
require_once './app/controllers/EcoFacilityController.php';

// Simple router
$routes = [];

// Function to define routes
function route($method, $uri, $callback)
{
    global $routes;
    $routes[] = compact('method', 'uri', 'callback');
}

// Function to handle requests
function dispatch($routes)
{
    $baseDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
    $requestedUri = strtok($_SERVER['REQUEST_URI'], '?');
    $requestedMethod = $_SERVER['REQUEST_METHOD'];

    $requestedUri = $requestedUri ? $requestedUri : '/';
    // Debugging

    // echo "Base Dir: $baseDir<br>";
    // echo "Requested URI: $requestedUri<br>";
    // echo "Requested Method: $requestedMethod<br>";

    foreach ($routes as $route) {
        // Debugging
        // echo "Checking Route: {$route['uri']}<br>";

        if ($route['method'] === $requestedMethod && preg_match('#^' . $route['uri'] . '$#', $requestedUri, $matches)) {
            // Debugging
            // echo "Matched Route: {$route['uri']}<br>";

            array_shift($matches); // Remove the full match
            return call_user_func_array($route['callback'], $matches);
        }
    }

    // If no route matches, return 404
    http_response_code(404);
    require __DIR__ . "/app/views/errors/404.php";
}

// Define routes
$HomeController = new HomeController();

// return $HomeController->index();

route('GET', '/', [$HomeController, 'index']);
route('GET', '/create', [$HomeController, 'create']);
route('POST', '/create', [$HomeController, 'create']);
route('GET', '/edit/(\d+)', [$HomeController, 'edit']);
route('POST', '/edit/(\d+)', [$HomeController, 'edit']);
route('GET', '/delete/(\d+)', [$HomeController, 'delete']);


$EcoFacilityController = new EcoFacilityController();
route('GET', '/eco-facility', [$EcoFacilityController, 'index']);
route('GET', '/eco-facility/datatable', [$EcoFacilityController, 'datatable']);

// Dispatch the current request
dispatch($routes);
