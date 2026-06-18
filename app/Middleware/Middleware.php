<?php

function middleware_handle(string $uri, string $method): void
{
    $path = rtrim(parse_url($uri, PHP_URL_PATH) ?? '/', '/') ?: '/';
    $method = strtoupper($method);

    middleware_verify_csrf($path, $method);
    middleware_guard_auth($path);
}

function middleware_verify_csrf(string $path, string $method): void
{
    if (!in_array($method, ['POST', 'PUT', 'PATCH', 'DELETE'], true)) {
        return;
    }

    if (!csrf_verify($_POST['_csrf'] ?? null)) {
        http_response_code(419);
        auth_flash('Sesi formulir sudah tidak valid. Silakan ulangi aksi tadi.', 'error');

        if ($path === '/login') {
            auth_redirect(BASE_URL . '/login');
        }

        $user = auth_user();
        auth_redirect($user ? auth_home_url($user) : BASE_URL . '/login');
    }
}

function middleware_guard_auth(string $path): void
{
    $publicRoutes = ['/', '/login'];

    if (in_array($path, $publicRoutes, true)) {
        if ($path === '/login' && auth_check() && ($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'GET') {
            auth_redirect(auth_home_url());
        }

        return;
    }

    if ($path === '/logout') {
        return;
    }

    if (str_starts_with($path, '/Admin')) {
        middleware_require_role('admin');
        return;
    }

    if (str_starts_with($path, '/user')) {
        middleware_require_role('peserta');
    }
}

function middleware_require_role(string $role): void
{
    $user = auth_user();

    if (!$user) {
        auth_flash('Silakan login terlebih dahulu untuk mengakses halaman tersebut.', 'error');
        auth_redirect(BASE_URL . '/login');
    }

    if (($user['role'] ?? '') !== $role) {
        http_response_code(403);
        auth_flash('Akun kamu tidak memiliki akses ke halaman tersebut.', 'error');
        auth_redirect(auth_home_url($user));
    }
}

?>
