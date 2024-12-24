<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ecobuddy</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/style/bootstrap.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/style/datatables.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/style/style.css">

    <?php

    if (isset($style)) {
        include_once $style;
    }

    ?>
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
                    <!-- <li class="nav-item">
                        <a class="nav-link" href="<?= BASE_URL ?>">Home</a>
                    </li> -->
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <!-- Menu items when logged in -->
                        <li class="nav-item">
                            <a class="nav-link" href="<?= BASE_URL ?>"><?= $_SESSION['user_name'] ?> - <?= $_SESSION['user_role'] ?></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link btn btn-danger btn-sm ms-2" href="<?= BASE_URL ?>/logout">Logout</a>
                        </li>
                    <?php else: ?>
                        <!-- Menu items when not logged in -->
                        <li class="nav-item">
                            <a class="nav-link" href="<?= BASE_URL ?>/login">Login</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>

        </div>
    </nav>