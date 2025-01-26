<?php

global $router, $connect;

$router->post('/login', function() use ($connect) {
    $data = json_decode(file_get_contents('php://input'), true) ?? [];
    $controller = new AuthController($connect);
    $controller->login($data);
});

$router->post('/register', function() use ($connect) {
    $data = json_decode(file_get_contents('php://input'), true) ?? [];
    $controller = new AuthController($connect);
    $controller->register($data);
});

$router->post('/logout', function() use ($connect) {
    $controller = new AuthController($connect);
    $controller->logout();
});