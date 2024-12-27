<main class="main">

  <!-- Hero Section -->
  <section id="hero" class="hero section">

    <div class="container" data-aos="fade-up" data-aos-delay="100">

      <div class="py-4">
        <div class="card register-card">
          <div class="card-body">
            <h2 class="register-header text-center text-primary mb-4">Create Your Account!</h2>
            <?php if (!empty($error)): ?>
              <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            <form method="POST" action="<?= BASE_URL ?>/register">
              <!-- username Input -->
              <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" name="username" id="username" placeholder="Enter your username" required>
              </div>
              <!-- Password Input -->
              <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" name="password" id="password" placeholder="Enter your password" required>
              </div>
              <!-- Confirm Password Input -->
              <div class="mb-3">
                <label for="confirmPassword" class="form-label">Confirm Password</label>
                <input type="password" class="form-control" name="confirmPassword" id="confirmPassword" placeholder="Confirm your password" required>
              </div>

              <!-- Submit Button -->
              <button type="submit" class="btn btn-primary btn-register w-100">Register</button>
            </form>
          </div>
          <div class="text-center py-3">
            <span>Already have an account?</span> <a href="<?= BASE_URL ?>/login" class="text-primary">Login</a>
          </div>
        </div>
      </div>

    </div>

  </section>

</main>