<?php


class MessageModel
{
    private $db;

    public function __construct($dbConnection)
    {
        $this->db = $dbConnection;
    }

    // Save message to the database
    public function saveMessage($userId, $message): bool
    {
        $query = "INSERT INTO messages (user_id, message, created_at) VALUES (:user_id, :message, NOW())";

        // Prepare the SQL statement
        $stmt = $this->db->prepare($query);

        // Bind parameters
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':message', $message, PDO::PARAM_STR);

        // Execute the query
        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    // Retrieve all messages from the database
    public function getMessages(): array
    {
        $query = "SELECT m.message, m.created_at, u.username 
                  FROM messages m 
                  JOIN users u ON m.user_id = u.id 
                  ORDER BY m.created_at ASC";

        $stmt = $this->db->prepare($query);
        $stmt->execute();

        // Fetch all results
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
