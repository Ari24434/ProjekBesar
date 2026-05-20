<?php
session_start();

define('BASE_PATH', dirname(__DIR__));
define('BASE_URL', "http://localhost");

require BASE_PATH . '/app/Pusat/routing.php';

require BASE_PATH . '/app/helpers/url.php';

require BASE_PATH . '/link/link.php';

request($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);

?>