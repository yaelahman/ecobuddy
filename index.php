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
    // Ensure base directory is correctly detected
    $baseDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
    $requestedUri = strtok($_SERVER['REQUEST_URI'], '?');
    $requestedMethod = $_SERVER['REQUEST_METHOD'];

    // Normalize paths
    $baseDir = rtrim($baseDir, '/');
    $requestedUri = rtrim($requestedUri, '/');

    // Remove base directory from the requested URI if present
    if (!empty($baseDir) && strpos($requestedUri, $baseDir) === 0) {
        $requestedUri = substr($requestedUri, strlen($baseDir));
    }

    // Default to root URI if empty
    $requestedUri = $requestedUri ?: '/';

    // Debugging output
    // echo "Base Dir: $baseDir<br>";
    // echo "Requested URI: $requestedUri<br>";
    // echo "Requested Method: $requestedMethod<br>";

    foreach ($routes as $route) {
        if (
            $route['method'] === $requestedMethod &&
            preg_match('#^' . $route['uri'] . '$#', $requestedUri, $matches)
        ) {
            array_shift($matches); // Remove the full match
            return call_user_func_array($route['callback'], $matches);
        }
    }

    // Return a 404 response if no route matches
    http_response_code(404);
    // echo "404 Not Found";
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
