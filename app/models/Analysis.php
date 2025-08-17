<?php
class Analysis extends Model {
    public function all() {
        return $this->db->query('SELECT * FROM users ORDER BY id DESC')->fetchAll();
    }

    public function find(int $id) {
        $stmt = $this->db->prepare('SELECT * FROM analysis WHERE id=?');
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create(array $data) {
        $stmt = $this->db->prepare('INSERT INTO analysis(name, description) VALUES(?, ?)');
        $stmt->execute([$data['name'], $data['description'] ?? null]);
        return $this->db->lastInsertId();
    }

    public function updateAnalysis(int $id, array $data) {
        $stmt = $this->db->prepare('UPDATE analysis SET name=?, description=? WHERE id=?');
        $stmt->execute([$data['name'], $data['description'] ?? null, $id]);
    }

    public function delete(int $id) {
        $stmt = $this->db->prepare('DELETE FROM analysis WHERE id=?');
        $stmt->execute([$id]);
    }

}