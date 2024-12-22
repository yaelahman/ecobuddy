<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap demo</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/style/bootstrap.min.css">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand" href="<?= BASE_URL ?>">Eco Buddy</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="<?= BASE_URL ?>">Home</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Jumbotron -->
    <div class="jumbotron text-center py-5 bg-primary text-white">
        <h1 class="display-4">Welcome to Eco Buddy!</h1>
        <p class="lead">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Nobis quisquam nostrum explicabo distinctio repudiandae deleniti molestias eligendi tempora possimus ratione!</p>
    </div>

    <!-- Features Section -->
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body text-center">
                        <h5 class="card-title">Simple Routing</h5>
                        <p class="card-text">Use clean and simple routes for easy navigation.</p>
                        <a href="<?= BASE_URL ?>" class="btn btn-primary">Learn More</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body text-center">
                        <h5 class="card-title">SQLite Integration</h5>
                        <p class="card-text">Easily manage tasks with SQLite and PDO.</p>
                        <a href="<?= BASE_URL ?>" class="btn btn-primary">Learn More</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body text-center">
                        <h5 class="card-title">Bootstrap Styling</h5>
                        <p class="card-text">Responsive and modern UI built with Bootstrap.</p>
                        <a href="<?= BASE_URL ?>" class="btn btn-primary">Learn More</a>
                    </div>
                </div>
            </div>
        </div>
    </div>