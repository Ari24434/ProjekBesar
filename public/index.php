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

date_default_timezone_set((string) app_env_value('APP_TIMEZONE', 'Asia/Jakarta'));

function app_request_base_url(): string
{
    $host = $_SERVER['HTTP_X_FORWARDED_HOST'] ?? $_SERVER['HTTP_HOST'] ?? 'localhost';
    $host = trim(explode(',', $host)[0]);

    $proto = $_SERVER['HTTP_X_FORWARDED_PROTO'] ?? null;

    if ($proto) {
        $scheme = trim(explode(',', $proto)[0]);
    } else {
        $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    }

    return $scheme . '://' . $host;
}

function app_base_url(): string
{
    $configuredUrl = rtrim((string) app_env_value('APP_URL', ''), '/');
    $requestUrl = app_request_base_url();
    $requestHost = parse_url($requestUrl, PHP_URL_HOST) ?: '';
    $configuredHost = parse_url($configuredUrl, PHP_URL_HOST) ?: '';

    if ($configuredUrl === '') {
        return $requestUrl;
    }

    $configuredIsLocal = in_array($configuredHost, ['localhost', '127.0.0.1', '::1'], true);
    $requestIsLocal = in_array($requestHost, ['localhost', '127.0.0.1', '::1'], true);

    if ($configuredIsLocal && !$requestIsLocal) {
        return $requestUrl;
    }

    return $configuredUrl;
}

define('BASE_URL', app_base_url());

require BASE_PATH . '/app/helpers/url.php';
require BASE_PATH . '/app/Pusat/db.php';
require BASE_PATH . '/app/Auth/Auth.php';
require BASE_PATH . '/app/Middleware/Middleware.php';
require BASE_PATH . '/app/Pusat/routing.php';

require BASE_PATH . '/link/link.php';

request($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);

?>
