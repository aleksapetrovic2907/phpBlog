<?php
namespace Src\Post\Api;

use Src\Post\Services\PostService;

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    header("HTTP/1.1 405 Method Not Allowed");
    echo json_encode(["error" => "Only GET requests are allowed"]);
    exit;
}

if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {
    header("HTTP/1.1 400 Bad Request");
    echo json_encode(["error" => "Post ID is required and must be a valid number"]);
    exit;
}

$id = (int) $_GET["id"];
$postService = new PostService();
$post = $postService->getPostById($id);

if ($post) {
    header("HTTP/1.1 200 OK");
    echo json_encode($post);
} else {
    header("HTTP/1.1 404 Not Found");
    echo json_encode(["error" => "Post not found"]);
}

exit;
?>