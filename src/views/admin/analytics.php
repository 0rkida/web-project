<?php

// Count new users in the last 30 days
global $conn;
$query = "SELECT COUNT(*) AS new_users FROM users WHERE created_at >= NOW() - INTERVAL 30 DAY";
$newUsers = $conn->query($query)->fetch_assoc()['new_users'];

// Count total matches
$query = "SELECT COUNT(*) AS total_matches FROM matches";
$totalMatches = $conn->query($query)->fetch_assoc()['total_matches'];
