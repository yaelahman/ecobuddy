<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Index - iLanding Bootstrap Template</title>
    <meta name="description" content="">
    <meta name="keywords" content="">

    <!-- Favicons -->
    <link href="<?= BASE_URL ?>/assets/img/favicon.png" rel="icon">
    <link href="<?= BASE_URL ?>/assets/img/apple-touch-icon.png" rel="apple-touch-icon">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Inter:wght@100;200;300;400;500;600;700;800;900&family=Nunito:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="<?= BASE_URL ?>/assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= BASE_URL ?>/assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="<?= BASE_URL ?>/assets/vendor/aos/aos.css" rel="stylesheet">
    <link href="<?= BASE_URL ?>/assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
    <link href="<?= BASE_URL ?>/assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

    <!-- Main CSS File -->
    <link href="<?= BASE_URL ?>/assets/style/datatables.css" rel="stylesheet">
    <link href="<?= BASE_URL ?>/assets/css/main.css" rel="stylesheet">
    <?php

    if (isset($style)) {
        include_once $style;
    }

    ?>

</head>

<body class="index-page">

    <header id="header" class="header d-flex align-items-center fixed-top">
        <div class="header-container container-fluid container-xl position-relative d-flex align-items-center justify-content-between">

            <a href="<?= BASE_URL ?>" class="logo d-flex align-items-center me-auto me-xl-0">
                <h1 class="sitename">EcoBuddy</h1>
            </a>

            <nav id="navmenu" class="navmenu">
                <ul>
                    <li><a href="<?= BASE_URL ?>" class="<?= $_SERVER['REQUEST_URI'] == BASE_URL ? 'active' : '' ?>">Home</a></li>
                    <li><a href="<?= BASE_URL ?>/eco-facility" class="<?= $_SERVER['REQUEST_URI'] == BASE_URL . '/eco-facility' ? 'active' : '' ?>">Eco Facility</a></li>
                </ul>
                <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
            </nav>

            <?php if (!isset($_SESSION['user_id'])): ?>
                <a class="btn-getstarted" href="<?= BASE_URL ?>/login">Login</a>
            <?php else: ?>
                <div class="dropdown">
                    <button class="btn-getstarted dropdown-toggle border-0" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                        <?= $_SESSION['user_name'] ?> (<?= $_SESSION['user_role'] ?>)
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                        <li><a class="dropdown-item" href="<?= BASE_URL ?>/logout">Logout</a></li>
                    </ul>
                </div>
            <?php endif; ?>

        </div>
    </header>