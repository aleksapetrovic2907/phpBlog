<?php
namespace Src\Post\Models;

class Post
{
    public int $id;
    public string $userId;
    public string $content;
    public string $createdAt;

    public function __construct($id, $userId, $content, $createdAt)
    {
        $this->id = $id;
        $this->userId = $userId;
        $this->content = $content;
        $this->createdAt = $createdAt;
    }
}
?>