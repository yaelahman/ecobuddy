<?php

require __DIR__ . "/../config/base.php";
/**
 * Controller is the base class for all controllers in the application.
 * It provides common functionality for rendering views, handling redirects, and error handling.
 */
class Controller
{
    /**
     * Renders a view file with the given path and data.
     * 
     * This method extracts the provided data as variables and includes the header, view, and footer files.
     * 
     * @param string $viewPath The path to the view file relative to the views directory.
     * @param array $data An array of data to be extracted and made available to the view.
     */
    protected function render($viewPath, $data = [])
    {
        extract($data); // Extract data as variables for the view
        require __DIR__ . "/../views/layouts/header.php";
        require __DIR__ . "/../views/$viewPath.php";
        require __DIR__ . "/../views/layouts/footer.php";
    }

    /**
     * Returns the path to a component file (script or style) if it exists.
     * 
     * This method checks if a component file exists for the given view path and type (script or style).
     * If the file exists, it returns the path to the file; otherwise, it returns an empty string.
     * 
     * @param string $viewPath The path to the view relative to the views directory.
     * @param bool $script Indicates whether to look for a script or style component. Defaults to true for script.
     * @return string The path to the component file or an empty string if it does not exist.
     */
    protected function component($viewPath, $script = true)
    {
        $type = $script ? 'script' : 'style';
        if (file_exists(__DIR__ . "/../views/$viewPath/component/$type.php")) {
            return __DIR__ . "/../views/$viewPath/component/$type.php";
        } else {
            return "";
        }
    }

    /**
     * Redirects to a specific URI.
     * 
     * This method constructs the full URL by appending the given URI to the base URL and sets the Location header.
     * It then exits the script to prevent further execution.
     * 
     * @param string $uri The URI to redirect to.
     */
    protected function redirect($uri)
    {
        $url = BASE_URL . $uri;
        header("Location: $url");
        exit;
    }

    /**
     * Handles 404 errors by setting the HTTP response code and displaying a message.
     * 
     * This method sets the HTTP response code to 404 and outputs a "Page Not Found" message.
     * It then exits the script to prevent further execution.
     */
    protected function error404()
    {
        http_response_code(404);
        echo "Page Not Found";
        exit;
    }
}
