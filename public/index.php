<?php
$config = require __DIR__ . '/../config/config.php';
session_name($config['app']['session_name']);
session_start();

require_once __DIR__ . '/../app/core/Autoload.php';
require_once __DIR__ . '/../app/helpers/auth.php';
require_once __DIR__ . '/../app/helpers/csrf.php';
require_once __DIR__ . '/../app/helpers/url.php';
require_once __DIR__ . '/../app/helpers/audit.php';

$app = new App();
$app->run();
