CREATE TABLE password_resets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL,
    user_id INT NOT NULL,
    reset_token VARCHAR(255) NOT NULL,
    reset_token_expiry DATETIME NOT NULL,
    remember_token VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP

);
