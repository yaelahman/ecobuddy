<?php

function middleware($name, $callback)
{
    global $middlewares;
    $middlewares[$name] = $callback;
}

// Define middleware
middleware('auth', function () {
    return isset($_SESSION['user_id']); // Check if the user is logged in
});

middleware('manager', function () {
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'Manager'; // Check if the user is a Manager
});

