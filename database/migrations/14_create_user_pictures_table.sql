CREATE TABLE user_pictures (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    picture_path VARCHAR(255),
    FOREIGN KEY (user_id) REFERENCES users(id)
);
