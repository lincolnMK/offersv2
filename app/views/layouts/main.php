<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Lands Treasury Information Management System</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      display: flex;
      min-height: 100vh;
      flex-direction: column;
    }
    .wrapper {
      display: flex;
      flex: 1;
    }
    .sidebar {
      width: 220px;
      background: #f8f9fa;
      padding: 15px;
    }
    .sidebar a {
      display: block;
      padding: 8px 12px;
      color: #333;
      text-decoration: none;
      border-radius: 5px;
    }
    .sidebar a:hover {
      background-color: #e9ecef;
    }
    main.container {
      flex: 1;
      padding: 20px;
    }
  </style>
</head>
<body>

  <!-- Top Navbar -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
      <a class="navbar-brand fw-bold" href="#">Lands Treasury IMS</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" 
              data-bs-target="#navbarNav" aria-controls="navbarNav" 
              aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
        <ul class="navbar-nav">
          <?php if (is_logged_in()): ?>
            <li class="nav-item">
              <span class="navbar-text me-3">
                Logged in as:<br><strong><?= htmlspecialchars(auth_user()['name']) ?></strong>
              </span>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="<?= url('auth/logout') ?>">Logout</a>
            </li>
          <?php else: ?>
            <li class="nav-item">
              <a class="nav-link" href="<?= url('auth/login') ?>">Login</a>
            </li>
          <?php endif; ?>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Wrapper for Sidebar + Main -->
  <div class="wrapper">
    <!-- Sidebar -->
    <div class="sidebar border-end">
      <ul class="list-unstyled">
        <?php if (is_logged_in()): ?>
          <li><a href="<?= url('') ?>">Home</a></li>

          <?php if (can('users','view')): ?>
            <li><a href="<?= url('users/index') ?>">Users</a></li>
          <?php endif; ?>
          <?php if (can('roles','view')): ?>
            <li><a href="<?= url('roles/index') ?>">Roles</a></li>
          <?php endif; ?>
          <?php if (can('permissions','view')): ?>
            <li><a href="<?= url('permissions/index') ?>">Permissions</a></li>
          <?php endif; ?>
          <?php if (can('clients','view')): ?>
            <li><a href="<?= url('clients/index') ?>">Clients Management</a></li>
          <?php endif; ?>
          <?php if (can('offers','view')): ?>
            <li><a href="<?= url('offers/index') ?>">Offers</a></li>
          <?php endif; ?>
          <?php if (can('payments','view')): ?>
            <li><a href="<?= url('payments/index') ?>">Payments</a></li>
          <?php endif; ?>
          <?php if (can('reports','view')): ?>
            <li><a href="<?= url('reports/index') ?>">Reports</a></li>
          <?php endif; ?>
          <?php if (can('analysis','view')): ?>
            <li><a href="<?= url('analysis/index') ?>">Analysis</a></li>
          <?php endif; ?>

        <?php else: ?>
          <li><a href="<?= url('auth/login') ?>">Login</a></li>
        <?php endif; ?>
      </ul>
    </div>

    <!-- Main Content -->
    <main class="container">
      <?= $content ?? '' ?>
    </main>

<!-- Footer -->



  </div>

<!-- Footer -->
<footer class="bg-light text-dark py-3 mt-auto">
    <div class="container text-center">
        <small>&copy; <?= date("Y") ?> Treasury @ Ministry Of Lands. All rights reserved.</small>
    </div>
</footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
