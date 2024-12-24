<?php

// Include necessary files
require_once './app/config/database.php';  // Database connection
require_once './app/routes/middleware.php';  // middleware
require_once './app/routes/web.php';  // Routes file

// Function to define route
function route($method, $uri, $callback, $middleware = null)
{
    global $routes;
    $routes[] = compact('method', 'uri', 'callback', 'middleware');
}

// Function to dispatch requests
// Function to handle requests
function dispatch($routes)
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

        foreach ($routes as $route) {
            if (
                $route['method'] === $requestedMethod &&
                preg_match('#^' . $route['uri'] . '$#', $requestedUri, $matches)
            ) {
                array_shift($matches); // Remove the full match

                // If a middleware is assigned, execute it
                if (isset($route['middleware']) && isset($middlewares[$route['middleware']])) {
                    $middlewareResult = call_user_func($middlewares[$route['middleware']]);
                    if (!$middlewareResult) {
                        // Middleware failed, return or redirect
                        http_response_code(403);
                        require __DIR__ . "/app/views/errors/403.php";
                        return;
                    }
                }

                // Ensure callback is a valid callable
                if (is_callable($route['callback'])) {
                    // Execute the route's callback
                    return call_user_func_array($route['callback'], $matches);
                } else {
                    throw new Exception('Invalid callback for route ' . $route['uri']);
                }
            }
        }

        // Return a 404 response if no route matches
        http_response_code(404);
        require __DIR__ . "/app/views/errors/404.php";
    } catch (\Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}


// Dispatch the current request
dispatch($routes);
