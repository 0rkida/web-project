CREATE TABLE reports (
    id INT AUTO_INCREMENT PRIMARY KEY,
    reporting_user_id INT NOT NULL,
    reported_user_id INT NOT NULL,
    reason TEXT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (reporting_user_id) REFERENCES users(id),
    FOREIGN KEY (reported_user_id) REFERENCES users(id)
);
