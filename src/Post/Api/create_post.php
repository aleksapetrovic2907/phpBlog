<?php
use Src\Auth\Services\AuthService;
use Src\Post\Services\PostService;
use Src\Post\Validators\PostValidator;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("HTTP/1.1 405 Method Not Allowed");
    echo json_encode(["error" => "Only POST requests are allowed"]);
    exit;
}

$authService = new AuthService();
if (!$authService->isAuthenticated()) {
    header("HTTP/1.1 403 Forbidden");
    echo json_encode(["error" => "You do not have permission to create a post"]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);
$title = trim($data['title'] ?? "");
$content = trim($data['content'] ?? "");

$postValidator = new PostValidator();
$titleErrors = $postValidator->validateTitle($title);
$contentErrors = $postValidator->validateContent($content);

if ($titleErrors || $contentErrors) {
    header("HTTP/1.1 400 Bad Request");
    echo json_encode([
        "title" => $titleErrors,
        "content" => $contentErrors
    ]);
    exit;
}

try {
    $postService = new PostService();
    $userId = $authService->getAuthenticatedUser()->id;
    $post = $postService->createPost($userId, $title, $content);

    header("HTTP/1.1 200 OK");
    echo json_encode($post);
} catch (\Exception $e) {
    header("HTTP/1.1 500 Internal Server Error");
    echo json_encode(["error" => "Failed to create post"]);
}

exit;
?>