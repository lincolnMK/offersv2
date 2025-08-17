<?php
class Module extends Model {
  public function all(){ return $this->db->query('SELECT * FROM modules ORDER BY name')->fetchAll(); }
}
