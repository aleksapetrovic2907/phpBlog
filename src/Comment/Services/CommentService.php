<?php
namespace Src\Comment\Services;

use Config\Database;
use Src\Comment\Models\Comment;

class CommentService
{
    /**
     * Creates a new comment on a post.
     * @param int $postId Id of the post to comment on.
     * @param int $userId Id of the user commenting.
     * @param string $content Content of the comment.
     * @return Comment|null The created Comment object on success, null on failure.
     */
    public function createComment(int $postId, int $userId, string $content): ?Comment
    {
        $connection = Database::getConnection();

        $createCommentQuery = "INSERT INTO comments (post_id, user_id, content) VALUES (?, ?, ?);";
        $stmt = $connection->prepare($createCommentQuery);
        $stmt->bind_param("iis", $postId, $userId, $content);

        try {
            $stmt->execute();
            $createdAt = date("Y-m-d H:i:s");
            return new Comment($connection->insert_id, $postId, $userId, $content, $createdAt);
        } catch (\mysqli_sql_exception $e) {
            return null;
        }
    }

    /**
     * Get a comment by its id.
     * @param int $id The id of the comment.
     * @return Comment|null The comment object if found, or null if not found.
     */
    public function getCommentById(int $id): ?Comment
    {
        $connection = Database::getConnection();
        $getCommentQuery = "SELECT * FROM comments WHERE id = ?;";
        $stmt = $connection->prepare($getCommentQuery);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $row = $result->fetch_assoc()) {
            return new Comment($row["id"], $row["post_id"], $row["user_id"], $row["content"], $row["created_at"]);
        }

        return null;
    }

    /**
     * Get comments under a post.
     * @param int $postId The id of the post.
     * @return Comment[] Array of comments.
     */
    public function getCommentsByPostId(int $postId): array
    {
        $comments = [];

        $connection = Database::getConnection();
        $getCommentsQuery = "SELECT * FROM comments WHERE post_id = ?;";
        $stmt = $connection->prepare($getCommentsQuery);
        $stmt->bind_param("i", $postId);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $comments[] = new Comment($row["id"], $row["post_id"], $row["user_id"], $row["content"], $row["created_at"]);
        }

        return $comments;
    }

    /**
     * Deletes a comment.
     * @param int $id The id of the comment to be deleted.
     * @return bool True if successfully deleted, otherwise false.
     */
    public function deleteCommentById(int $id): bool
    {
        $connection = Database::getConnection();

        $deleteCommentQuery = "DELETE FROM comments WHERE id = ?;";
        $stmt = $connection->prepare($deleteCommentQuery);
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            return $stmt->affected_rows > 0;
        }

        return false;
    }
}
?>