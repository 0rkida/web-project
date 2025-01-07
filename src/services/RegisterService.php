<?php
global $conn;
include '../db.php';

function insertUser(): void{

}


function generateVerificationCode(): string {
    try {
        return bin2hex(random_bytes(32));
    } catch (Exception $e) {
        die('Could not generate verification code: ' . $e->getMessage());
    }
}