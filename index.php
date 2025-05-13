<?php
// Include necessary files
require_once './app/config/database.php'; // Database connection


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$HTTPS_ON = false;
define("BASE_URL", rtrim(($HTTPS_ON ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . str_replace(basename($_SERVER['SCRIPT_NAME']), "", $_SERVER['SCRIPT_NAME']), '/'));

session_start();


// Auto-discover controllers in the controllers directory
function discoverControllers()
{
    $controllerFiles = glob('./app/controllers/*.php');
    $controllers = [];

    foreach ($controllerFiles as $file) {
        $className = basename($file, '.php');
        if ($className !== 'Controller') { // Skip base controller
            require_once $file;
            $controllers[strtolower(str_replace('Controller', '', $className))] = $className;
        }
    }

    return $controllers;
}

// Convert camelCase or snake_case to kebab-case for URLs
function toKebabCase($string)
{
    // Replace underscores with hyphens
    $string = str_replace('_', '-', $string);

    // Convert camelCase to kebab-case
    $string = preg_replace('/([a-z])([A-Z])/', '$1-$2', $string);

    // Convert to lowercase
    return strtolower($string);
}

// Function to dispatch requests
function dispatch()
{
    try {
        global $middlewares;

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

        // Get available controllers
        $controllers = discoverControllers();

        // Parse URI segments
        $segments = explode('/', trim($requestedUri, '/'));

        // Handle root URL
        if (empty($segments[0])) {
            // Default to HomeController->index()
            $controllerName = 'HomeController';
            $methodName = 'index';
            $parameters = [];

            if (class_exists($controllerName)) {
                $controller = new $controllerName();
                if (method_exists($controller, $methodName)) {
                    return call_user_func_array([$controller, $methodName], $parameters);
                }
            }
        }

        // Determine controller, method and parameters from URI
        $controllerKey = strtolower(str_replace('-', '', $segments[0]));

        // Check if the first segment matches a controller
        if (isset($controllers[$controllerKey])) {
            $controllerName = $controllers[$controllerKey];
            $controller = new $controllerName();

            // Check for method in second segment or default to index
            $methodName = isset($segments[1]) && !empty($segments[1]) ? $segments[1] : 'index';
            $methodName = str_replace('-', '_', $methodName); // Convert kebab-case to snake_case

            // Parameters are any remaining segments
            $parameters = array_slice($segments, 2);

            // Check for method suffix based on HTTP method
            $methodWithHttpVerb = strtolower($requestedMethod) . '_' . $methodName;

            // Check if method with HTTP verb exists
            if (method_exists($controller, $methodWithHttpVerb)) {
                $methodName = $methodWithHttpVerb;
            }

            // Check if the method exists and is callable
            if (method_exists($controller, $methodName)) {

                // Call the method with parameters
                return call_user_func_array([$controller, $methodName], $parameters);
            }
        }

        // Return a 404 response if no route matches
        http_response_code(404);
        require __DIR__ . "/app/views/errors/404.phtml";
    } catch (\Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}

// Dispatch the current request
dispatch();
