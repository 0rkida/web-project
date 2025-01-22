<?php

$itemName = "Premium Subscription" ;
$itemPrice = "25";
$currency = "EUR" ;

const STRIPE_PUBLISHABLE_KEY = 'sk_test_51QdZaOIA6j8Agjdo5GJTLy0SpIDPNae1bPpzug7GTqcDIUlNR7CKK1ojTH9gGYRe0uEHUjbnpIDGSKukUVTONETg00wfXvfRpk';
const STRIPE_SECRET_KEY = 'pk_test_51QdZaOIA6j8AgjdoONN2YmHKTojcogE82ZcF8ntm0l1YwdZNUKNnlDgxb62vZ7IBVbS1NfyGQoRNxjWn6o0bvJxE00alHhiENc';

const DB_HOST = 'localhost';
const DB_USERNAME = 'root';
const DB_PASSWORD = '';
const DB_NAME = 'datting_app';

function getDbConnection() {
    $conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

    if ($conn->connect_error) {
        die("Database connection failed: " . $conn->connect_error);
    }

    return $conn;
}
?>



