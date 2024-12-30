<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
require 'C:\xampp\htdocs\web-project\vendor\autoload.php';

if (isset($_POST['register'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Generate verification code
    $verificationCode = substr(number_format(time() * rand(),0,'',''),0,6);

    // PHPMailer instance
    $mail = new PHPMailer(true);

    try {
        // Mailer configurations
        $mail->SMTPDebug = 0;
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'your_email@gmail.com'; // Set your email
        $mail->Password = 'your_password'; // Set your password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('no-reply@yourdomain.com', 'Your Site');
        $mail->addAddress($email, $name);     // Add user email
        $mail->addReplyTo('no-reply@yourdomain.com', 'Your Site');

        // Set email format to HTML
        $mail->isHTML(true);
        $mail->Subject = "Verification Code";
        $mail->Body    = "<p>Your verification code is: <b>$verificationCode</b></p>";

        $mail->send();

        // Hash password before storing it
        $encrypted_code = password_hash($password, PASSWORD_DEFAULT);

        // Database connection
        $conn = mysqli_connect("localhost", "root", "root", "test");

        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }

        // Insert user data into database
        $sql = "INSERT INTO users (name, email, password, verification_code, email_verified_at) VALUES ('$name', '$email', '$encrypted_code', '$verificationCode', NULL)";

        if (mysqli_query($conn, $sql)) {
            header("Location: email_verification.php?email=$email&code=$verificationCode");
            exit();
        } else {
            throw new Exception("Database error: " . mysqli_error($conn));
        }

    } catch (Exception $e) {
        // Show the form again with error message
        echo "<p>Error: " . $e->getMessage() . "</p>";
    }
} else {
    // Display the registration form
    echo '<form method="POST" action="">
        <input type="text" name="name" placeholder="Enter your name" required />
        <input type="email" name="email" placeholder="Enter your email" required />
        <input type="password" name="password" placeholder="Enter your password" required />
        <input type="submit" name="register" value="Register" />
    </form>';
}


if (isset($_GET['email']) && isset($_GET['code'])) {
    $email = $_GET['email'];
    $verificationCode = $_GET['code'];

    // Database connection
    $conn = mysqli_connect("localhost", "root", "root", "test");

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Check if verification code matches in database
    $query = "SELECT * FROM users WHERE email = '$email' AND verification_code = '$verificationCode' AND email_verified_at IS NULL";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        // Verification successful
        $updateQuery = "UPDATE users SET email_verified_at = NOW() WHERE email = '$email'";
        if (mysqli_query($conn, $updateQuery)) {
            echo "Email verified successfully!";
        } else {
            echo "Error updating record: " . mysqli_error($conn);
        }
    } else {
        echo "Invalid verification code or email.";
    }

    mysqli_close($conn);
}
