<?php

class TokenModel {
    private mysqli $connect;

    public function __construct($connect) {
        $this->connect = $connect;
    }

    public function getToken($token): array
    {
        $sql = "SELECT * FROM `tokens` WHERE token=?";
        $stmt = $this->connect->prepare($sql);
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        return $row;
    }

    public function createToken($data):void
    {
        $sql = "INSERT INTO `tokens` (`user_id`, token, expires_in) VALUES(?, ?, ?)";
        $stmt = $this->connect->prepare($sql);
        $stmt->bind_param("iss", $data['user_id'], $data['token'], $data['expires_in']);
        $stmt->execute();
        $stmt->close();
    }

    public function deleteToken($token):void
    {
        $sql = "DELETE FROM `tokens` WHERE token=?";
        $stmt = $this->connect->prepare($sql);
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $stmt->close();
    }
}