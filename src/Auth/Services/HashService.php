<?php
namespace Src\Auth\Services;

class HashService
{
    public function getHashAlgo(): string
    {
        return PASSWORD_BCRYPT;
    }

    public function hashPassword(string $password): string
    {
        return password_hash($password, self::getHashAlgo());
    }

    public function verifyPassword(string $password, string $passwordHash): bool
    {
        return password_verify($password, $passwordHash);
    }
}
?>