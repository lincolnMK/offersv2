<?php

class AuditModel extends Model
{
    public function insert($table, $data)
    {
        $columns = implode(', ', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));

        $sql = "INSERT INTO $table ($columns) VALUES ($placeholders)";
        $stmt = $this->db->prepare($sql);

        return $stmt->execute(array_combine(
            array_map(fn($k) => ":$k", array_keys($data)),
            array_values($data)
        ));
    }

    public function log($userId, $moduleSlug, $action, $entityId = null, $details = null)
    {
        $data = [
            'user_id' => $userId,
            'module_slug' => $moduleSlug,
            'action' => $action,
            'entity_id' => $entityId,
            'details' => $details ? json_encode($details) : null,
            'created_at' => date('Y-m-d H:i:s')
        ];

        return $this->insert('audit_logs', $data);
    }

    public function getRecentLogs($limit = 100)
    {
        $sql = "SELECT * FROM audit_logs ORDER BY created_at DESC LIMIT :limit";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', (int) $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
