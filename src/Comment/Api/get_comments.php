<?php
use Src\Comment\Services\CommentService;

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

$postId = (int) $_GET["id"];
$commentService = new CommentService();
$comments = $commentService->getCommentsByPostId($postId);
header("HTTP/1.1 200 OK");
echo json_encode($comments);
exit;
?>