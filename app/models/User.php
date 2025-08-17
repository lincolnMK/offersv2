<?php
class User extends Model {
  public function findByEmail(string $email) {
    $stmt = $this->db->prepare('SELECT * FROM users WHERE email=? LIMIT 1');
    $stmt->execute([$email]);
    return $stmt->fetch();
  }

  public function find(int $id): ?array {
        $pdo = Database::pdo();
        $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $user ?: null;
    }

  public function all() {
    return $this->db->query('SELECT id,name,email,status FROM users ORDER BY id DESC')->fetchAll();
  }
  public function create(array $data) {
    $stmt = $this->db->prepare('INSERT INTO users(name,email,password_hash,status) VALUES(?,?,?,?)');
    $stmt->execute([$data['name'],$data['email'],$data['password_hash'],$data['status']??'active']);
    return $this->db->lastInsertId();
  }
  public function updateUser(int $id, array $data) {
    $fields=[]; $vals=[];
    foreach ($data as $k=>$v) { $fields[]="$k=?"; $vals[]=$v; }
    $vals[]=$id;
    $sql='UPDATE users SET '.implode(',', $fields).' WHERE id=?';
    $stmt=$this->db->prepare($sql); $stmt->execute($vals);
  }
  public function delete(int $id) {
    $stmt=$this->db->prepare('DELETE FROM users WHERE id=?');
    $stmt->execute([$id]);
  }



    public function getPermissions(int $userId): array {
        $pdo = Database::pdo();

        $stmt = $pdo->prepare("
            SELECT m.slug AS module, p.action, up.allow
            FROM user_permissions up
            JOIN permissions p ON up.permission_id = p.id
            JOIN modules m ON p.module_id = m.id
            WHERE up.user_id = ?
        ");
        $stmt->execute([$userId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Optionally, get role-based permissions too
     */
   public function getRolePermissions(int $userId): array {
    $pdo = Database::pdo();

    $stmt = $pdo->prepare("
        SELECT m.slug AS module, p.action
        FROM user_roles ur
        JOIN role_permissions rp ON ur.role_id = rp.role_id
        JOIN permissions p ON rp.permission_id = p.id
        JOIN modules m ON p.module_id = m.id
        WHERE ur.user_id = ?
    ");
    $stmt->execute([$userId]);

    // Normalize output to include 'allow' => true
    $rawPerms = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return array_map(function($perm) {
        return [
            'module' => $perm['module'],
            'action' => $perm['action'],
            'allow'  => true
        ];
    }, $rawPerms);
}
}


