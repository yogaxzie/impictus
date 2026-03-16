CREATE DATABASE dxiepro_db;
USE dxiepro_db;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    login_code VARCHAR(50) NOT NULL UNIQUE,
    is_premium BOOLEAN DEFAULT FALSE,
    is_admin BOOLEAN DEFAULT FALSE,
    expired_date DATE NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert admin default
INSERT INTO users (username, email, password, login_code, is_admin, is_premium, expired_date) 
VALUES ('DxiEPro', 'admin@dxiepro.it', MD5('252532.com'), 'Cv27x', TRUE, TRUE, '2099-12-31');

-- Insert contoh user free trial
INSERT INTO users (username, email, password, login_code, is_premium, expired_date) 
VALUES ('usertrial', 'trial@email.com', MD5('trial123'), 'TrialX7', FALSE, DATE_ADD(NOW(), INTERVAL 7 DAY));
