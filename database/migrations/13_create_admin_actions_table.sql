CREATE TABLE admin_actions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    reported_id INT NOT NULL,
    action ENUM('warn', 'block', 'delete') NOT NULL,
    action_taken_by INT NOT NULL,
    action_reason TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (reported_id) REFERENCES reports(id) ON DELETE CASCADE,
    FOREIGN KEY (action_taken_by) REFERENCES admins(id) ON DELETE SET NULL
);