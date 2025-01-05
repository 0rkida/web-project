<?php

const DB_HOST = 'localhost';     // MySQL host (usually 'localhost')
const DB_USERNAME = 'root';      // MySQL username
const DB_PASSWORD = '';          // MySQL password (empty if not set)
const DB_NAME = 'datting_app';      // Your database name

try {
    $conn = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME,
        DB_USERNAME,
        DB_PASSWORD
    );
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connected successfully"; // Debug message (remove in production)
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
