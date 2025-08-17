<?php
class App {
  public function run() {
    $url = $_GET['url'] ?? '';
    $url = trim($url, '/');
    $parts = $url ? explode('/', $url) : [];

    $controller = !empty($parts[0]) ? ucfirst($parts[0]) . 'Controller' : 'HomeController';
    $method = $parts[1] ?? 'index';
    $params = array_slice($parts, 2);

    if (!class_exists($controller)) { http_response_code(404); echo 'Controller not found'; return; }
    $instance = new $controller();
    if (!method_exists($instance, $method)) { http_response_code(404); echo 'Method not found'; return; }

    call_user_func_array([$instance, $method], $params);
  }
}
