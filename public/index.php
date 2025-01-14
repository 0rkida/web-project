<?php
session_set_cookie_params([
    'lifetime' => 3600, // 1 hour
    'path' => '/', // Available throughout the site
    'domain' => 'localhost', // Ensure this matches your domain
    'secure' => false, // Set to true if using HTTPS
    'httponly' => true, // Make the cookie accessible only via HTTP and not JavaScript
    'samesite' => 'Lax', // For cross-site request handling
]);
session_start();

require_once '../src/routes.php';
