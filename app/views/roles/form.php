<h2><?= isset($role)?'Edit Role':'Create Role' ?></h2>
<form method="post">
  <?= csrf_field() ?>
  <label>Name <input type="text" name="name" value="<?= htmlspecialchars($role['name'] ?? '') ?>" required></label>
  <label>Description <input type="text" name="description" value="<?= htmlspecialchars($role['description'] ?? '') ?>"></label>
  <button type="submit">Save</button>
</form>
