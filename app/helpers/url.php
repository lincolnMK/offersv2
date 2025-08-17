<?php
/**
 * Build a full URL based on base_url from config
 */
function url(string $path = ''): string {
    $config = require __DIR__ . '/../../config/config.php';
    $base = rtrim($config['app']['base_url'], '/');
    $path = ltrim($path, '/');
    return $base . '/' . $path;
}

/**
 * Redirect to a URL based on base_url
 */
function redirect(string $path = ''): void {
    header('Location: ' . url($path));
    exit;
}
