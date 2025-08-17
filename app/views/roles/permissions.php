<h2>Permissions for Role: <?= htmlspecialchars($role['name']) ?></h2>
<form method="post">
  <?= csrf_field() ?>
  <table>
    <thead><tr><th>Module</th><th>Action</th><th>Allow</th></tr></thead>
    <tbody>
      <?php foreach ($perms as $p): ?>
      <tr>
        <td><?= htmlspecialchars($p['module_name']) ?> (<?= htmlspecialchars($p['module_slug']) ?>)</td>
        <td><?= htmlspecialchars($p['action']) ?></td>
        <td>
          <input type="checkbox" name="perm[]" value="<?= $p['id'] ?>" <?= in_array($p['id'], $current) ? 'checked' : '' ?>>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  <button type="submit">Save Permissions</button>
</form>
<p><a href="/roles/index">&larr; Back to Roles</a></p>
