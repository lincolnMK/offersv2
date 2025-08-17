<?php
spl_autoload_register(function ($class) {
    $dirs = ['core', 'controllers', 'models', 'middleware'];
    foreach ($dirs as $dir) {
        $path = __DIR__ . '/../' . $dir . '/' . $class . '.php';
        if (file_exists($path)) { require_once $path; return; }
    }
});
