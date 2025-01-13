CREATE TABLE notifications (
                               id INT AUTO_INCREMENT PRIMARY KEY,
                               user_id INT NOT NULL,
                               type ENUM('like', 'match', 'message') NOT NULL,
                               message VARCHAR(255) NOT NULL,
                               is_read BOOLEAN DEFAULT FALSE,
                               created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                               FOREIGN KEY (user_id) REFERENCES users(id)
);
