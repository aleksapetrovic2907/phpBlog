<?php
namespace Src\Post\Services;

use Config\Database;
use Src\Post\Models\Post;

class PostService
{
    public function createPost(int $userId, string $title, string $content): ?Post
    {
        $connection = Database::getConnection();

        $createPostQuery = "INSERT INTO posts (user_id, title, content) VALUES (?, ?, ?);";
        $stmt = $connection->prepare($createPostQuery);
        $stmt->bind_param("iss", $userId, $title, $content);

        if (!$stmt->execute()) {
            throw new \Exception("Post creation failed");
        }

        return $this->getPostById($connection->insert_id);
    }

    public function getPosts(): array
    {
        $connection = Database::getConnection();

        $getPostQuery = "SELECT * FROM posts;";
        $stmt = $connection->prepare($getPostQuery);
        $stmt->execute();
        $result = $stmt->get_result();

        $posts = [];
        while ($row = $result->fetch_assoc()) {
            $posts[] = new Post($row["id"], $row["user_id"], $row["title"], $row["content"], $row["created_at"]);
        }

        return $posts;
    }

    public function getPostById(int $id): ?Post
    {
        $connection = Database::getConnection();

        $getPostQuery = "SELECT * FROM posts WHERE id = ?;";
        $stmt = $connection->prepare($getPostQuery);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $postData = $result->fetch_assoc();
            return new Post($postData["id"], $postData["user_id"], $postData["title"], $postData["content"], $postData["created_at"]);
        }

        return null;
    }

    public function deletePostById(int $id): void
    {
        $connection = Database::getConnection();

        $deletePostQuery = "DELETE FROM posts WHERE id = ?;";
        $stmt = $connection->prepare($deletePostQuery);
        $stmt->bind_param("i", $id);

        if (!$stmt->execute()) {
            throw new \Exception("Post deletion failed");
        }
    }

    public function updatePost(int $id, string $title, string $content)
    {
        $connection = Database::getConnection();

        $updatePostQuery = "UPDATE posts SET title = ?, content = ? WHERE id = ?;";
        $stmt = $connection->prepare($updatePostQuery);
        $stmt->bind_param("ssi", $title, $content, $id);
        if (!$stmt->execute()) {
            throw new \Exception("Post update failed.");
        }
    }
}
?>