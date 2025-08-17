<?php
class PermissionsController extends Controller {
  private string $module = 'permissions';
  public function __construct(){ Auth::ensure(); }
  public function index(){ ACL::ensure($this->module,'view');
    $perms = (new Permission())->allWithModules();
    $this->view('permissions/index', compact('perms'));
  }
}
