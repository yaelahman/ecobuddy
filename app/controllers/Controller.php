<?php

require __DIR__ . "/../config/base.php";
class Controller
{
    // Render a view file
    protected function render($viewPath, $data = [])
    {
        extract($data); // Extract data as variables for the view
        require __DIR__ . "/../views/layouts/header.php";
        require __DIR__ . "/../views/$viewPath.php";
        require __DIR__ . "/../views/layouts/footer.php";
    }

    protected function component($viewPath, $script = true)
    {
        $type = $script ? 'script' : 'style';
        return __DIR__ . "/../views/$viewPath/component/$type.php";
    }

    // Redirect to a specific URI
    protected function redirect($uri)
    {
        header("Location: $uri");
        exit;
    }

    // Handle 404 errors
    protected function error404()
    {
        http_response_code(404);
        echo "Page Not Found";
        exit;
    }
}
