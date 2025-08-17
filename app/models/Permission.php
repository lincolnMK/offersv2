<?php
class Permission extends Model {
  public function allWithModules() {
    $sql = 'SELECT p.*, m.name as module_name, m.slug as module_slug
            FROM permissions p JOIN modules m ON m.id=p.module_id
            ORDER BY m.name, p.action';
    return $this->db->query($sql)->fetchAll();
  }
}
