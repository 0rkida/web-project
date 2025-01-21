<?php
include 'sessionManager.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['photos'])) {
    $uploadsDir = 'public/assets/img/user-uploads/albums/';  // Make sure this ends with a slash

    foreach ($_FILES['photos']['tmp_name'] as $index => $tmpName) {
        $fileName = $_FILES['photos']['name'][$index];
        $fileTmp = $_FILES['photos']['tmp_name'][$index];
        $fileSize = $_FILES['photos']['size'][$index];
        $fileError = $_FILES['photos']['error'][$index];

        // Check if there was no error during the upload
        if ($fileError === UPLOAD_ERR_OK) {
            // Check file size (optional)
            if ($fileSize < 5000000) {  // 5MB limit
                $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];

                // Ensure the file is an image
                if (in_array($fileExtension, $allowedExtensions)) {
                    // Generate a unique file name to avoid conflicts
                    $newFileName = uniqid('photo_') . '.' . $fileExtension;

                    // Move the uploaded file to the server
                    if (move_uploaded_file($fileTmp, $uploadsDir . $newFileName)) {
                        // File is successfully uploaded
                        echo 'File uploaded successfully: ' . $newFileName;
                        // Optionally, you could add the file path to the database here
                    } else {
                        echo 'Error moving the uploaded file.';
                    }
                } else {
                    echo 'Invalid file type. Only JPG, JPEG, PNG, and GIF files are allowed.';
                }
            } else {
                echo 'File size exceeds the limit (5MB).';
            }
        } else {
            echo 'Error uploading file: ' . $fileError;
        }
    }
} else {
    echo 'No files selected.';
}
