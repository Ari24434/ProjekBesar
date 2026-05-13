<?php

$routes = [];

function get($path, $callback) {
    global $routes;
    $routes['GET'][$path] = $callback;
}

function request($uri, $method) {
    global $routes;

    $uri = parse_url($uri, PHP_URL_PATH);

    $callback = $routes[$method][$uri] ?? null;

    if (!$callback) {
        http_response_code(404);
        echo "Lagi eror mas";
        return;
    }

    call_user_func($callback);
}

?>