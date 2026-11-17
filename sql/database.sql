CREATE DATABASE IF NOT EXISTS library_platform CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE library_platform;
CREATE TABLE IF NOT EXISTS members (
 id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 full_name VARCHAR(120) NOT NULL,
 email VARCHAR(190) NOT NULL UNIQUE,
 phone VARCHAR(30) NOT NULL,
 membership_type ENUM('Student','Faculty','Community') NOT NULL,
 favorite_genre VARCHAR(60) NOT NULL,
 notes TEXT NULL,
 created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);
