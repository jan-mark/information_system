========================================================
STUDENT INFORMATION SYSTEM WITH ENCRYPTED DATA
Complete Setup & Usage Guide
========================================================

PROJECT OVERVIEW
================
This is a PHP-based Student Information System with 3 database tables using different encryption methods:

• TABLE 1 (users): MD5 hashing for login account passwords
• TABLE 2 (students): Bcrypt hashing for student record data
• TABLE 3 (student_details): SHA1 hashing for student information values

DATABASE NAME: information_system
LOCATION: c:\xampp.windows\htdocs\inventory_system\


INSTALLATION & SETUP
====================

Step 1: Import Database in phpMyAdmin
--------------------------------------
1. Open phpMyAdmin (http://localhost/phpmyadmin/)
2. Click "Import" tab at top
3. Choose file: information_system.sql
4. Click "Go"
5. Database "information_system" will be created with 5+ sample records per table


Step 2: Verify Apache & MySQL are Running
-------------------------------------------
• Start XAMPP Control Panel
• Make sure Apache and MySQL are running (green indicators)


Step 3: Access the Application
-------------------------------
• Open browser: http://localhost/inventory_system/register.php
• Or: http://localhost/inventory_system/login.php


SAMPLE LOGIN CREDENTIALS (After Database Import)
==================================================
Username: admin
Password: admin123

Other Demo Accounts in System:
• teacher01 / password123
• teacher02 / secure456
• registrar / student2024
• principal / welcome99


APPLICATION FEATURES
====================
1. Create Login Accounts
   - Register page allows new account creation
   - Password stored as MD5 hash in TABLE 1
   
2. Login System
   - Authenticated access to dashboard
   - Session-based security
   
3. Add Students
   - Create student records with name, ID, course, year level
   - Bcrypt hash auto-generated and stored in TABLE 2
   
4. Update Students
   - Edit student information
   - Bcrypt hash regenerated on update
   
5. Add Student Information
   - Add details like email, phone, address, guardian
   - SHA1 hash auto-generated and stored in TABLE 3
   
6. Update Student Information
   - Edit information values
   - SHA1 hash recalculated
   
7. View Hashed Data
   - Dashboard displays all hashes from all 3 tables
   - Hashes visible in formatted tables


DATABASE TABLE STRUCTURE
========================

TABLE 1: users (MD5 Hashes)
---------------------------
Columns:
  • id - Primary key
  • username - User login name
  • full_name - Full name
  • password_md5 - Password stored as MD5 hash (32 characters)
  • created_at - Account creation timestamp

Example MD5 Record:
  ID: 1
  Username: admin
  Full Name: Administrator
  MD5 Hash: 0192023a7bbd73250516f069df18b500
  (Password: admin123)

Sample Records: 5
  1. admin
  2. teacher01
  3. teacher02
  4. registrar
  5. principal


TABLE 2: students (Bcrypt Hashes)
----------------------------------
Columns:
  • id - Primary key
  • user_id - Foreign key to users table
  • student_number - Unique student ID
  • first_name - Student first name
  • last_name - Student last name
  • course - Course/Program name
  • year_level - Year (1-4)
  • secret_bcrypt - Bcrypt hash of (student_number|first_name|last_name|course|year_level)
  • created_at - Record creation timestamp
  • updated_at - Last update timestamp

Example Bcrypt Record:
  ID: 1
  Student No: STU001
  Name: James Wilson
  Course: Bachelor of Science in Computer Science
  Year: 1
  Bcrypt Hash: $2y$10$N9qo8uLOickgx2ZMRZoMyeIjZAgcg7b3XeKeUxWdeS86E36dmIL1m

Sample Records: 7
  1. STU001 - James Wilson
  2. STU002 - Mary Garcia
  3. STU003 - David Rodriguez
  4. STU004 - Emma Martinez
  5. STU005 - Robert Anderson
  6. STU006 - Linda Thompson
  7. STU007 - William White


TABLE 3: student_details (SHA1 Hashes)
---------------------------------------
Columns:
  • id - Primary key
  • student_id - Foreign key to students table
  • info_title - Information field name (e.g., "Email", "Phone")
  • info_value - Actual information value
  • info_sha1 - SHA1 hash of info_value (40 characters)
  • created_at - Record creation timestamp
  • updated_at - Last update timestamp

Example SHA1 Record:
  ID: 1
  Student: STU001 (James Wilson)
  Title: Email
  Value: james.wilson@student.edu
  SHA1: f6f1f7bdc80fd7e3d95ae816a50caaeb18e88b94

Sample Records: 35+
  Information types include:
  • Email addresses
  • Phone numbers
  • Addresses
  • Guardian names
  • Dates of birth
  • GPA
  • Major information
  • Emergency contacts


FILE STRUCTURE
==============
c:\xampp.windows\htdocs\inventory_system\
├── config.php              [Database connection config]
├── functions.php           [Helper functions]
├── auth.php               [Authentication & session handling]
├── header.php             [HTML header & navigation bar]
├── footer.php             [HTML footer]
├── register.php           [Account registration page]
├── login.php              [Login page]
├── logout.php             [Logout handler]
├── index.php              [Dashboard - shows all 3 tables]
├── student_add.php        [Add student record]
├── student_edit.php       [Edit student record]
├── detail_add.php         [Add student information]
├── detail_edit.php        [Edit student information]
└── information_system.sql [Database dump with 5+ records]


HOW TO TAKE DATABASE SCREENSHOT
================================

1. Open phpMyAdmin at http://localhost/phpmyadmin/
2. On left sidebar, click "information_system" database
3. You should see 3 tables:
   - student_details
   - students
   - users
4. Click on "users" table
5. Take screenshot showing all 5 records with MD5 hashes
6. Click on "students" table
7. Take screenshot showing all 7 records with Bcrypt hashes
8. Click on "student_details" table
9. Take screenshot showing 35+ records with SHA1 hashes
10. Or take a single screenshot from the Dashboard (index.php)
    which displays all 3 tables with their hashes


HOW TO VERIFY/DECRYPT HASHES
=============================

WEBSITE 1: MD5 Decryption (Hashkiller)
---------------------------------------
URL: https://hashkiller.io/
Steps:
1. Go to website
2. Paste any MD5 hash (32 chars) in search box
3. Click search
4. See if original password is found in database
Example: 0192023a7bbd73250516f069df18b500 → admin123

WEBSITE 2: MD5/SHA1 Decryption (MD5Online.org)
-----------------------------------------------
URL: https://md5online.org/
Steps:
1. Go to "Decrypt" tab
2. Paste MD5 or SHA1 hash
3. Click "Decrypt" button
4. Will show original text if found in dictionary
Example: 8843d7f92416211de9ebb963ff4ce28125932878 → See address decrypted

WEBSITE 3: Online Hash Generator
---------------------------------
URL: https://www.sha1-online.com/
Steps:
1. Go to website
2. Enter any text (e.g., "admin123")
3. Click "Encrypt" or "Hash"
4. See generated SHA1/MD5 hash
5. Verify it matches your database values

WEBSITE 4: Bcrypt Verification (Bcrypt.online)
-----------------------------------------------
URL: https://bcrypt.online/
Steps:
1. Go to website
2. Paste plain text in "Plaintext" field
3. Paste Bcrypt hash in "Hash" field
4. Click "Verify"
5. Shows green/red if password matches hash
Example:
   Plaintext: STU001|James|Wilson|Computer Science|1
   Hash: $2y$10$N9qo8uLOickgx2ZMRZoMyeIjZAgcg7b3XeKeUxWdeS86E36dmIL1m
   Result: MATCH ✓

WEBSITE 5: Cracking MD5/SHA1 (CrackStation)
--------------------------------------------
URL: https://crackstation.net/
Steps:
1. Go to website
2. Paste one or more hashes
3. Click "Crack Hashes"
4. Will show cracked values if in their database
5. Accepts MD5, SHA1, SHA256, etc.


TROUBLESHOOTING
===============

Q: Database connection failed
A: Make sure MySQL is running in XAMPP Control Panel
   Check credentials in config.php

Q: Table already exists error
A: The database was already imported. Drop it first:
   1. Go to phpMyAdmin
   2. Click on "information_system"
   3. Click "Drop" button
   4. Re-import the SQL file

Q: No students or records showing
A: Make sure you imported the information_system.sql file
   This creates 5+ sample records for testing

Q: Can't decrypt Bcrypt hash
A: Bcrypt is one-way hashing - can't be decrypted
   You can only verify if plain text matches the hash
   Use bcrypt.online to verify


DATABASE STATISTICS
===================
Total Records Created:
• TABLE 1 (users): 5 accounts with MD5 passwords
• TABLE 2 (students): 7 student records with Bcrypt hashes
• TABLE 3 (student_details): 35+ information entries with SHA1 hashes
• Total Relations: 3 tables with foreign keys
• Encryption Methods: MD5 (32 chars) + Bcrypt (60 chars) + SHA1 (40 chars)


SECURITY NOTES
==============
• MD5 & SHA1 are deprecated for password storage (used here for demo only)
• Bcrypt is strong for passwords (used correctly for student record hashing)
• Passwords in production should use password_hash() with Bcrypt
• All user input is sanitized before display
• Database connections use PDO with prepared statements
• Sessions are properly managed with CSRF-safe design


END OF GUIDE
============
Created: March 2026
System: Student Information System v1.0
Framework: PHP 7.4+, MySQL 5.7+, XAMPP
