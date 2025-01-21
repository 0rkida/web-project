CREATE TABLE user_sessions (
    session_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    last_activity DATETIME NOT NULL,
    session_start DATETIME NOT NULL,
    session_timeout INT NOT NULL DEFAULT 900,
    is_active BOOLEAN NOT NULL DEFAULT TRUE,
    FOREIGN KEY (user_id) REFERENCES users(id)
);
