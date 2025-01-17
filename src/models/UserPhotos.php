<?php
namespace App\models;

use Exception;

class UserPhotos
{
    private $db;

    public function __construct($dbConnection)
    {
        $this->db = $dbConnection;
    }

    public function savePicture($userId, $picturePath)
    {
        $query = "INSERT INTO user_pictures (user_id, picture_path) VALUES (?, ?)";
        $stmt = $this->db->prepare($query);

        if (!$stmt) {
            throw new Exception("Database error: " . $this->db->error);
        }

        $stmt->bind_param("is", $userId, $picturePath);

        if ($stmt->execute()) {
            return true;
        } else {
            throw new Exception("Error saving picture: " . $stmt->error);
        }
    }

    public function getPhotosByUserId($userId)
    {
        $query = "SELECT picture_path FROM user_pictures WHERE user_id = ?";
        $stmt = $this->db->prepare($query);

        if (!$stmt) {
            throw new Exception("Database error: " . $this->db->error);
        }

        $stmt->bind_param("i", $userId);
        $stmt->execute();

        $result = $stmt->get_result();
        $pictures = $result->fetch_all(MYSQLI_ASSOC);

        return $pictures;
    }

}
