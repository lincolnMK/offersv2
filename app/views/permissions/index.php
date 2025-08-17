<h2>Permissions</h2>
<table>
  <thead><tr><th>ID</th><th>Module</th><th>Action</th></tr></thead>
  <tbody>
    <?php foreach ($perms as $p): ?>
      <tr>
        <td><?= $p['id'] ?></td>
        <td><?= htmlspecialchars($p['module_name']) ?> (<?= htmlspecialchars($p['module_slug']) ?>)</td>
        <td><?= htmlspecialchars($p['action']) ?></td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>
