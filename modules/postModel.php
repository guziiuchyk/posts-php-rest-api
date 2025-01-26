<?php

class PostModel {
    private mysqli $connect;

    public function __construct($connect) {
        $this->connect = $connect;
    }

    public function getPosts(): array
    {
        $sql = "SELECT * FROM posts";
        $stmt = $this->connect->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        $posts = [];
        while ($row = $result->fetch_assoc()) {
            $posts[] = $row;
        }
        $stmt->close();
        return $posts;
    }
    public function getPost(int $id): array
    {
        $sql = "SELECT * FROM posts WHERE id = ?";
        $stmt = $this->connect->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        if ($row) {
             return $row;
        } else {
            return [];
        }
    }

    public function createPost(array $data): int
    {
        $sql = "INSERT INTO posts (title, body, author_id) VALUES (?, ?, ?)";
        $stmt = $this->connect->prepare($sql);
        $stmt->bind_param("ssi", $data['title'], $data['body'], $data['author_id']);
        $stmt->execute();
        $lastInsertId = $this->connect->insert_id;
        $stmt->close();
        return $lastInsertId;
    }

    public function updatePost(int $id, array $data): int
    {
        $sql = "UPDATE posts SET `title` = ?, `body` = ? WHERE `id` = ?";
        $stmt = $this->connect->prepare($sql);
        $stmt->bind_param("ssi", $data['title'], $data['body'], $id);
        $stmt->execute();
        $stmt->close();
        return $id;
    }

    public function deletePost(int $id): void
    {
        $sql = "DELETE FROM posts WHERE id = ?";
        $stmt = $this->connect->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
    }
}