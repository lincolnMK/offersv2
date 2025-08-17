<?php
function auth_user() { return $_SESSION['user'] ?? null; }
function auth_id() { return $_SESSION['user']['id'] ?? null; }
function is_logged_in(): bool { return !!auth_user(); }

function regenerate_session() {
  if (function_exists('session_create_id')) {
    session_regenerate_id(true);
  }
}

// Permission check
function can(string $moduleSlug, string $action): bool {
  $user = auth_user();
  if (!$user) return false;
  $pdo = Database::pdo();

  // explicit user override
  $stmt = $pdo->prepare("SELECT up.allow
    FROM user_permissions up
    JOIN permissions p ON p.id=up.permission_id
    JOIN modules m ON m.id=p.module_id
    WHERE up.user_id=? AND m.slug=? AND p.action=? LIMIT 1");
  $stmt->execute([$user['id'], $moduleSlug, $action]);
  $ovr = $stmt->fetch();
  if ($ovr !== false) return (bool)$ovr['allow'];

  // role-based
  $stmt = $pdo->prepare("SELECT 1
    FROM user_roles ur
    JOIN role_permissions rp ON rp.role_id=ur.role_id
    JOIN permissions p ON p.id=rp.permission_id
    JOIN modules m ON m.id=p.module_id
    WHERE ur.user_id=? AND m.slug=? AND p.action=? LIMIT 1");
  $stmt->execute([$user['id'], $moduleSlug, $action]);
  return (bool)$stmt->fetch();
}

function require_login() {
  if (!is_logged_in()) {  redirect('auth/login'); exit; }
}

function require_can($module, $action) {
  if (!can($module, $action)) { http_response_code(403); echo 'Forbidden'; exit; }
}
