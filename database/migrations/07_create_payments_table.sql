CREATE TABLE payments (
                          id INT AUTO_INCREMENT PRIMARY KEY,
                          user_id INT NOT NULL,
                          amount DECIMAL(10, 2) NOT NULL,
                          currency VARCHAR(10) DEFAULT 'USD',
                          payment_intent_id VARCHAR(255) NOT NULL,
                          status VARCHAR(50) NOT NULL,
                          created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                          updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
