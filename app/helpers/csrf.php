<?php
function csrf_token(): string {
  if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
  }
  return $_SESSION['csrf_token'];
}
function csrf_field(): string {
  return '<input type="hidden" name="_token" value="'.htmlspecialchars(csrf_token(), ENT_QUOTES).'">';
}
function verify_csrf(): void {
  $token = $_POST['_token'] ?? '';
  if (!hash_equals($_SESSION['csrf_token'] ?? '', $token)) {
    http_response_code(419);
    echo 'CSRF token mismatch';
    exit;
  }
}
