CREATE TABLE matches (
    id INT AUTO_INCREMENT PRIMARY KEY,         -- Matches ID (Primary Key, auto-incremented)
    user_id INT NOT NULL,                      -- User ID (foreign key, assuming users table exists)
    match_name VARCHAR(255) NOT NULL,          -- Matches name (e.g., the title of the match)
    match_details TEXT,                        -- Matches details (e.g., description or extra information)
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,  -- Date and time when the match was created
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, -- Date and time when the match was last updated
      FOREIGN KEY (user_id) REFERENCES users(id) -- Assumes a 'users' table with a 'user_id' primary key
);
