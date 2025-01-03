<?php

require_once __DIR__ . '/../config/db_connection.php';

global $conn;

function seedDatabase($conn): bool
{
    // Seed users table
    echo PHP_EOL . "Seeding users table...";
    for ($i = 0; $i < 50; $i++) {
        $name = "User $i";
        $email = "user$i@example.com";
        $password = password_hash("password$i", PASSWORD_DEFAULT);
        $role = $i % 2 === 0 ? 'admin' : 'user';
        $emailConfirmed = mt_rand(0, 1);

        $query = "INSERT INTO users (name, email, password, role, email_confirmed) VALUES ('$name', '$email', '$password', '$role', $emailConfirmed)";
        $conn->query($query);
    }

    echo PHP_EOL . "Seeding complete.";
    return true; // Return true if seeding was successful
}

// Run the seeder
seedDatabase($conn);
