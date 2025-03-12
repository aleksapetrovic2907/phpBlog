<?php
require_once __DIR__ . '/../vendor/autoload.php';

$requestUri = trim($_SERVER['REQUEST_URI']);
$requestMethod = $_SERVER['REQUEST_METHOD'];

$routes = [
    'GET' => [
        'api/user/{id}' => 'src/User/Api/users.php',
        'api/post/{id}' => 'src/Post/Api/get_post.php',
        'api/comment/{postId}' => 'src/Comment/Api/get_comments.php',
        'api/like/{postId} likers' => 'src/Like/Api/get_likers.php',
        'api/like/{postId} likesCount' => 'src/Like/Api/get_likes_count.php',
    ],
    'POST' => [
        'api/user' => 'src/User/Api/register.php',
        'api/auth/login' => 'src/Auth/Api/login.php',
        'api/auth/logout' => 'src/Auth/Api/logout.php',
        'api/post' => 'src/Post/Api/create_post.php',
        'api/comment/{postId}' => 'src/Comment/Api/create_comment.php',
        'api/like/{postId}' => 'src/Comment/Api/like_post.php',
    ],
    'DELETE' => [
        'api/post/{id}' => 'src/Post/Api/delete_post.php',
        'api/comment/{id}' => 'src/Comment/Api/delete_comment.php',
        'api/like/{postId}' => 'src/Like/Api/unlike_post.php',
    ],
    'PATCH' => [
        'api/post/{id}' => 'src/Post/Api/update_post.php',
    ],
];

$matchedRoute = null;
$params = [];

foreach ($routes[$requestMethod] ?? [] as $route => $filePath) {
    $pattern = '/^' . preg_replace('/\{([a-zA-Z0-9_]+)\}/', '(?P<$1>[a-zA-Z0-9_]+)', $route) . '$/';
    if (preg_match($pattern, $requestUri, $matches)) {
        $matchedRoute = $filePath;
        $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
        break;
    }
}

if ($matchedRoute) {
    extract($params);
    include $matchedRoute;
} else {
    header("HTTP/1.1 404 Not Found");
    echo json_encode(["error" => "Endpoint not found"]);
}
?>