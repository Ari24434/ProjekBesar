<?php
session_start();

$env = parse_ini_file(__DIR__ . '/../.env');

define('BASE_PATH', dirname(__DIR__));
define('BASE_URL', $env['APP_URL'] ?? '');

require BASE_PATH . '/app/Pusat/routing.php';

require BASE_PATH . '/app/helpers/url.php';

require BASE_PATH . '/routes/router.php';

request($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);

?>