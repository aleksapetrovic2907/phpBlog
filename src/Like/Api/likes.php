<?php

use Src\Like\Services\LikeService;
use Src\Auth\Services\AuthService;

$likeService = new LikeService();
$authService = new AuthService();

if ($_SERVER['REQUEST_METHOD'] === 'POST' || $_SERVER['REQUEST_METHOD'] === 'DELETE') {
    if (!$authService->isAuthenticated()) {
        header("HTTP/1.1 401 Unauthorized");
        echo json_encode(["error" => "Authentication required"]);
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {
        header("HTTP/1.1 400 Bad Request");
        echo json_encode(["error" => "Post ID is required and must be a valid number"]);
        exit;
    }

    $postId = (int) $_GET["id"];

    // If we want users who liked the post
    if (isset($_GET["action"]) && $_GET["action"] === "users") {
        $users = $likeService->getLikersByPostId($postId);
        header("HTTP/1.1 200 OK");
        echo json_encode($users);
    }

    // If we want the like count on the post
    if (isset($_GET["action"]) && $_GET["action"] === "count") {
        $count = $likeService->getLikeCount($postId);
        header("HTTP/1.1 200 OK");
        echo json_encode($count);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {
        header("HTTP/1.1 400 Bad Request");
        echo json_encode(["error" => "Post ID is required and must be a valid number"]);
        exit;
    }

    $postId = (int) $_GET["id"];
    $userId = $authService->getAuthenticatedUser()->id;

    if ($likeService->like($postId, $userId)) {
        header("HTTP/1.1 200 OK");
        echo json_encode(["message" => "Post liked successfully"]);
    } else {
        header("HTTP/1.1 400 Bad Request");
        echo json_encode(["error" => "Post is already liked"]);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {
        header("HTTP/1.1 400 Bad Request");
        echo json_encode(["error" => "Post ID is required and must be a valid number"]);
        exit;
    }

    $postId = (int) $_GET["id"];
    $userId = $authService->getAuthenticatedUser()->id;

    if ($likeService->unlike($postId, $userId)) {
        header("HTTP/1.1 200 OK");
        echo json_encode(["message" => "Post successfully unliked"]);
    } else {
        header("HTTP/1.1 400 Bad Request");
        echo json_encode(["error" => "Post isn't previously liked"]);
    }
} else {
    header("HTTP/1.1 405 Method Not Allowed");
    echo json_encode(["error" => "Method not allowed"]);
}

exit;
?>