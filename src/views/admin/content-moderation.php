<?php

global $conn;
$query = "SELECT * FROM content WHERE status = 'flagged'";
$result = $conn->query($query);

if (isset($_POST['approve_content'])) {
    $contentId = $_POST['content_id'];
    $query = "UPDATE content SET status = 'approved' WHERE id = $contentId";
    $conn->query($query);
}
if (isset($_POST['reject_content'])) {
    $contentId = $_POST['content_id'];
    $query = "UPDATE content SET status = 'rejected' WHERE id = $contentId";
    $conn->query($query);
}

