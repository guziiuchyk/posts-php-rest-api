<?php

global $router, $connect;

$router->get('/posts', function () use ($connect) {
    $controller = new PostController($connect);
    $controller->index();
});

$router->get('/posts/{id}', function ($id) use ($connect) {
    $controller = new PostController($connect);
    $controller->show($id);
});

$router->post('/posts', function () use ($connect) {
    $data = json_decode(file_get_contents('php://input'), true) ?? [];
    $controller = new PostController($connect);
    $controller->store($data);
});

$router->delete('/posts/{id}', function ($id) use ($connect) {
    $controller = new PostController($connect);
     $controller->destroy($id);
});

$router->put('/posts/{id}', function ($id) use ($connect) {
    $data = json_decode(file_get_contents('php://input'), true) ?? [];
    $controller = new PostController($connect);
    $controller->update($id, $data);
});