<?php
namespace Src\Auth\Api;

use Src\Auth\Services\AuthService;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("HTTP/1.1 405 Method Not Allowed");
    echo json_encode(["error" => "Only POST requests are allowed"]);
    exit;
}

$username = $_POST["username"] ?? "";
$password = $_POST["password"] ?? "";

// Sanitize input.
$username = htmlspecialchars(trim($username), ENT_QUOTES, 'UTF-8');
$password = htmlspecialchars(trim($password), ENT_QUOTES, 'UTF-8');

$authService = new AuthService();
$user = $authService->login($username, $password);

if (!$user) {
    header("HTTP/1.1 401 Unauthorized");
    echo json_encode(["error" => "Invalid credentials"]);
    exit;
}

header("HTTP/1.1 200 OK");
echo json_encode($user);
exit;
?>