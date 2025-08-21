-- Fix old records: set visit_date and entry_time from created_at if missing or invalid
UPDATE visitors SET visit_date = DATE(created_at) WHERE visit_date IS NULL OR visit_date = '0000-00-00';
UPDATE visitors SET entry_time = TIME(created_at) WHERE entry_time IS NULL OR entry_time = '00:00:00';
CREATE DATABASE IF NOT EXISTS visitor_management;

USE visitor_management;

CREATE TABLE IF NOT EXISTS visitors (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    contact VARCHAR(10) NOT NULL,
    purpose VARCHAR(255) NOT NULL,
    department VARCHAR(255) DEFAULT NULL,
    dean_department VARCHAR(255) DEFAULT NULL,
    cdc_program VARCHAR(255) DEFAULT NULL,
    synergy_program VARCHAR(255) DEFAULT NULL,
    admission_program VARCHAR(50) DEFAULT NULL,
    domain VARCHAR(255) DEFAULT NULL,
    course VARCHAR(255) DEFAULT NULL,
    qualification VARCHAR(50) DEFAULT NULL,
    percentage DECIMAL(5, 2) DEFAULT NULL,
    location VARCHAR(255) NOT NULL,
    visit_date DATE NOT NULL,
    entry_time TIME NOT NULL,
    exit_time TIME DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    passkey VARCHAR(20) DEFAULT NULL
);

ALTER TABLE visitors MODIFY exit_time TIME DEFAULT NULL;

UPDATE visitors SET visit_date = DATE(created_at) WHERE visit_date IS NULL OR visit_date = '0000-00-00';
UPDATE visitors SET entry_time = TIME(created_at) WHERE entry_time IS NULL OR entry_time ='00:00:00';