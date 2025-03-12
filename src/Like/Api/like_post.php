<?php
namespace Src\Like\Api;

use Src\Auth\Services\AuthService;
use Src\Like\Services\LikeService;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("HTTP/1.1 405 Method Not Allowed");
    echo json_encode(["error" => "Only POST requests are allowed"]);
    exit;
}

$authService = new AuthService();

if (!$authService->isAuthenticated()) {
    header("HTTP/1.1 401 Unauthorized");
    echo json_encode(["error" => "Authentication required"]);
    exit;
}

if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {
    header("HTTP/1.1 400 Bad Request");
    echo json_encode(["error" => "Post ID is required and must be a valid number"]);
    exit;
}

$postId = (int) $_GET["id"];
$userId = $authService->getAuthenticatedUser()->id;
$likeService = new LikeService();

if ($likeService->like($postId, $userId)) {
    header("HTTP/1.1 200 OK");
    echo json_encode(["message" => "Post liked successfully"]);
} else {
    header("HTTP/1.1 400 Bad Request");
    echo json_encode(["error" => "Post is already liked"]);
}

exit;
?>