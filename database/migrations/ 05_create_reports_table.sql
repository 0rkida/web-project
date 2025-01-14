CREATE TABLE reports (
    id INT AUTO_INCREMENT PRIMARY KEY,
    reported_id INT NOT NULL,
    reason TEXT NOT NULL,
    status ENUM('pending', 'reviewed') DEFAULT 'pending',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (reported_id) REFERENCES users(id)
);