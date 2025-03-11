<?php
use Src\Auth\Services\AuthService;
use Src\Comment\Validators\CommentValidator;
use Src\Comment\Services\CommentService;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("HTTP/1.1 405 Method Not Allowed");
    echo json_encode(["error" => "Only POST requests are allowed"]);
    exit;
}

if (!isset($_GET["post_id"]) || !is_numeric($_GET["post_id"])) {
    header("HTTP/1.1 400 Bad Request");
    echo json_encode(["error" => "Post ID is required and must be a valid number"]);
    exit;
}

$postId = (int) $_GET["post_id"];

$authService = new AuthService();
if (!$authService->isAuthenticated()) {
    header("HTTP/1.1 401 Unauthorized");
    echo json_encode(["error" => "Authentication required"]);
    exit;
}
$userId = $authService->getAuthenticatedUser()->id;

$content = trim($_POST["content"] ?? "");
$commentValidator = new CommentValidator();
$contentErrors = $commentValidator->validateContent($content);

if ($contentErrors) {
    header("HTTP/1.1 400 Bad Request");
    echo json_encode($contentErrors);
    exit;
}

$commentService = new CommentService();
$comment = $commentService->createComment($postId, $userId, $content);
if ($comment) {
    header("HTTP/1.1 200 OK");
    echo json_encode($comment);
} else {
    header("HTTP/1.1 500 Internal Server Error");
    echo json_encode(["error" => "Failed to create comment"]);
}

exit;
?>