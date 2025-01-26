<?php


use Firebase\JWT\JWT;

function generateAccessToken($userId): string
{
    $issuedAt = time();
    $expirationTime = $issuedAt + ACCESS_TOKEN_EXPIRATION;

    $payload = [
        'userId' => $userId,
        'iat' => $issuedAt,
        'exp' => $expirationTime,
    ];

    return JWT::encode($payload, ACCESS_TOKEN_SECRET, 'HS256');
}

function decodeAccessToken(string $token): ?stdClass
{
    try {
        return JWT::decode($token, new Firebase\JWT\Key(ACCESS_TOKEN_SECRET, 'HS256'));
    } catch (Exception){
        return null;
    }
}

function generateRefreshToken($userId): string
{
    $issuedAt = time();
    $expirationTime = $issuedAt + REFRESH_TOKEN_EXPIRATION;

    $payload = [
        'userId' => $userId,
        'iat' => $issuedAt,
        'exp' => $expirationTime,
    ];

    return JWT::encode($payload, REFRESH_TOKEN_SECRET, 'HS256');
}

function decodeRefreshToken(string $token): ?stdClass
{
    try {
       return JWT::decode($token, new Firebase\JWT\Key(REFRESH_TOKEN_SECRET, 'HS256'));
    } catch (Exception){
        return null;
    }
}

function validateRequiredFields(array $data, array $requiredFields): void
{
    foreach ($requiredFields as $field) {
        if (!isset($data[$field]) || empty(trim($data[$field]))) {
            http_response_code(400);
            echo json_encode(array("message" => "$field is required"));
            exit;
        }
    }
}

function validateMinLength(string $str,string $field, int $minLength): void
{
    if (strlen($str) < $minLength) {
        http_response_code(400);
        echo json_encode(array("message" => "$field must be at least $minLength characters."));
        exit;
    }
}

function validateMaxLength(string $str, string $field, int $maxLength): void
{
    if (strlen($str) > $maxLength) {
        http_response_code(400);
        echo json_encode(array("message" => "$field must be less than $maxLength characters."));
        exit;
    }
}

function trimArray(array $array): array
{
    foreach ($array as $key => $value) {
        if (is_string($value)) {
            $array[$key] = trim($value);
        }
    }
    return $array;
}

function validateEmail(string $email): void
{
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo json_encode(array("message" => "Invalid email address"));
        exit;
    }
}


function validatePassword(string $password): void
{
    if (strlen($password) < 8) {
        echo json_encode(array("message" => "Password must be at least 8 characters long"));
        exit;
    }

    if (!preg_match('/\d/', $password)) {
        echo json_encode(array("message" => "Password must contain at least one digit"));
        exit;
    }

    if (!preg_match('/[A-Za-z]/', $password)) {
        echo json_encode(array("message" => "Password must contain at least one letter"));
        exit;
    }
}

function refreshAccessToken(mysqli $connect ,string $refreshToken): ?string
{
    $tokenModel = new TokenModel($connect);
    $storedToken = $tokenModel->getToken($refreshToken);
    if (!$storedToken) {
        return null;
    }

    if (strtotime($storedToken['expires_in']) < time()) {
        return null;
    }

    $userId = $storedToken['user_id'];

    return generateAccessToken($userId);
}

function loginRequired(mysqli $connect): int
{
    $accessToken = $_COOKIE['access_token'] ?? null;
    $refreshToken = $_COOKIE['refresh_token'] ?? null;
    if (!$refreshToken) {
        http_response_code(401);
        echo json_encode(['message' => 'Unauthorized']);
        exit;
    }
    $decodedAccessToken = decodeRefreshToken($accessToken);
    if($accessToken && $decodedAccessToken) {
        return $decodedAccessToken->userId;
    }



    $newAccessToken = refreshAccessToken($connect ,$refreshToken);
    if (!$newAccessToken) {
        http_response_code(401);
        echo json_encode(['message' => 'Unauthorized']);
        exit;
    }

    setcookie("access_token", $newAccessToken, time() + ACCESS_TOKEN_EXPIRATION, "/", false, false);
    return decodeAccessToken($newAccessToken)->userId;
}