<?php

class userController
{
    private mysqli $connect;

    public function __construct($connect)
    {
        $this->connect = $connect;
    }

    public function index(): void
    {
        $userModel = new userModel($this->connect);
        $users = $userModel->getUsers();
        echo json_encode($users);
    }

    public function show($id): void
    {
        $userModel = new userModel($this->connect);
        $user = $userModel->getUserById($id);
        echo json_encode($user);
    }
}