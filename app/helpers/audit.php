<?php

function log_audit($moduleSlug, $action, $entityId = null, $details = null)
{
    // Ensure session is active
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }

    $userId = $_SESSION['user']['id'] ?? null;

    if (!$userId) {
        return false; // Optionally log anonymous or skip
    }

    // Load the model manually
    require_once __DIR__ . '/../models/AuditModel.php';
    $audit = new AuditModel();

    //return $audit->log($userId, $moduleSlug, $action, $entityId, $details);
    $result = $audit->log($userId, $moduleSlug, $action, $entityId, $details);
if (!$result) {
    error_log('Audit log failed');
}


}