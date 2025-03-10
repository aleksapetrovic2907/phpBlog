<?php
namespace Src\User\Services;

use Config\Database;
use Src\Auth\Services\HashService;
use Src\User\Models\User;
use Src\User\Exceptions\UsernameAlreadyExistsException;

class UserService
{
    public function createUser(string $username, string $password): ?User
    {
        $connection = Database::getConnection();

        if ($this->getUserByUsername($username) !== null) {
            throw new UsernameAlreadyExistsException();
        }

        // Insert new user.
        $hashService = new HashService();
        $passwordHash = $hashService->hashPassword($password);
        $insertNewUserQuery = "INSERT INTO users (username, password_hash) VALUES(?, ?)";
        $stmt = $connection->prepare($insertNewUserQuery);
        $stmt->bind_param("ss", $username, $passwordHash);

        if (!$stmt->execute()) {
            throw new \Exception("User creation failed.");
        }

        return $this->getUserById($connection->insert_id);
    }

    public function getUserById(int $id): ?User
    {
        $connection = Database::getConnection();

        $findUserQuery = "SELECT * FROM users WHERE id = ?;";
        $stmt = $connection->prepare($findUserQuery);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $userData = $result->fetch_assoc();
            return new User($userData["id"], $userData["username"], $userData["password_hash"], $userData["created_at"]);
        }

        return null;
    }

    public function getUserByUsername(string $username): ?User
    {
        $connection = Database::getConnection();

        $findUserQuery = "SELECT * FROM users WHERE username = ?;";
        $stmt = $connection->prepare($findUserQuery);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $userData = $result->fetch_assoc();
            return new User($userData["id"], $userData["username"], $userData["password_hash"], $userData["created_at"]);
        }

        return null;
    }

    public function deleteUserById(int $userId): void
    {
        $connection = Database::getConnection();

        $deleteUserQuery = "DELETE FROM users WHERE id = ?;";
        $stmt = $connection->prepare($deleteUserQuery);
        $stmt->bind_param("i", $userId);
        if (!$stmt->execute()) {
            throw new \Exception("User deletion failed.");
        }
    }

    public function updateUser(int $userId, string $username, string $password)
    {
        $connection = Database::getConnection();

        $hashService = new HashService();
        $passwordHash = $hashService->hashPassword($password);
        $updateUserQuery = "UPDATE users SET username = ?, password_hash = ? WHERE id = ?;";
        $stmt = $connection->prepare($updateUserQuery);
        $stmt->bind_param("ssi", $username, $passwordHash, $userId);
        if (!$stmt->execute()) {
            throw new \Exception("User update failed.");
        }
    }
}
?>