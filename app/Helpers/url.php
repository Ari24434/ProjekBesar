<?php

function url($path = '') {
    return rtrim(BASE_URL, '/') . '/' . ltrim($path, '/');
}

function current_path() {
    return rtrim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
}

?>