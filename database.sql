-- Password Manager Database Schema
-- Create the database
-- CREATE DATABASE password_manager;


-- Categories table for organizing credentials
CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    description TEXT,
    icon VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Main credentials table
CREATE TABLE credentials (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT NOT NULL,
    service_name VARCHAR(255) NOT NULL,
    username VARCHAR(255),
    email VARCHAR(255),
    password_encrypted TEXT NOT NULL,
    website_url VARCHAR(500),
    notes TEXT,
    is_favorite BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    last_used TIMESTAMP NULL,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE,
    INDEX idx_category (category_id),
    INDEX idx_service (service_name),
    INDEX idx_email (email)
);

-- Credit cards table (separate for security)
CREATE TABLE credit_cards (
    id INT AUTO_INCREMENT PRIMARY KEY,
    card_name VARCHAR(255) NOT NULL,
    cardholder_name VARCHAR(255) NOT NULL,
    card_number_encrypted TEXT NOT NULL,
    expiry_month INT NOT NULL,
    expiry_year INT NOT NULL,
    cvv_encrypted TEXT NOT NULL,
    bank_name VARCHAR(255),
    card_type ENUM('visa', 'mastercard', 'amex', 'discover', 'other') DEFAULT 'other',
    billing_address TEXT,
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Secure notes table for other sensitive information
CREATE TABLE secure_notes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    content_encrypted TEXT NOT NULL,
    category VARCHAR(100),
    tags VARCHAR(500),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_category (category),
    INDEX idx_title (title)
);

-- Password history table (to track password changes)
CREATE TABLE password_history (
    id INT AUTO_INCREMENT PRIMARY KEY,
    credential_id INT NOT NULL,
    old_password_encrypted TEXT NOT NULL,
    changed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (credential_id) REFERENCES credentials(id) ON DELETE CASCADE,
    INDEX idx_credential (credential_id)
);

-- Insert default categories
INSERT INTO categories (name, description, icon) VALUES
('Social Media', 'Facebook, Instagram, Twitter, LinkedIn, etc.', 'social'),
('Email', 'Gmail, Yahoo, Outlook, ProtonMail, etc.', 'email'),
('Banking', 'Online banking, financial services', 'bank'),
('Shopping', 'Amazon, eBay, online stores', 'shopping'),
('Work', 'Work-related accounts and services', 'work'),
('Entertainment', 'Netflix, Spotify, YouTube, gaming', 'entertainment'),
('Cloud Storage', 'Google Drive, Dropbox, OneDrive', 'cloud'),
('Development', 'GitHub, GitLab, coding platforms', 'code'),
('VPN/Security', 'VPN services, antivirus, security tools', 'security'),
('Other', 'Miscellaneous accounts', 'other');

-- Sample data insertion (DO NOT use real passwords)
-- Example for Google account
INSERT INTO credentials (category_id, service_name, username, email, password_encrypted, website_url, notes) 
VALUES 
(2, 'Google', 'john_doe', 'john.doe@gmail.com', 'ENCRYPTED_PASSWORD_HERE', 'https://accounts.google.com', 'Main Google account'),
(1, 'Facebook', 'johndoe123', 'john.doe@gmail.com', 'ENCRYPTED_PASSWORD_HERE', 'https://facebook.com', 'Personal Facebook account');

-- Example credit card (DO NOT use real card numbers)
INSERT INTO credit_cards (card_name, cardholder_name, card_number_encrypted, expiry_month, expiry_year, cvv_encrypted, bank_name, card_type, notes)
VALUES 
('Main Credit Card', 'John Doe', 'ENCRYPTED_CARD_NUMBER_HERE', 12, 2027, 'ENCRYPTED_CVV_HERE', 'Chase Bank', 'visa', 'Primary credit card');

-- Useful queries for managing your passwords

-- 1. Get all credentials by category
-- SELECT c.*, cat.name as category_name 
-- FROM credentials c 
-- JOIN categories cat ON c.category_id = cat.id 
-- WHERE cat.name = 'Social Media';

-- 2. Search for specific service
-- SELECT * FROM credentials WHERE service_name LIKE '%google%';

-- 3. Get recently used credentials
-- SELECT * FROM credentials ORDER BY last_used DESC LIMIT 10;

-- 4. Get all credit cards
-- SELECT card_name, cardholder_name, bank_name, card_type FROM credit_cards;

-- 5. Count credentials by category
-- SELECT cat.name, COUNT(c.id) as credential_count 
-- FROM categories cat 
-- LEFT JOIN credentials c ON cat.id = c.category_id 
-- GROUP BY cat.id, cat.name;

-- Security recommendations:
-- 1. Always encrypt passwords and sensitive data before storing
-- 2. Use a strong master password for your application
-- 3. Implement proper access controls
-- 4. Regular backups of your database
-- 5. Consider using AES encryption for sensitive fields
-- 6. Never store plain text passwords

-- Create a view for easy credential overview (without sensitive data)
CREATE VIEW credential_overview AS
SELECT 
    c.id,
    c.service_name,
    c.username,
    c.email,
    c.website_url,
    cat.name as category,
    c.is_favorite,
    c.created_at,
    c.last_used
FROM credentials c
JOIN categories cat ON c.category_id = cat.id;