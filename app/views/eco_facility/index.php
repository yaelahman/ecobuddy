<main class="main">

    <!-- Hero Section -->
    <section id="hero" class="py-5">

        <div class="container py-5" data-aos="fade-up" data-aos-delay="100">

         <div class="card shadow-sm rounded-lg border-0 my-3">
            <div class="card-body">
                <div class="py-3">
                    <div class="d-flex justify-content-end">
                        <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'Manager'): ?>
                            <a href="<?= BASE_URL ?>/eco-facility/create" class="btn btn-primary rounded-circle p-2 d-flex justify-content-center align-items-center">
                                <i class="fas fa-plus"></i>
                            </a>
                        <?php endif; ?>
                    </div>
                    <div class="table-responsive">
                        <table id="example" class="display" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Description</th>
                                    <th>Category</th>
                                    <th>Town</th>
                                    <th>County</th>
                                    <th>Postcode</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
         </div>

        </div>

    </section>

</main>