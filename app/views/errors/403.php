<?php
// Set the 403 header
header("HTTP/1.0 403 Access Denied");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 Access Denied</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light text-center d-flex justify-content-center align-items-center vh-100">
    <div>
        <h1 class="display-1 text-danger">403</h1>
        <h2 class="mb-3">Page Access Denied</h2>
        <p class="text-muted mb-4">
            Oops! The page you are looking for doesn't exist. It might have been moved or deleted.
        </p>
        <a href="<?= BASE_URL ?>" class="btn btn-primary">
            <i class="bi bi-house-door-fill"></i> Go Home
        </a>
    </div>
    <!-- Bootstrap JS and Icons -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>