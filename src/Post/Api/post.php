<?php
use Src\Post\Services\PostService;
use Src\Auth\Services\AuthService;

$authService = new AuthService();
if ($_SERVER['REQUEST_METHOD'] === 'PUT' || $_SERVER['REQUEST_METHOD'] === 'PATCH' || $_SERVER['REQUEST_METHOD'] === 'DELETE') {
    if (!$authService->isAuthenticated()) {
        header("HTTP/1.1 401 Unauthorized");
        echo json_encode(["error" => "Authentication required"]);
        exit;
    }
}

// Get post.
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (!isset($_GET["id"])) {
        header("HTTP/1.1 400 Bad Request");
        echo json_encode(["error" => "Post ID is required"]);
        exit;
    }

    $id = $_GET["id"];
    $postService = new PostService();
    $post = $postService->getPostById($id);

    if ($post) {
        header("HTTP/1.1 200 OK");
        echo json_encode($post);
    } else {
        header("HTTP/1.1 404 Not Found");
        echo json_encode(["error" => "Post not found"]);
    }
}

// Update post.
elseif ($_SERVER['REQUEST_METHOD'] === 'PUT' || $_SERVER['REQUEST_METHOD'] === 'PATCH') {
    if (!isset($_GET["id"])) {
        header("HTTP/1.1 400 Bad Request");
        echo json_encode(["error" => "Post ID is required"]);
        exit;
    }

    $id = $_GET["id"];
    $post = $postService->getPostById($id);

    if (!$post) {
        header("HTTP/1.1 404 Not Found");
        echo json_encode(["error" => "Post not found"]);
        exit;
    }

    $user = $authService->getAuthenticatedUser();
    if ($post->userId !== $user->id) {
        header("HTTP/1.1 403 Forbidden");
        echo json_encode(["error" => "You do not have permission to edit this post"]);
        exit;
    }


    $data = json_decode(file_get_contents("php://input"), true);
    $title = htmlspecialchars(trim($data['title']));
    $content = htmlspecialchars(trim($data['content']));

    $postValidator = new PostValidator();
    $titleErrors = $postValidator->validateTitle($title);
    $contentErrors = $postValidator->validateContent($content);

    if ($titleErrors || $contentErrors) {
        header("HTTP/1.1 400 Bad Request");
        echo json_encode([
            "title" => $titleErrors,
            "content" => $contentErrors,
        ]);
        exit;
    }

    $postService = new PostService();
    try {
        $postService->updatePost($id, $title, $content);
        header("HTTP/1.1 200 OK");
        echo json_encode(["message" => "Post updated successfully"]);
    } catch (\Exception $e) {
        header("HTTP/1.1 500 Internal Server Error");
        echo json_encode(["error" => "Failed to update post"]);
    }
}

// Delete post.
elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    if (!isset($_GET["id"])) {
        header("HTTP/1.1 400 Bad Request");
        echo json_encode(["error" => "Post ID is required"]);
        exit;
    }

    $id = $_GET["id"];
    $postService = new PostService();
    $post = $postService->getPostById($id);

    if (!$post) {
        header("HTTP/1.1 404 Not Found");
        echo json_encode(["error" => "Post not found"]);
        exit;
    }

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
}

// Non-allowed methods.
else {
    header("HTTP/1.1 405 Method Not Allowed");
    echo json_encode(["error" => "Method not allowed"]);
}

exit;
?>