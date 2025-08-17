<?php
class UsersController extends Controller {
  private string $module = 'users';
  public function __construct(){ Auth::ensure(); }
  public function index(){ ACL::ensure($this->module,'view');
    $m = new User(); $users = $m->all();
    $this->view('users/index', compact('users'));
  }
  public function create() {
    ACL::ensure($this->module, 'add');

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        verify_csrf();

        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $status = $_POST['status'] ?? 'active';

        // ✅ Basic validation
        if (empty($name) || empty($email) || empty($password)) {
            flash('error', 'Name, email, and password are required.');
            redirect('users/create');
            return;
        }

        $data = [
            'name' => $name,
            'email' => $email,
            'password_hash' => password_hash($password, PASSWORD_BCRYPT),
            'status' => $status,
        ];

        $userModel = new User();
        $newUserId = $userModel->create($data);

        // ✅ Audit log
        log_audit('users', 'create', $newUserId, [
            'name' => $name,
            'email' => $email
        ]);

              redirect('users');
    }

    // ✅ Render form if not POST
    $this->view('users/form');
}

public function edit($id)
{
    ACL::ensure($this->module, 'edit');

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        verify_csrf();

        // Use model's find() method to get existing user
        $oldUser = (new User())->find((int)$id);
        if (!$oldUser) {
            abort(404, 'User not found');
        }

        // Prepare new data
        $data = [
            'name'   => $_POST['name'],
            'email'  => $_POST['email'],
            'status' => $_POST['status']
        ];
        if (!empty($_POST['password'])) {
            $data['password_hash'] = password_hash($_POST['password'], PASSWORD_BCRYPT);
        }

        // Update user
        (new User())->updateUser((int)$id, $data);

        // Compare old vs new for audit
        $changedFields = [];
        foreach ($data as $key => $newValue) {
            $oldValue = $oldUser[$key] ?? null;

            if ((string)$oldValue !== (string)$newValue) {
                // Mask password hash in audit log
                if ($key === 'password_hash') {
                    $changedFields[$key] = [
                        'old' => '***',
                        'new' => '***'
                    ];
                } else {
                    $changedFields[$key] = [
                        'old' => $oldValue,
                        'new' => $newValue
                    ];
                }
            }
        }

        // Log audit entry
        log_audit('users', 'edit', $id, ['changed_fields' => $changedFields]);

        redirect('users/index');
        return;
    }

    // GET request: load user for form
    $user = (new User())->find((int)$id);
    if (!$user) {
        abort(404, 'User not found');
    }

    $this->view('users/form', compact('user'));
}


  public function delete($id)
{
    ACL::ensure($this->module, 'delete');

    $userModel = new User();
    $user = $userModel->find((int)$id);

    if (!$user) {
        abort(404, 'User not found');
    }
    // Mask sensitive fields
if (isset($user['password_hash'])) {
    $user['password_hash'] = '***';
}

    // Log audit BEFORE deletion
    log_audit('users', 'delete', $id, ['deleted_record' => $user]);

    // Proceed with deletion
    $userModel->delete((int)$id);

    redirect('users/index');
}

public function details($id) {
    ACL::ensure($this->module, 'view');

    $userModel = new User();
    $user = $userModel->find($id);
    if (!$user) {
        redirect('users/index');
        return;
    }

    // Fetch all modules
    $modulesStmt = Database::pdo()->query("SELECT slug, name FROM modules ORDER BY name");
    $allModules = $modulesStmt->fetchAll(PDO::FETCH_ASSOC);

    // Define all possible actions
    $allActions = ['view','add','edit','update','delete','export'];

    // Fetch permissions
    $userPerms = $userModel->getPermissions($id); // includes 'allow'
    $rolePerms = $userModel->getRolePermissions($id); // now includes 'allow' => true

    // Build permissions matrix: default all false
    $permissionsMatrix = [];
    foreach ($allModules as $module) {
        $slug = $module['slug'];
        $permissionsMatrix[$slug] = array_fill_keys($allActions, false);
    }

    // Merge and apply all permissions
    $allPerms = array_merge($userPerms, $rolePerms);
    foreach ($allPerms as $perm) {
        if (isset($permissionsMatrix[$perm['module']][$perm['action']])) {
            $permissionsMatrix[$perm['module']][$perm['action']] = (bool)$perm['allow'];
        }
    }

    // Fetch audit logs
    $stmt = Database::pdo()->prepare("SELECT * FROM audit_logs WHERE user_id = ? ORDER BY created_at DESC");
    $stmt->execute([$id]);
    $auditLogs = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Fetch user action logs
    $stmt = Database::pdo()->prepare("SELECT * FROM login_attempts WHERE email = ? ORDER BY last_attempt DESC");
    $stmt->execute([$user['email']]);
    $actionLogs = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Pass everything to the view
    
    $this->view('users/details', compact(
        'user', 
        'allModules', 
        'allActions', 
        'permissionsMatrix', 
        'userPerms', 
        'rolePerms', 
        'auditLogs', 
        'actionLogs'
    ));
}


public function viewPermissions($userId)
{
    // Load the model if not already autoloaded
    $this->load->model('PermissionModel');

    // Fetch role-based and user-specific permissions
    $data['rolePermissions'] = $this->PermissionModel->getRolePermissions($userId);
    $data['userPermissions'] = $this->PermissionModel->getUserPermissions($userId);

    // Pass data to the view
    $this->load->view('users/details', $data);
}




}
