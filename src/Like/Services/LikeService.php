<?php
namespace Src\Like\Services;

use Config\Database;
use Src\User\Services\UserService;

class LikeService
{
    /**
     * Like a post.
     * @param int $postId Id of the liked post.
     * @param int $userId Id of the user who liked the post.
     * @return bool True if liked successfully, false if already liked.
     */
    public function like(int $postId, int $userId): bool
    {
        if ($this->hasUserLikedPost($postId, $userId)) {
            return false;
        }

        $connection = Database::getConnection();
        $likePostQuery = "INSERT INTO likes (post_id, user_id) VALUES(?, ?);";
        $stmt = $connection->prepare($likePostQuery);
        $stmt->bind_param("ii", $postId, $userId);
        $stmt->execute();
        return true;
    }

    /**
     * Unlikes a post.
     * @param int $postId Id of the unliked post.
     * @param int $userId Id of the user who unliked the post.
     * @return bool True if unliked successfully, false if wasn't previously liked.
     */
    public function unlike(int $postId, int $userId): bool
    {
        if (!$this->hasUserLikedPost($postId, $userId)) {
            return false;
        }

        $connection = Database::getConnection();
        $unlikePostQuery = "DELETE from likes WHERE post_id = ? AND user_id = ?;";
        $stmt = $connection->prepare($unlikePostQuery);
        $stmt->bind_param("ii", $postId, $userId);
        $stmt->execute();
        return true;
    }

    /**
     * Checks if a user liked a post.
     * @param int $postId Id of the post to be checked.
     * @param int $userId Id of the user to be checked.
     * @return bool True if the user has liked post, false otherwise.
     */
    public function hasUserLikedPost(int $postId, int $userId): bool
    {
        $connection = Database::getConnection();

        $hasLikedQuery = "SELECT 1 FROM likes WHERE post_id = ? AND user_id = ? LIMIT 1;";
        $stmt = $connection->prepare($hasLikedQuery);
        $stmt->bind_param("ii", $postId, $userId);
        $stmt->execute();
        $stmt->store_result();

        return $stmt->num_rows > 0;
    }

    /**
     * Returns users who liked a post.
     * @param int $postId Id of the post.
     * @return array<\Src\User\Models\User>
     */
    public function getLikersByPostId(int $postId): array
    {
        $connection = Database::getConnection();

        $getLikersQuery = "SELECT user_id FROM likes WHERE post_id = ?;";
        $stmt = $connection->prepare($getLikersQuery);
        $stmt->bind_param("i", $postId);
        $stmt->execute();
        $result = $stmt->get_result();

        $userService = new UserService();
        $users = [];
        while ($row = $result->fetch_assoc()) {
            $users[] = $userService->getUserById($row["user_id"]);
        }

        return $users;
    }

    /**
     * Gets the total number of likes on a post.
     * @param int $postId Id of the post.
     * @return int Number of likes.
     */
    public function getLikeCount(int $postId): int
    {
        $connection = Database::getConnection();
        $getLikesQuery = "SELECT COUNT(*) as like_count FROM likes WHERE post_id = ?;";
        $stmt = $connection->prepare($getLikesQuery);
        $stmt->bind_param("i", $postId);
        $stmt->execute();
        $stmt->bind_result($likeCount);
        $stmt->fetch();

        return $likeCount;
    }
}
?>