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
    public function saveMessage($senderId, $receiverId, $message) {
        $stmt = $this->db->prepare("
        INSERT INTO messages (sender_id, receiver_id, message) 
        VALUES (:sender_id, :receiver_id, :message)
    ");

        return $stmt->execute([
            ':sender_id' => $senderId,
            ':receiver_id' => $receiverId,
            ':message' => $message,
        ]);
    }


    // Retrieve all messages from the database
    public function getMessages(): array
    {
        $query = "SELECT m.sender_id, m.receiver_id, u.username 
                  FROM messages m 
                  JOIN users u ON m.id = u.id 
                  ORDER BY m.created_at ";

        $stmt = $this->dbConnection->prepare($query);
        $stmt->execute();

        // Get the result and return it as an associative array
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Search users based on a search term
    public function searchUsers($outgoingId, $searchTerm) {
        $stmt = $this->db->prepare("
        SELECT u.id, u.username, 
               IF(u.id IN (SELECT messages.receiver_id FROM messages WHERE sender_id = ?), 'online', 'offline') AS status
        FROM users u
        WHERE u.username LIKE ?
    ");
        $stmt->execute([$outgoingId, "%$searchTerm%"]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}
