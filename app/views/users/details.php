<?php
// users/details.php



?>

<div class="container mt-4">
    <h2>User Details: <?= htmlspecialchars($user['name']) ?></h2>
    <p>Email: <strong><?= htmlspecialchars($user['email']) ?></strong></p>
    <p>Status: <strong><?= ucfirst($user['status']) ?></strong></p>
    <p>Created At: <?= $user['created_at'] ?></p>
    <p>Updated At: <?= $user['updated_at'] ?></p>

    <?php if (can('users','edit')): ?>
        <a href="<?= url('users/edit/'.$user['id']) ?>" class="btn btn-primary mb-3">Edit User</a>
    <?php endif; ?>
<hr>

<!-- Explicit User Permissions (Flat List) -->
<h4>User Permissions (Flat List)</h4>
<?php if (!empty($userPerms)): ?>
    <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>Module</th>
                    <th>Action</th>
                    <th>Allowed</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($userPerms as $perm): ?>
                    <tr>
                        <td><?= htmlspecialchars($perm['module']) ?></td>
                        <td><?= htmlspecialchars($perm['action']) ?></td>
                        <td class="text-center"><?= $perm['allow'] ? 'Yes' : 'No' ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php else: ?>
    <p>No explicit user permissions assigned.</p>
<?php endif; ?>

<!-- Always Show Matrix -->
<h4>User Permissions Matrix</h4>
<div class="table-responsive">
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>Module</th>
                <?php foreach ($allActions as $action): ?>
                    <th class="text-center"><?= ucfirst($action) ?></th>
                <?php endforeach; ?>
            </tr>
        </thead>
        <tbody>
            <?php
            // Build matrix from flat userPerms (if any)
            $userMatrix = [];
            if (!empty($userPerms)) {
                foreach ($userPerms as $perm) {
                    $slug = $perm['module']; // Assuming 'module' is already a slug
                    $action = $perm['action'];
                    $userMatrix[$slug][$action] = $perm['allow'];
                }
            }

            foreach ($allModules as $module):
                $slug = $module['slug'];
            ?>
                <tr>
                    <td><?= htmlspecialchars($module['name']) ?></td>
                    <?php foreach ($allActions as $action): ?>
                        <td class="text-center">
                            <input type="checkbox" disabled
                                <?= isset($userMatrix[$slug][$action]) && $userMatrix[$slug][$action] ? 'checked' : '' ?>>
                            <?php if (!isset($userMatrix[$slug][$action])): ?>
                                <span style="color:gray;">–</span>
                            <?php endif; ?>
                        </td>
                    <?php endforeach; ?>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<hr>

<!-- Permissions Matrix (User + Role) -->
<h4>Effective Permissions (User + Role)</h4>
<?php if (!empty($permissionsMatrix)): ?>
    <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>Module</th>
                    <?php foreach ($allActions as $action): ?>
                        <th class="text-center"><?= ucfirst($action) ?></th>
                    <?php endforeach; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($allModules as $module):
                    $slug = $module['slug'];
                ?>
                    <tr>
                        <td><?= htmlspecialchars($module['name']) ?></td>
                        <?php foreach ($allActions as $action): ?>
                            <td class="text-center">
                                <input type="checkbox" disabled
                                    <?= isset($permissionsMatrix[$slug][$action]) && $permissionsMatrix[$slug][$action] ? 'checked' : '' ?>>
                                <?php if (!isset($permissionsMatrix[$slug][$action])): ?>
                                    <span style="color:red;">Missing</span>
                                <?php endif; ?>
                            </td>
                        <?php endforeach; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php else: ?>
    <p>No permissions available for this user.</p>
<?php endif; ?>

<hr>

    <!-- Audit Logs -->
<h4>Audit Logs</h4>
<?php if (!empty($auditLogs)): ?>
    <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>Date</th>
                    <th>Action</th>
                    <th>Module / Entity</th>
                    <th>Details</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($auditLogs as $log): ?>
                    <tr>
                        <td><?= htmlspecialchars($log['created_at']) ?></td>
                        <td><?= htmlspecialchars($log['action']) ?></td>
                        <td>
                            <?= htmlspecialchars($log['module_slug']) ?>
                            <?php if (!empty($log['entity_id'])): ?>
                                / <?= htmlspecialchars($log['entity_id']) ?>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php 
                                $details = json_decode($log['details'], true);
                                if (is_array($details)) {
                                    echo '<pre>'.htmlspecialchars(json_encode($details, JSON_PRETTY_PRINT)).'</pre>';
                                } else {
                                    echo htmlspecialchars($log['details']);
                                }
                            ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php else: ?>
    <p>No audit logs found for this user.</p>
<?php endif; ?>


    <hr>

    <!-- User Actions -->
    <h4>User Actions</h4>
    <?php if (!empty($actionLogs)): ?>
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>Date</th>
                        <th>Action</th>
                        <th>IP Address</th>
                        <th>Details</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($actionLogs as $action): ?>
                        <tr>
                            <td><?= $action['created_at'] ?></td>
                            <td><?= htmlspecialchars($action['action']) ?></td>
                            <td><?= htmlspecialchars($action['ip_address']) ?></td>
                            <td><?= htmlspecialchars($action['details']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <p>No actions logged for this user.</p>

        <hr>



    <?php endif; 
    
    
    
    
    ?>

    <a href="<?= url('users/index') ?>" class="btn btn-secondary mt-3">Back to Users</a>
</div>
