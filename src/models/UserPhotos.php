<?php
namespace App\models;

use Exception;

class UserPhotos
{
    private $dbConnection;

    public function __construct($dbConnection)
    {
        $this->dbConnection = $dbConnection;
    }

    public function savePicture($userId, $picturePath)
    {
        $query = "INSERT INTO user_pictures (user_id, picture_path) VALUES (?, ?)";
        $stmt = $this->dbConnection->prepare($query);

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
        $query = "SELECT picture_path  FROM user_pictures WHERE user_id = ?";
        $stmt = $this->dbConnection->prepare($query);

        if (!$stmt) {
            throw new Exception("Database error: " . $this->dbConnection->error);
        }

        $stmt->bind_param("i", $userId);
        $stmt->execute();

        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);

//        $pictures = $result->fetch_all(MYSQLI_ASSOC);
//
//        return $pictures;

    }


    public function getAllUsersPictures()
    {
        $sql = "SELECT users.username, user_pictures.picture_path 
                FROM users 
                JOIN user_pictures ON users.id = user_pictures.user_id";
        $result = $this->dbConnection->query($sql);

        $users = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $users[] = $row;
            }
        }
        return $users;

    }
}
