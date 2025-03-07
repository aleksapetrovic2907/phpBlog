<?php
namespace Src\Services;

use Src\Models\User;
use Src\Services\UserService;
use Src\Services\HashService;

class AuthService
{
    public function __construct()
    {
        session_start();
    }

    public function login(string $username, string $password): ?User
    {
        $hashService = new HashService();
        $userService = new UserService();

        $user = $userService->getUserByUsername($username);
        if ($user && $hashService->verifyPassword($password, $user->passwordHash)) {
            $_SESSION['user_id'] = $user->id;
            session_regenerate_id(true);
            return $user;
        }

        return null;
    }

    public function logout()
    {
        session_unset();
        session_destroy();
    }

    public function isAuthenticated(): bool
    {
        return isset($_SESSION['user_id']);
    }

    public function getAuthenticatedUser(): ?User
    {
        if ($this->isAuthenticated()) {
            $userService = new UserService();
            return $userService->getUserById($_SESSION['user_id']);
        }

        return null;
    }
}
?>