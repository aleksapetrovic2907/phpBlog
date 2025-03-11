<?php
namespace Src\User\DTOs;

class GetUserDTO
{
    public int $id;
    public string $username;
    public string $createdAt;

    public function __construct(int $id, string $username, string $createdAt)
    {
        $this->id = $id;
        $this->username = $username;
        $this->createdAt = $createdAt;
    }
}