<?php
global $conn;
require 'db_connection.php';

if ($conn) {
    echo "Database connection successful!";
} else {
    echo "Database connection failed!";
}

