-- SQL Injection Training Lab - Database Schema
-- WARNING: This is intentionally vulnerable for educational purposes

USE sqli_lab;

-- Users table for authentication labs
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL,
    secret_data TEXT
);

-- Articles table for query injection labs
CREATE TABLE IF NOT EXISTS articles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    content TEXT NOT NULL
);

-- Insert sample users
INSERT INTO users (username, password, secret_data) VALUES
('admin', 'supersecretpassword123', 'FLAG{admin_secret_data_exposed}'),
('john_doe', 'password123', 'Personal notes: Remember to update server'),
('jane_smith', 'qwerty456', 'Credit card: 4111-XXXX-XXXX-1234'),
('guest', 'guest', 'No sensitive data for guest account'),
('developer', 'dev2024!', 'API_KEY=sk-proj-xxxxxxxxxxxxx');

-- Insert sample articles
INSERT INTO articles (id, title, content) VALUES
(1, 'Welcome to SQL Injection Lab', 'This is a training environment to learn about SQL injection vulnerabilities. Always practice responsibly!'),
(2, 'Understanding UNION Attacks', 'The UNION operator is used to combine results from multiple SELECT statements. Attackers can use this to extract data from other tables.'),
(3, 'Error-Based Injection', 'By forcing database errors, attackers can extract information from error messages. This is why error display should be disabled in production.'),
(4, 'Blind SQL Injection', 'When no direct output is shown, attackers can still infer data by observing application behavior changes.'),
(5, 'Prevention Techniques', 'Use prepared statements, parameterized queries, and input validation to prevent SQL injection attacks.');

-- Lab 6: Second-Order SQLi - Users table
CREATE TABLE IF NOT EXISTS lab6_users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL,
    password VARCHAR(255) NOT NULL
);

-- Insert admin user for Lab 6 demonstration
INSERT INTO lab6_users (username, password) VALUES
('admin', 'admin_original_password');

-- Lab 9: Secret Documents table (simulates file system for LOAD_FILE demo)
CREATE TABLE IF NOT EXISTS secret_documents (
    id INT AUTO_INCREMENT PRIMARY KEY,
    filename VARCHAR(255) NOT NULL,
    content TEXT NOT NULL
);

-- Insert confidential documents
INSERT INTO secret_documents (filename, content) VALUES
('confidential.txt', '===============================================
🔒 CONFIDENTIAL DOCUMENT - INTERNAL USE ONLY
===============================================

Document ID: SEC-2024-001
Classification: TOP SECRET
Issued: 2024-01-15

SENSITIVE EMPLOYEE DATA
-----------------------
Employee ID: E10042
Name: Sarah Chen
Position: Senior Security Engineer
Clearance Level: ALPHA-5
API Key: sk-prod-a8f7b2c9d4e1f6a3b8c7d2e9
Database Password: MyS3cr3tP@ssw0rd!2024

SYSTEM CREDENTIALS
------------------
Admin Portal: https://admin.thedailysecure.internal
Username: sysadmin
Password: Sup3rS3cur3P@ss!
Two-Factor Backup: 8472-9364-1047-2856

INFRASTRUCTURE DETAILS
----------------------
Primary DB Server: 10.0.1.50
Backup Location: /mnt/secure_backup/daily_dumps/
Encryption Key (AES-256): 4f9a2b8c7d6e5a3f1b8c9d7e6a5b4c3d

⚠️ WARNING: Unauthorized access or disclosure of this
information may result in immediate termination and
legal prosecution under Corporate Security Policy §7.3'),

('db_credentials.txt', 'Production Database Credentials
================================
Server: db-prod-01.internal
Port: 3306
Database: production_db
Username: db_admin
Password: Pr0d_DB_P@ssw0rd_2024
Root Password: R00t_MyS3cr3tK3y!

Backup Server: db-backup-01.internal
Replication Key: rep_key_a9f8b7c6d5e4'),

('api_keys.txt', 'API Keys & Tokens
==================
AWS Access Key: AKIAIOSFODNN7FAKEKEY
AWS Secret Key: wJalrXUtnFEMI/K7MDENG/bPxRfiCYFAKEKEY
Stripe API Key: sk_test_51HqT2pK3k4l5m6n7o8p9q0rFAKE
SendGrid API: SG.FAKEKEYc3d4e5f6g7h8i9j0k1l2m3n4o5p6
Google Maps API: AIzaSyD1234567890ABCDEFGHabcdefghFAKE'),

('salary_data.csv', 'Employee_ID,Name,Department,Annual_Salary,Bonus
E10001,John Smith,Engineering,125000,15000
E10002,Jane Doe,Marketing,98000,12000
E10042,Sarah Chen,Security,145000,20000
E10055,Michael Brown,Finance,135000,18000
E10099,Lisa Anderson,HR,92000,10000');
