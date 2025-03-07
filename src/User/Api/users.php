<?php
use Src\User\Services\UserService;

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    header("HTTP/1.1 405 Method Not Allowed");
    echo json_encode(["error" => "Only GET requests are allowed"]);
    exit;
}

if (!isset($_GET["username"]) || trim($_GET["username"]) === "") {
    header("HTTP/1.1 400 Bad Request");
    echo json_encode(["error" => "Username is required"]);
    exit;
}

$username = htmlspecialchars(trim($username), ENT_QUOTES, 'UTF-8');

$userService = new UserService();
$user = $userService->getUserByUsername($username);

if ($user) {
    header("HTTP/1.1 200 OK");

    // TODO: Consider creating a DTO
    echo json_encode([
        "id" => $user->id,
        "username" => $user->username,
        "created_at" => $user->createdAt
    ]);
} else {
    header("HTTP/1.1 404 Not Found");
    echo json_encode(["error" => "User not found"]);
}
exit;
?>