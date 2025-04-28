<?php
// Include necessary files
require_once './app/config/database.php'; // Database connection
require_once './app/config/base.php';     // Base configuration
require_once './app/routes/middleware.php'; // middleware

// Function to define route (kept for backward compatibility)
function route($method, $uri, $callback, $middleware = null)
{
    global $routes;
    $routes[] = compact('method', 'uri', 'callback', 'middleware');
}

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
                // Get method metadata using ReflectionMethod
                $reflection = new ReflectionMethod($controller, $methodName);

                // Check for middleware attribute or comment in method doc
                $docComment = $reflection->getDocComment();
                $middlewareName = null;

                if ($docComment) {
                    // Parse @middleware annotation
                    if (preg_match('/@middleware\s+([^\s]+)/', $docComment, $matches)) {
                        $middlewareName = trim($matches[1]);
                    }
                }

                // If middleware is assigned, execute it
                if ($middlewareName && isset($middlewares[$middlewareName])) {
                    $middlewareResult = call_user_func($middlewares[$middlewareName]);
                    if (!$middlewareResult) {
                        // Middleware failed, return or redirect
                        http_response_code(403);
                        require __DIR__ . "/app/views/errors/403.phtml";
                        return;
                    }
                }

                // Call the method with parameters
                return call_user_func_array([$controller, $methodName], $parameters);
            }
        }

        // Handle legacy routes for backward compatibility
        global $routes;
        if (!empty($routes)) {
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
                            require __DIR__ . "/app/views/errors/403.phtml";
                            return;
                        }
                    }

                    // Execute the route's callback with sanitized matches
                    $sanitizedMatches = array_map('htmlspecialchars', $matches);
                    return call_user_func_array($route['callback'], $sanitizedMatches);
                }
            }
        }

        // Return a 404 response if no route matches
        http_response_code(404);
        require __DIR__ . "/app/views/errors/404.phtml";
    } catch (\Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}

// For backward compatibility, include web.php if it exists
if (file_exists('./app/routes/web.php')) {
    $routes = [];
    require_once './app/routes/web.php';
}

// Dispatch the current request
dispatch();
