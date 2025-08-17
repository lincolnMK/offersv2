<h2><?= isset($user)?'Edit User':'Create User' ?></h2>
<form method="post">
  <?= csrf_field() ?>
  <label>Name <input type="text" name="name" value="<?= htmlspecialchars($user['name'] ?? '') ?>" required></label>
  <label>Email <input type="email" name="email" value="<?= htmlspecialchars($user['email'] ?? '') ?>" required></label>
  <label>Password <input type="password" name="password" placeholder="<?= isset($user)?'Leave blank to keep current':'' ?>"></label>
  <label>Status
    <select name="status">
      <option value="active" <?= (isset($user) && $user['status']==='active')?'selected':'' ?>>Active</option>
      <option value="disabled" <?= (isset($user) && $user['status']==='disabled')?'selected':'' ?>>Disabled</option>
    </select>
  </label>
  <button type="submit">Save</button>
</form>
