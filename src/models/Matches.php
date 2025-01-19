<?php
namespace App\models;
use PDO;

class MatchesModel
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    /**
     * Get all matches for a specific user
     * @param int $userId
     * @return array
     */
    public function getMatchesForUser($userId)
    {
        $query = "SELECT u.id, u.full_name
                  FROM likes l1
                  INNER JOIN likes l2 ON l1.liked_user_id = l2.liked_by_user_id
                  INNER JOIN users u ON l1.liked_user_id = u.id
                  WHERE l1.liked_by_user_id = ? AND l2.liked_user_id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$userId, $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get all notifications (likes without match confirmation) for a specific user
     * @param int $userId
     * @return array
     */
    public function getNotificationsForUser($userId)
    {
        $query = "SELECT u.id, u.full_name
                  FROM likes l
                  INNER JOIN users u ON l.liked_by_user_id = u.id
                  WHERE l.liked_user_id = ? 
                  AND NOT EXISTS (
                      SELECT 1 FROM likes l2 
                      WHERE l2.liked_by_user_id = ? 
                      AND l2.liked_user_id = l.liked_by_user_id
                  )";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$userId, $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Add a like from one user to another
     * @param int $likedByUserId
     * @param int $likedUserId
     * @return bool
     */
    public function addLike($likedByUserId, $likedUserId)
    {
        $query = "INSERT INTO likes (liked_by_user_id, liked_user_id) VALUES (?, ?)";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$likedByUserId, $likedUserId]);
    }

    /**
     * Check if there's a mutual like between two users
     * @param int $userId
     * @param int $otherUserId
     * @return bool
     */
    public function checkForMutualLike($userId, $otherUserId)
    {
        $query = "SELECT COUNT(*) 
                  FROM likes l1
                  INNER JOIN likes l2 ON l1.liked_user_id = l2.liked_by_user_id
                  WHERE l1.liked_by_user_id = ? AND l1.liked_user_id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$userId, $otherUserId]);
        return $stmt->fetchColumn() > 0;
    }

    /**
     * Create a match (no changes to the database, only used for confirmation)
     * @param int $userId
     * @param int $otherUserId
     * @return array
     */
    public function createMatch($userId, $otherUserId)
    {
        // Since we do not store matches in a separate table, this function simply fetches the match details.
        $query = "SELECT u1.id AS user1_id, u1.full_name AS user1_name, 
                         u2.id AS user2_id, u2.full_name AS user2_name
                  FROM users u1
                  INNER JOIN users u2 ON u1.id = ? AND u2.id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$userId, $otherUserId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
