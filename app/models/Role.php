<?php
class Role extends Model {
  public function all() {
    return $this->db->query('SELECT * FROM roles ORDER BY id DESC')->fetchAll();
  }
  public function find(int $id) {
    $stmt = $this->db->prepare('SELECT * FROM roles WHERE id=?');
    $stmt->execute([$id]);
    return $stmt->fetch();
  }
  public function create(array $data) {
    $stmt = $this->db->prepare('INSERT INTO roles(name,description) VALUES(?,?)');
    $stmt->execute([$data['name'],$data['description']??null]);
    return $this->db->lastInsertId();
  }
  public function updateRole(int $id, array $data) {
    $stmt = $this->db->prepare('UPDATE roles SET name=?, description=? WHERE id=?');
    $stmt->execute([$data['name'],$data['description']??null,$id]);
  }
  public function delete(int $id) {
    $stmt = $this->db->prepare('DELETE FROM roles WHERE id=?');
    $stmt->execute([$id]);
  }
  public function permissionsForRole(int $roleId) {
    $stmt = $this->db->prepare('SELECT permission_id FROM role_permissions WHERE role_id=?');
    $stmt->execute([$roleId]);
    return array_column($stmt->fetchAll(), 'permission_id');
  }
  public function setPermissions(int $roleId, array $permissionIds) {
    $this->db->beginTransaction();
    $stmt = $this->db->prepare('DELETE FROM role_permissions WHERE role_id=?');
    $stmt->execute([$roleId]);
    if ($permissionIds) {
      $stmt = $this->db->prepare('INSERT INTO role_permissions(role_id, permission_id) VALUES(?,?)');
      foreach ($permissionIds as $pid) { $stmt->execute([$roleId,(int)$pid]); }
    }
    $this->db->commit();
  }
}
