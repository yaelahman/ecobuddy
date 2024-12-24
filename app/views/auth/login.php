<div class="container py-4">
    <div class="card login-card">
      <div class="card-body">
        <h2 class="login-header text-center text-primary mb-4">Welcome Back!</h2>
        <?php if (!empty($error)): ?>
          <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <form method="POST" action="<?= BASE_URL ?>/login">
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
          
          <!-- Submit Button -->
          <button type="submit" class="btn btn-primary btn-login w-100">Login</button>
        </form>
      </div>
      <div class="text-center py-3">
        <span>Don't have an account?</span> <a href="#" class="text-primary">Sign up</a>
      </div>
    </div>
</div>