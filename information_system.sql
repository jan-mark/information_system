CREATE DATABASE IF NOT EXISTS information_system
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE information_system;

DROP TABLE IF EXISTS student_info;
DROP TABLE IF EXISTS students;
DROP TABLE IF EXISTS users;

CREATE TABLE users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    full_name VARCHAR(100) NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


CREATE TABLE students (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL,
    student_number VARCHAR(30) NOT NULL UNIQUE,
    first_name VARCHAR(60) NOT NULL,
    last_name VARCHAR(60) NOT NULL,
    course VARCHAR(80) NOT NULL,
    year_level TINYINT UNSIGNED NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_students_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE student_info (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    student_id INT UNSIGNED NOT NULL,
    field_type VARCHAR(50) NOT NULL,
    plain_value VARCHAR(255) NOT NULL,
    md5_hash CHAR(32),
    bcrypt_hash VARCHAR(255),
    sha256_hash CHAR(64),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_info_student FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE
);

-- Insert Sample Users
INSERT INTO users (username, full_name, password) VALUES
('admin', 'Administrator', 'admin123'),
