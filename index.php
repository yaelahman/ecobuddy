<?php
require_once './app/config/database.php';
require_once './app/controllers/HomeController.php';

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
    // Debug requested URI and method
    $baseDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
    $requestedUri = strtok($_SERVER['REQUEST_URI'], '?');
    $requestedUri = str_replace($baseDir, '', $requestedUri); // Normalize URI
    $requestedMethod = $_SERVER['REQUEST_METHOD'];

    echo "Requested URI: $requestedUri<br>";
    echo "Requested Method: $requestedMethod<br>";

    foreach ($routes as $route) {
        if ($route['method'] === $requestedMethod && preg_match('#^' . $route['uri'] . '$#', $requestedUri, $matches)) {
            echo "Matched Route: " . $route['uri'] . "<br>";
            array_shift($matches); // Remove the full match
            return call_user_func_array($route['callback'], $matches);
        }
    }

    // If no route matches, return 404
    http_response_code(404);
    echo "Page Not Found";
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

// Dispatch the current request
dispatch($routes);
