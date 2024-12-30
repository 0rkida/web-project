<?php
class Database {
    private static $conn;

    // Get the database connection
    public static function getConnection() {
        if (!self::$conn) {
            self::$conn = new mysqli('localhost', 'username', 'password', 'database');

            // Check connection
            if (self::$conn->connect_error) {
                die("Connection failed: " . self::$conn->connect_error);
            }
        }
        return self::$conn;
    }

    // Close the database connection
    public static function closeConnection() {
        if (self::$conn) {
            self::$conn->close();
            self::$conn = null;
        }
    }
}

// Usage example
// $conn = Database::getConnection();
// Database::closeConnection();
