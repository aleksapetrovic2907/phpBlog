<?php
namespace Src\Comment\Api;

use Src\Auth\Services\AuthService;
use Src\Comment\Services\CommentService;

if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
    header("HTTP/1.1 405 Method Not Allowed");
    echo json_encode(["error" => "Only DELETE requests are allowed"]);
    exit;
}

if (!isset($_GET["comment_id"]) || !is_numeric($_GET["comment_id"])) {
    header("HTTP/1.1 400 Bad Request");
    echo json_encode(["error" => "Comment ID is required and must be a valid number"]);
    exit;
}

$commentId = (int) $_GET["comment_id"];
$commentService = new CommentService();
$comment = $commentService->getCommentById($commentId);

if (!$comment) {
    header("HTTP/1.1 404 Not Found");
    echo json_encode(["error" => "Comment not found"]);
    exit;
}

$authService = new AuthService();
if (!$authService->isAuthenticated()) {
    header("HTTP/1.1 401 Unauthorized");
    echo json_encode(["error" => "Authentication required"]);
    exit;
}

if ($authService->getAuthenticatedUser()->id !== $comment->userId) {
    header("HTTP/1.1 403 Forbidden");
    echo json_encode(["error" => "You do not have permission to delete this comment"]);
    exit;
}

if ($commentService->deleteCommentById($commentId)) {
    header("HTTP/1.1 204 No Content");
    echo json_encode(["message" => "Comment is successfully deleted"]);
} else {
    header("HTTP/1.1 500 Internal Server Error");
    echo json_encode(["error" => "Comment deletion failed"]);
}

exit;
?>