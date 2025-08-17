<h2 class="text-center mb-3">Ministry of Lands Treasury</h2>
<p class="text-center text-muted mb-4">Use your email and password</p>

<?php if (!empty($error)): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<form method="post">
    <?= csrf_field() ?>
    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" name="email" id="email" class="form-control " required autofocus>
    </div>

    <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input type="password" name="password" id="password" class="form-control " required>
    </div>

    <div class="d-grid">
        <button type="submit" class="btn btn-secondary">Login</button>
    </div>
</form>


<p class="text-center text-muted mt-3">
    <small>&copy; <?= date('Y') ?> Ministry of Lands Treasury. All rights reserved.</small>
</p> 