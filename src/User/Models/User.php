<?php
namespace Src\User\Models;

class User
{
    public int $id;
    public string $username;
    public string $passwordHash;
    public string $createdAt;

    public function __construct($id, $username, $passwordHash, $createdAt)
    {
        $this->id = $id;
        $this->username = $username;
        $this->passwordHash = $passwordHash;
        $this->createdAt = $createdAt;
    }
}
?>