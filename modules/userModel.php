<?php

class userModel {
    private mysqli $connect;

    public function __construct($connect)
    {
        $this->connect = $connect;
    }

    public function getUsers():array
    {
        $sql = "SELECT id,name FROM users";
        $stmt = $this->connect->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        $users = [];
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }
        $stmt->close();
        return $users;
    }

    public function getUserById(int $id): array
    {
        $sql = "SELECT id,name FROM users WHERE id=?";
        $stmt = $this->connect->prepare($sql);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        return $row;
    }

    public function createUser(array $data) :void
    {
        $sql = "INSERT INTO users (email, password, name) VALUES (?, ?, ?)";
        $stmt = $this->connect->prepare($sql);
        $stmt->bind_param("sss", $data['email'], $data["password"], $data['name']);
        $stmt->execute();
        $stmt->close();
    }

    public function getUserByEmail($email) :array
    {
        $sql = "SELECT * FROM users WHERE email=?";
        $stmt = $this->connect->prepare($sql);
        $stmt->bind_param('i', $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        return $row;
    }
}