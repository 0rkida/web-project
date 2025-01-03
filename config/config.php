<?php

// Dynamically define the project root (absolute path on the filesystem)
define('PROJECT_ROOT', realpath(__DIR__ . '/../') . '/');

// Dynamically define the base URL (for web paths)
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";
$host = $_SERVER['HTTP_HOST'];
$scriptDir = str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);

define('BASE_URL', $protocol . $host . $scriptDir);

// Function to generate file paths
function app_path($path = ''): string
{
    return PROJECT_ROOT . ltrim($path, '/');
}

// Function to generate web URLs
function base_url($path = ''): string
{
    return BASE_URL . ltrim($path, '/');
}