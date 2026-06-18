<?php
$secureSession = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');

session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'domain' => '',
    'secure' => $secureSession,
    'httponly' => true,
    'samesite' => 'Lax',
]);

session_start();

header('X-Frame-Options: SAMEORIGIN');
header('X-Content-Type-Options: nosniff');
header('Referrer-Policy: strict-origin-when-cross-origin');

define('BASE_PATH', dirname(__DIR__));

function app_env_value(string $key, mixed $default = null): mixed
{
    $envPath = BASE_PATH . '/.env';

    if (!is_file($envPath)) {
        return $default;
    }

    foreach (file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        $line = trim($line);

        if ($line === '' || str_starts_with($line, '#') || !str_contains($line, '=')) {
            continue;
        }

        [$envKey, $value] = explode('=', $line, 2);

        if (trim($envKey) === $key) {
            return trim(trim($value), "\"'");
        }
    }

    return $default;
}

define('BASE_URL', rtrim(app_env_value('APP_URL', 'http://localhost'), '/'));

require BASE_PATH . '/app/helpers/url.php';
require BASE_PATH . '/app/Pusat/db.php';
require BASE_PATH . '/app/Auth/Auth.php';
require BASE_PATH . '/app/Middleware/Middleware.php';
require BASE_PATH . '/app/Pusat/routing.php';

require BASE_PATH . '/link/link.php';

request($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);

?>
