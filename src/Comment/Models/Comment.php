<?php
namespace Src\Comment\Models;

class Comment
{
    public int $id;
    public int $postId;
    public int $userId;
    public string $content;
    public string $createdAt;

    public function __construct(int $id, int $postId, int $userId, string $content, string $createdAt)
    {
        $this->id = $id;
        $this->postId = $postId;
        $this->userId = $userId;
        $this->content = $content;
        $this->createdAt = $createdAt;
    }
}
?>