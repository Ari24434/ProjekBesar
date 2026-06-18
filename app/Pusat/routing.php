<?php

$routes = [];

function get($path, $callback) {
    global $routes;
    $routes['GET'][$path] = $callback;
}

function post($path, $callback) {
    global $routes;
    $routes['POST'][$path] = $callback;
}

function delete($path, $callback) {
    global $routes;
    $routes['DELETE'][$path] = $callback;
}

function put($path, $callback) {
    global $routes;
    $routes['PUT'][$path] = $callback;
}

function request($uri, $method) {
    global $routes;

    $uri = parse_url($uri, PHP_URL_PATH);
    $method = strtoupper($_POST['_method'] ?? $method);

    middleware_handle($uri, $method);

    $callback = $routes[$method][$uri] ?? null;

    if (!$callback) {
        http_response_code(404);
        echo "Lagi eror mas";
        return;
    }

    call_user_func($callback);
}

?>
