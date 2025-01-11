<?php


global $conn;

require_once __DIR__ . 'C:\xampp\htdocs\web-project\src\controllers\db.php';

// Set the directory containing migration files
$migrationDir = __DIR__ . '/../database';

echo $migrationDir . PHP_EOL . PHP_EOL;

// Get all PHP files in the directory, sorted by name
$migrationFiles = glob($migrationDir . '/*.php');
sort($migrationFiles, SORT_NATURAL);

echo "Migration files found: " . count($migrationFiles) . PHP_EOL . PHP_EOL;


// Loop through and include each file
foreach ($migrationFiles as $file) {
    echo "Running migration: " . basename($file) . PHP_EOL;

    // Include the migration file
    require_once $file;

    // Execute the migration function if defined
    if (function_exists('runMigration')) {
        runMigration($conn);
    }

    echo "Migration completed: " . basename($file) . PHP_EOL . PHP_EOL;
}