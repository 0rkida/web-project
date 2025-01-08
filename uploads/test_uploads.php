<?php
$dir = 'uploads/';

if (is_writable($dir)) {
    echo "The directory is writable!";
} else {
    echo "The directory is not writable!";
}

