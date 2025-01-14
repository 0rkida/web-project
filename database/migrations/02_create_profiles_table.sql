CREATE TABLE profile (
    user_id INT PRIMARY KEY, -- Unique ID for the profile
#     full_name VARCHAR(100) NOT NULL,   -- User's full name
#     email VARCHAR(255) NOT NULL UNIQUE, -- User's email (unique)
#     password VARCHAR(255) NOT NULL,    -- User's password (hashed)
#     role ENUM('user', 'admin') DEFAULT 'user', -- User role
    profile_picture VARCHAR(255) DEFAULT 'default.png', -- profileviews picture path
    age INT,                           -- User's age
    gender ENUM('male', 'female'), -- User's gender
    location VARCHAR(255),             -- User's location
    match_percentage INT,              -- Matches percentage
    self_summary TEXT,                 -- Self-summary
    hobby TEXT,                        -- Hobbies
    doing_with_life TEXT,              -- Activities or goals
    good_at TEXT,                      -- Skills or hobbies
    last_online TIMESTAMP,             -- Last online timestamp
    ethnicity VARCHAR(50),             -- Ethnicity
    height FLOAT,                      -- Height (in meters or inches)
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, -- Creation date
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP -- Update date
);
