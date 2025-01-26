<?php

class PostController
{
    private mysqli $connect;

    public function __construct($connect)
    {
        $this->connect = $connect;
    }

    public function index(): void
    {
        $model = new PostModel($this->connect);
        $posts = $model->getPosts();
        echo json_encode($posts);
    }

    public function show($id): void
    {
        $model = new PostModel($this->connect);
        $post = $model->getPost($id);
        if (empty($post)) {
            http_response_code(404);
            echo json_encode(['message' => 'Post not found']);
            exit;
        }
        echo json_encode($post);
    }

    public function store($data): void
    {
        $id = loginRequired($this->connect);

        $data = trimArray($data);

        $this->validatePostData($data);

        $data["author_id"] = $id;

        $model = new PostModel($this->connect);
        $id = $model->createPost($data);
        http_response_code(201);
        echo json_encode(array("id" => $id));
    }

    public function update($id, array $data): void
    {
        $userId = loginRequired($this->connect);

        $data = trimArray($data);

        $this->validatePostData($data);

        $model = new PostModel($this->connect);
        $oldPost = $model->getPost($id);

        if ($userId != $oldPost["author_id"]) {
            http_response_code(403);
            echo json_encode(array("message" => "You cannot edit your own post."));
            exit;
        }

        $id = $model->updatePost($id, $data);
        echo json_encode(array("id" => $id));
    }

    public function destroy($id): void
    {
        $model = new PostModel($this->connect);
        $model->deletePost($id);
        http_response_code(204);
        echo json_encode(array("message" => "Post deleted"));
    }

    private function validatePostData(array $data): void
    {
        validateRequiredFields($data, ["title", "body"]);

        validateMinLength($data["title"], "Title", 3);
        validateMinLength($data["body"], "Body", 3);

        validateMaxLength($data["title"], "title", 255);
        validateMaxLength($data["body"], "Body", 20000);
    }
}