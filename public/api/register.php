<?php
use Src\User\Exceptions\UsernameAlreadyExistsException;
use Src\User\Services\UserService;
use Src\User\Validators\UserValidator;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("HTTP/1.1 405 Method Not Allowed");
    echo json_encode(["error" => "Only POST requests are allowed."]);
    exit;
}

$username = $_POST['username'] ?? "";
$password = $_POST['password'] ?? "";

// Sanitize input.
$username = htmlspecialchars(trim($username), ENT_QUOTES, 'UTF-8');
$password = htmlspecialchars(trim($password), ENT_QUOTES, 'UTF-8');

// Validate input.
$validator = new UserValidator();
$usernameErrors = $validator->validateUsername($username);
$passwordErrors = $validator->validatePassword($password);
$error = [
    "username" => $usernameErrors,
    "password" => $passwordErrors
];

if (!empty($usernameErrors) || !empty($passwordErrors)) {
    header("HTTP/1.1 400 Bad Request");
    echo json_encode($error);
    exit;
}

// Create user.
try {
    $userService = new UserService();
    $user = $userService->createUser($username, $password);
    header("HTTP/1.1 200 OK");
    echo json_encode($user);
} catch (UsernameAlreadyExistsException $e) {
    header("HTTP/1.1 400 Bad Request");
    echo json_encode(["error" => $e->getMessage()]);
} catch (\Exception $e) {
    header("HTTP/1.1 500 Internal Server Error");
    echo json_encode(["error" => $e->getMessage()]);
}
?>