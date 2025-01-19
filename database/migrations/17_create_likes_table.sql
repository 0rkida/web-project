CREATE TABLE likes (
                       id INT AUTO_INCREMENT PRIMARY KEY,
                       user_id INT NOT NULL,
                       liked_user_id INT NOT NULL,
                       created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                       UNIQUE(user_id, liked_user_id),
                       FOREIGN KEY (user_id) REFERENCES users(id),
                       FOREIGN KEY (liked_user_id) REFERENCES users(id)
);
