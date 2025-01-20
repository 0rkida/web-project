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
    public function saveMessage($userId, $message): bool
    {
        $query = "INSERT INTO messages (sender_id, receiver_id,message, created_at) VALUES (?, ?,?, NOW())";

        // Prepare the SQL statement
        $stmt = $this->dbConnection->prepare($query);

        // Bind parameters
        $stmt->bind_param('is', $userId, $message);

        // Execute the query
        if ($stmt->execute()) {
            return true;
        }

        return false;
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
    public function searchUsers($outgoingId, $searchTerm): string
    {
        $searchTerm = $this->dbConnection->real_escape_string($searchTerm);
        $query = "SELECT * FROM users WHERE NOT id = ? AND (full_name LIKE ?)";

        $stmt = $this->dbConnection->prepare($query);
        $searchTerm = "%{$searchTerm}%";
        $stmt->bind_param('iss', $outgoingId, $searchTerm, $searchTerm);
        $stmt->execute();

        $result = $stmt->get_result();
        $output = "";

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                // Generate output using the user data (adapt as needed)
                $output .= "<div>{$row['full_name']}</div>";
            }
        } else {
            $output .= 'No user found related to your search term';
        }

        return $output;
    }
}
