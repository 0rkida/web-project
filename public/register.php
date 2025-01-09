<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
require 'C:\xampp\htdocs\web-project\vendor\autoload.php';

if (isset($_POST['register'])) {
    // Get the form input values
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Input validation
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format!";
        exit();
    }

    if (strlen($password) < 6) {
        echo "Password must be at least 6 characters long!";
        exit();
    }

    // Database connection
    $conn = mysqli_connect("localhost", "root", "root", "test");

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Check if email already exists in the database
    $checkEmailQuery = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $checkEmailQuery);

    if (mysqli_num_rows($result) > 0) {
        echo "This email address is already registered. Please use a different one.";
        exit();
    }

    // Generate verification code
    $verificationCode = substr(number_format(time() * rand(), 0, '', ''), 0, 6);

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
        $mail->addAddress($email, $name); // Add user email
        $mail->addReplyTo('no-reply@yourdomain.com', 'Your Site');

        // Set email format to HTML
        $mail->isHTML(true);
        $mail->Subject = "Verification Code";
        $mail->Body = "<p>Your verification code is: <b>$verificationCode</b></p>";

        $mail->send();

        // Hash password before storing it
        $encrypted_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert user data into database
        $sql = "INSERT INTO users (name, email, username, password, verification_code, email_verified_at) 
                VALUES ('$name', '$email', '$username', '$encrypted_password', '$verificationCode', NULL)";

        if (mysqli_query($conn, $sql)) {
            // Redirect to email verification page
            header("Location: verify.php?email=$email&code=$verificationCode");
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
        <input type="text" name="username" placeholder="Enter your username" required />
        <input type="password" name="password" placeholder="Enter your password" required />
        <input type="submit" name="register" value="Register" />
    </form>';
}
