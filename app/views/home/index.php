<!-- Jumbotron -->
<div class="jumbotron text-center py-5 bg-primary text-white">
    <h1 class="display-4">Welcome to Eco Buddy!</h1>
    <p class="lead">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Nobis quisquam nostrum explicabo distinctio repudiandae deleniti molestias eligendi tempora possimus ratione!</p>
</div>

<!-- Features Section -->
<div class="container mt-5">
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body text-center">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <!-- When Logged In -->
                        <h5 class="card-title">Welcome, <b><?= htmlspecialchars($_SESSION['user_name']) ?></b>!</h5>
                        <p class="card-text">You are logged in as <b><?= htmlspecialchars($_SESSION['user_role'] ?? 'User') ?></b>.</p>
                        <a href="<?= BASE_URL ?>/logout" class="btn btn-danger">Logout</a>
                    <?php else: ?>
                        <!-- When Not Logged In -->
                        <h5 class="card-title">Login</h5>
                        <p class="card-text">Login as Manager/User.</p>
                        <a href="<?= BASE_URL ?>/login" class="btn btn-primary">Learn More</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-body text-center">
                    <h5 class="card-title">Eco Facility</h5>
                    <p class="card-text">Please Check Our Facility Here.</p>
                    <a href="<?= BASE_URL ?>/eco-facility" class="btn btn-primary">Learn More</a>
                </div>
            </div>
        </div>
        <!-- <div class="col-md-4">
                <div class="card">
                    <div class="card-body text-center">
                        <h5 class="card-title">Bootstrap Styling</h5>
                        <p class="card-text">Responsive and modern UI built with Bootstrap.</p>
                        <a href="<?= BASE_URL ?>" class="btn btn-primary">Learn More</a>
                    </div>
                </div>
            </div> -->
    </div>
</div>