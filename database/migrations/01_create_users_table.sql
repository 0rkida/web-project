CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    username VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('user' , 'admin') DEFAULT 'user',
    verification_code VARCHAR(64) NOT NULL,
    is_verified TINYINT(1) DEFAULT 0

);

