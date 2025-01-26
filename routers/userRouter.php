<?php

global $router, $connect;

$router->get('/users', function () use ($connect) {
    $controller = new userController($connect);
    $controller->index();
});

$router->get('/users/{id}', function ($id) use ($connect) {
    $controller = new userController($connect);
    $controller->show($id);
});