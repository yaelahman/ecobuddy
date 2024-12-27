<main class="main">

    <!-- Hero Section -->
    <section id="hero" class="py-5">

        <div class="container py-5" data-aos="fade-up" data-aos-delay="100">
            <div class="py-3">
                <h2>Create New Eco Facility</h2>
                <div class="mb-3">
                    <a href="<?= BASE_URL ?>/eco-facility" class="btn btn-secondary">Back to Eco Facilities List</a>
                </div>
                <form action="<?= BASE_URL ?>/eco-facility/create" method="POST">
                    <div class="mb-3">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" class="form-control" id="title" name="title" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="category" class="form-label">Category</label>
                        <select class="form-control" id="category" name="category" required>
                            <option value="" selected disabled>Select Category</option>
                            <?php foreach ($category as $cat): ?>
                                <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="town" class="form-label">Town</label>
                        <input type="text" class="form-control" id="town" name="town" required>
                    </div>
                    <div class="mb-3">
                        <label for="county" class="form-label">County</label>
                        <input type="text" class="form-control" id="county" name="county" required>
                    </div>
                    <div class="mb-3">
                        <label for="postcode" class="form-label">Postcode</label>
                        <input type="text" class="form-control" id="postcode" name="postcode" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Create Eco Facility</button>
                </form>
            </div>

        </div>

    </section>

</main>