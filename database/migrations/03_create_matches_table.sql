CREATE TABLE matches (
                         id INT AUTO_INCREMENT PRIMARY KEY,
                         user1_id INT NOT NULL,
                         user2_id INT NOT NULL,
                         created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                         FOREIGN KEY (user1_id) REFERENCES users(id),
                         FOREIGN KEY (user2_id) REFERENCES users(id)
);
