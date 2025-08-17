<?php
class RolesController extends Controller {
  private string $module = 'roles';
  public function __construct(){ Auth::ensure(); }
  public function index(){ ACL::ensure($this->module,'view');
    $roles = (new Role())->all();
    $this->view('roles/index', compact('roles'));
  }
  public function create(){ ACL::ensure($this->module,'add');
    if ($_SERVER['REQUEST_METHOD']==='POST') {
      verify_csrf();
      $id = (new Role())->create(['name'=>$_POST['name'], 'description'=>$_POST['description'] ?? null]);
       redirect('roles/index'); return;
    }
    $this->view('roles/form');
  }
  public function edit($id){ ACL::ensure($this->module,'edit');
    $roleModel = new Role();
    if ($_SERVER['REQUEST_METHOD']==='POST') {
      verify_csrf();
      $roleModel->updateRole((int)$id, ['name'=>$_POST['name'], 'description'=>$_POST['description'] ?? null]);
       redirect('roles/index'); return;
    }
    $role = $roleModel->find((int)$id);
    $this->view('roles/form', compact('role'));
  }
  public function delete($id){ ACL::ensure($this->module,'delete');
    (new Role())->delete((int)$id);
     redirect('roles/index');
  }
  public function permissions($id){ ACL::ensure('permissions','update');
    $permModel = new Permission();
    $roleModel = new Role();
    $perms = $permModel->allWithModules();
    $current = $roleModel->permissionsForRole((int)$id);
    $role = $roleModel->find((int)$id);
    if ($_SERVER['REQUEST_METHOD']==='POST') {
      verify_csrf();
      $ids = array_map('intval', $_POST['perm'] ?? []);
      $roleModel->setPermissions((int)$id, $ids);
       redirect('roles/index'); return;
    }
    $this->view('roles/permissions', compact('perms','current','role'));
  }
}
