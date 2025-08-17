<h2>Roles</h2>
<?php if (can('roles','add')): ?><p><a role="button" href="/roles/create">+ New Role</a></p><?php endif; ?>
<table>
  <thead><tr><th>ID</th><th>Name</th><th>Description</th><th>Actions</th></tr></thead>
  <tbody>
    <?php foreach ($roles as $r): ?>
      <tr>
        <td><?= $r['id'] ?></td>
        <td><?= htmlspecialchars($r['name']) ?></td>
        <td><?= htmlspecialchars($r['description'] ?? '') ?></td>
        <td>
          <?php if (can('roles','edit')): ?><a href="/roles/edit/<?= $r['id'] ?>">Edit</a><?php endif; ?>
          <?php if (can('permissions','update')): ?> | <a href="/roles/permissions/<?= $r['id'] ?>">Permissions</a><?php endif; ?>
          <?php if (can('roles','delete')): ?> | <a href="/roles/delete/<?= $r['id'] ?>" onclick="return confirm('Delete role?')">Delete</a><?php endif; ?>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>
