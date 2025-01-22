<?php
namespace App\models;
class LoginLog {
    private  $conn ;


    public function __construct($db) {
        $this->conn = $db;
    }

    public function insertEmail( $email, $ip_address = null, $success = null): bool
    {
        $query = "INSERT INTO LoginLogs ( email, LoginTimestamp, IPAddress, success) 
                  VALUES ( :email, :login_timestamp, :ip_address, :success)";

        $stmt = $this->conn->prepare($query);

        $login_timestamp = date('Y-m-d H:i:s'); // Generate the current timestamp

        // Sanitize inputs

        $email = htmlspecialchars(strip_tags($email));
        $ip_address = htmlspecialchars(strip_tags($ip_address));
        $success = htmlspecialchars(strip_tags($success));

        // Bind parameters

        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':login_timestamp', $login_timestamp);
        $stmt->bindParam(':ip_address', $ip_address);
        $stmt->bindParam(':success', $success);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }
}
