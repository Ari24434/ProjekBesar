<?php

function auth_user(bool $fresh = false): ?array
{
    static $cachedUser = null;

    if (!$fresh && $cachedUser !== null) {
        return $cachedUser;
    }

    $idUser = (int) ($_SESSION['user']['id_user'] ?? $_SESSION['id_user'] ?? 0);

    if ($idUser <= 0) {
        $cachedUser = null;
        return null;
    }

    $cachedUser = db_fetch(
        "SELECT id_user, nama, email, no_hp, role, status, foto, created_at, last_login
         FROM user
         WHERE id_user = ? AND status = 'aktif'
         LIMIT 1",
        [$idUser]
    );

    if (!$cachedUser) {
        auth_forget_session();
        return null;
    }

    $_SESSION['user'] = $cachedUser;
    $_SESSION['id_user'] = (int) $cachedUser['id_user'];
    $_SESSION['role'] = $cachedUser['role'];

    return $cachedUser;
}

function auth_check(): bool
{
    return auth_user() !== null;
}

function auth_has_role(string|array $roles): bool
{
    $user = auth_user();

    if (!$user) {
        return false;
    }

    $allowedRoles = is_array($roles) ? $roles : [$roles];

    return in_array($user['role'], $allowedRoles, true);
}

function auth_attempt(string $email, string $password): bool
{
    $email = strtolower(trim($email));

    if ($email === '' || $password === '') {
        return false;
    }

    $user = db_fetch(
        "SELECT id_user, nama, email, no_hp, role, status, password, foto, created_at, last_login
         FROM user
         WHERE email = ? AND status = 'aktif'
         LIMIT 1",
        [$email]
    );

    if (!$user || !password_verify($password, $user['password'])) {
        return false;
    }

    session_regenerate_id(true);

    unset($user['password']);
    $_SESSION['user'] = $user;
    $_SESSION['id_user'] = (int) $user['id_user'];
    $_SESSION['role'] = $user['role'];
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

    db_execute('UPDATE user SET last_login = NOW() WHERE id_user = ?', [(int) $user['id_user']]);

    return true;
}

function auth_logout(): void
{
    auth_forget_session();
    session_regenerate_id(true);
}

function auth_forget_session(): void
{
    unset($_SESSION['user'], $_SESSION['id_user'], $_SESSION['role'], $_SESSION['csrf_token']);
}

function auth_home_url(?array $user = null): string
{
    $user = $user ?? auth_user();

    if (($user['role'] ?? null) === 'admin') {
        return BASE_URL . '/Admin/beranda';
    }

    if (($user['role'] ?? null) === 'peserta') {
        return BASE_URL . '/user/beranda';
    }

    return BASE_URL . '/login';
}

function auth_redirect(string $url): void
{
    header('Location: ' . $url);
    exit;
}

function auth_flash(string $message, string $type = 'error'): void
{
    $_SESSION['flash'] = [
        'message' => $message,
        'type' => $type,
    ];
}

function csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf_token'];
}

function csrf_field(): string
{
    return '<input type="hidden" name="_csrf" value="' . htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8') . '">';
}

function csrf_verify(?string $token): bool
{
    return is_string($token)
        && isset($_SESSION['csrf_token'])
        && hash_equals($_SESSION['csrf_token'], $token);
}

?>
