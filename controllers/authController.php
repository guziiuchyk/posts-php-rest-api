<?php

class authController
{
    private mysqli $connect;

    public function __construct($connect)
    {
        $this->connect = $connect;
    }

    public function login($data):void
    {
        validateRequiredFields($data, ["email", "password"]);
        validateEmail($data['email']);

        $userModel = new userModel($this->connect);
        $user = $userModel->getUserByEmail($data['email']);

        if (!$user) {
            http_response_code(401);
            echo json_encode(["message" => "Invalid email or password"]);
            exit;
        }
        if(!password_verify($data["password"], $user["password"])) {
            http_response_code(401);
            echo json_encode(["message" => "Invalid email or password"]);
            exit;
        }
        $refreshToken = generateRefreshToken($user["id"]);
        $expires_in = time() + REFRESH_TOKEN_EXPIRATION;
        $expires_in_sql_format = date('Y-m-d H:i:s', $expires_in);

        $tokenModel = new tokenModel($this->connect);
        $tokenModel->createToken(["token" => $refreshToken, "user_id" => $user["id"], "expires_in" => $expires_in_sql_format]);

        setcookie("refresh_token", $refreshToken, $expires_in, "/", false, false);
        setcookie("access_token", generateAccessToken($user["id"]), time() + ACCESS_TOKEN_EXPIRATION, "/", false, false);

        http_response_code(200);
        echo json_encode(["message" => "Logged in successfully"]);
    }

    public function logout():void
    {
        $tokenModel = new tokenModel($this->connect);
        $tokenModel->deleteToken($_COOKIE["refresh_token"]);

        setcookie("access_token", "", time() - ACCESS_TOKEN_EXPIRATION, "/", false, false);
        setcookie("refresh_token", "", time() - REFRESH_TOKEN_EXPIRATION, "/", false, false);
        echo json_encode(["message" => "Logged out successfully"]);
    }

    public function register($data): void
    {
        validateRequiredFields($data, ['email', 'password', "name"]);
        $data = trimArray($data);
        validateEmail($data['email']);
        validateMinLength($data['name'], "Name", 3);
        validateMaxLength($data['name'], "Name", 255);
        validatePassword($data['password']);

        $data["password"] = password_hash($data["password"], PASSWORD_DEFAULT);

        try {
            $userModel = new UserModel($this->connect);
            $userModel->createUser($data);
            http_response_code(201);
            echo json_encode(["message" => "User created"]);
        } catch (mysqli_sql_exception $e) {
            if ($e->getCode() === 1062) {
                http_response_code(400);
                echo json_encode(["message" => "User with this email already exists"]);
            } else {
                http_response_code(500);
                echo json_encode(["message" => "Internal server error"]);
            }
        }
    }

    public function checkToken(): void
    {
        loginRequired($this->connect);
        echo json_encode(["message" => "Logged in successfully"]);
    }
}