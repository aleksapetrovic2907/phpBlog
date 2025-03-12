<?php
namespace Src\Post\Api;

use Src\Auth\Services\AuthService;
use Src\Post\Services\PostService;

if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
    header("HTTP/1.1 405 Method Not Allowed");
    echo json_encode(["error" => "Only DELETE requests are allowed"]);
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

if (!$post) {
    header("HTTP/1.1 404 Not Found");
    echo json_encode(["error" => "Post not found"]);
    exit;
}

$authService = new AuthService();
$user = $authService->getAuthenticatedUser();
if ($post->userId !== $user->id) {
    header("HTTP/1.1 403 Forbidden");
    echo json_encode(["error" => "You do not have permission to delete this post"]);
    exit;
}

try {
    $postService->deletePostById($id);
    header("HTTP/1.1 200 OK");
    echo json_encode(["message" => "Post deleted successfully"]);
} catch (\Exception $e) {
    header("HTTP/1.1 500 Internal Server Error");
    echo json_encode(["error" => "Failed to delete post"]);
}

exit;
?>