<?php
namespace App\models;

class Message
{
    private $dbConnection;

    public function __construct($dbConnection)
    {
        $this->dbConnection = $dbConnection;
    }

    // Save message to the database
    public function saveMessage($senderId, $receiverId, $message)
    {
        $stmt = $this->dbConnection->prepare("
            INSERT INTO messages (sender_id, receiver_id, message) 
            VALUES (?, ?, ?)
        ");
        $stmt->bind_param("iis", $senderId, $receiverId, $message); // Bind parameters
        return $stmt->execute();
    }

    // Retrieve all messages from the database
    public function getMessages(): array
    {
        $query = "SELECT m.sender_id, m.receiver_id, u.username 
                  FROM messages m 
                  JOIN users u ON m.sender_id = u.id 
                  ORDER BY m.created_at";

        $stmt = $this->dbConnection->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result(); // Get result for MySQLi
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Search users based on a search term
    public function searchUsers($outgoingId, $searchTerm)
    {
        $stmt = $this->dbConnection->prepare("
            SELECT u.id, u.username, 
                   IF(u.id IN (SELECT receiver_id FROM messages WHERE sender_id = ?), 'online', 'offline') AS status
            FROM users u
            WHERE u.username LIKE ?
        ");
        $searchTerm = "%$searchTerm%"; // Add wildcards for LIKE
        $stmt->bind_param("is", $outgoingId, $searchTerm); // Bind parameters
        $stmt->execute();
        $result = $stmt->get_result(); // Get result for MySQLi
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
