<?php
global $conn, $userId;
session_start();
use App\models\Admin;
use App\models\User;

require_once '../../db.php';  // Assuming db.php contains the database connection
require_once 'C:\xampp\htdocs\web-project\src\models\User.php';
require_once 'C:\xampp\htdocs\web-project\src\models\Admin.php';
require_once '../../services/LoginService.php';

// Create an instance of the User class with the database connection
$userModel = new User($conn);
$adminModel = new Admin($conn);

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $rememberMe = isset($_POST['remember_me']);

    // Check if the user is blocked
    if ($userModel->isBlocked($email)) {
        $_SESSION['error_message'] = "Your account is temporarily locked due to multiple failed login attempts. Please try again after 30 minutes.";
        header("Location: /login.php");
        exit();
    }

    // First, check if it's an admin login
    $this->admin->authenticateAdmin($email, $password);

    if ($adminModel) {
        // Admin login successful
        $_SESSION['admin_id'] = $adminModel['id'];
        $_SESSION['role'] = 'Admin';
        $_SESSION['last_activity'] = time();

        // Handle "Remember Me" for admin
        if ($rememberMe) {
            // Generate a random token
            $token = bin2hex(random_bytes(16));

            // Cookie parameters
            $cookie_name = "remember_me";
            $cookie_value = $token;

            $path = "/";
            $domain = ""; // Adjust if needed, e.g., ".example.com"
            $secure = true; // Set to true for HTTPS
            $httponly = true; // Prevent JavaScript access
            $samesite = "Strict"; // Options: Strict, Lax, None

            // Set the cookie
            setcookie(
                $cookie_name,
                $cookie_value,
                [


                    'path' => $path,
                    'domain' => $domain,
                    'secure' => $secure,
                    'httponly' => $httponly,
                    'samesite' => $samesite
                ]
            );

            // Save the token in the database
            $this->admin->saveRememberMeToken($adminModel['id'], $token);
        }


        // Redirect to the admin dashboard
        header("Location: /admin/dashboard.php");
        exit();
    } else {
        // Check for regular user login
        $this->user ->authenticateUser($email, $password);

        if ($userModel) {
            // User login successful
            // Reset failed login attempts for user
            $this-> user-> resetFailedAttempts($userId);

            // Start session for user
            $_SESSION['user_id'] = $userModel['id'];
            $_SESSION['role'] = $userModel['role'];
            $_SESSION['last_activity'] = time();

            // Handle "Remember Me" for user
            if ($rememberMe) {
                $token = bin2hex(random_bytes(16)); // Generate a random token
                $cookie_name = "remember_me";
                $cookie_value = $token;
                $path = "/";
                $domain = ""; // Set to your domain if needed, e.g., ".example.com"
                $secure = true; // Set to true for HTTPS
                $httponly = true; // Prevent access via JavaScript
                $samesite = "Strict"; // Options: Strict, Lax, None (None requires HTTPS)

                // Set the cookie with all options
                setcookie(
                    $cookie_name,
                    $cookie_value,
                    [

                        'path' => $path,
                        'domain' => $domain,
                        'secure' => $secure,
                        'httponly' => $httponly,
                        'samesite' => $samesite
                    ]
                );

                // Save the token in the database
                $this->user->saveRememberMeToken($userModel['id'], $token);
            }


            // Redirect to the user dashboard
            header("Location: /home");
            exit();
        } else {
            // Incorrect login credentials
            // Increment failed login attempts for user
            $this -> user ->incrementFailedAttempts($userId);

            // Insert failed login attempt into login_attempts table
            $user_id = User::getUserIdByEmail($email); // Assuming a method to get user ID by email
            $sql = "INSERT INTO login_attempts (user_id) VALUES (?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $user_id);
            $stmt->execute();

            // Set the error message in session for display in modal
            $_SESSION['error_message'] = "Invalid email or password.";
            header("Location: /login.php");
            exit();
        }
    }
}



// Include the login form HTML from the separate file
include('public/login.html');
