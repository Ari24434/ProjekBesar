<?php

function url($path = '') {
    return rtrim(BASE_URL, '/') . '/' . ltrim($path, '/');
}

function asset_url(string $path): string {
    $path = ltrim($path, '/');
    $url = url($path);
    $filePath = defined('BASE_PATH') ? BASE_PATH . '/public/' . $path : null;

    if ($filePath && is_file($filePath)) {
        $url .= '?v=' . filemtime($filePath);
    }

    return $url;
}

function current_url() {
    return url($_SERVER['REQUEST_URI']);
}

function current_path() {
    return rtrim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
}



?>
