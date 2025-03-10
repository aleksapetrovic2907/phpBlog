<?php
namespace Src\Post\Models;

class Post
{
    public int $id;
    public int $userId;
    public string $title;
    public string $content;
    public string $createdAt;

    public function __construct($id, $userId, $title, $content, $createdAt)
    {
        $this->id = $id;
        $this->userId = $userId;
        $this->title = $title;
        $this->content = $content;
        $this->createdAt = $createdAt;
    }
}
?>