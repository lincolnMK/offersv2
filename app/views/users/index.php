<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Users</h2>
        <?php if (can('users','add')): ?>
            <a href="<?= url('users/create') ?>" class="btn btn-primary">+ New User</a>
        <?php endif; ?>
    </div>

    <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $u): ?>
                    <tr>
                        <td><?= $u['id'] ?></td>
                        <td>
                            <a href="<?= url('users/details/'.$u['id']) ?>">
                                <?= htmlspecialchars($u['name']) ?>
                            </a>
                        </td>
                        <td><?= htmlspecialchars($u['email']) ?></td>
                        <td><?= ucfirst($u['status']) ?></td>
                        <td>
                            <?php if (can('users','edit')): ?>
                                <a href="<?= url('users/edit/'.$u['id']) ?>" class="btn btn-sm btn-warning">Edit</a>
                            <?php endif; ?>

                            <?php if (can('users','delete')): ?>
                                <a href="<?= url('users/delete/'.$u['id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this user?')">Delete</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
