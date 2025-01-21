CREATE TABLE login_attempts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    attempt_time DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    last_failed_attempt DATETIME NULL,  -- Column to store the time of the last failed attempt
    failed_attempts INT NOT NULL DEFAULT 0,  -- Column to count failed attempts
    lockout_time TIMESTAMP NULL DEFAULT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
