<?php
use Src\Auth\Services\AuthService;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("HTTP/1.1 405 Method Not Allowed");
    echo json_encode(["error" => "Only POST requests are allowed"]);
    exit;
}

$authService = new AuthService();
if ($authService->isAuthenticated()) {
    $authService->logout();
    header("HTTP/1.1 200 OK");
    echo json_encode([]);
    exit;
} else {
    header("HTTP/1.1 400 Bad Request");
    echo json_encode(["error" => "User is not logged in"]);
    exit;
}
?>