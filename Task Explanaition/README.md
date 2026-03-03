# Internship Registration Portal - Assignment 2

## Overview
This project implements a secure registration portal using HTML, JavaScript, PHP, and MySQL. It includes strong client-side validation, server-side validation, database integrity, and security against common web attacks.

---

## Part A – Client Side Validation
All rules are enforced in `js/validation.js`:
1. Student ID pattern `FA21-BCS-001`.
2. Email format check via regex.
3. Password strength:
   - Minimum 8 characters
   - At least one uppercase
   - One lowercase
   - One digit
   - One special character
4. CGPA between 0.00 and 4.00.
5. CNIC format `12345-1234567-1`.
6. Phone format `03XXXXXXXXX`.
7. Resume must be PDF and &lt;2MB.
8. AJAX email availability (see `php/check_email.php`).

The form is in `index.html` and attaches validation functions on submission.

---

## Part B – Server Side Validation
Implemented in `php/register.php`:
- Re-checks all client rules with PHP regex/filters.
- Uses prepared statements (`PDO`) to avoid SQL injection.
- Passwords hashed with `password_hash`.
- File upload restrictions: only PDFs, size limit, stored in `php/uploads` with sanitized name and random prefix.
- Output is escaped using `htmlspecialchars`.
- Before insert, it checks for duplicate student ID or email.

`php/db_config.php` sets up a secure database connection and enables exceptions.

---

## Part C – Database Design
Schema in `db/schema.sql` contains:
- `students` table with primary key `id`.
- Unique constraints on `student_id` and `email`.
- Appropriate data types for each field.
- `CHECK` constraint for CGPA range.
- `resume_path` stored as varchar.
- Index on `cnic`.

---
#
# PART D - Security Testing 

# 1. What happens if JavaScript is disabled? 
If JavaScript is disabled in the user's browser, all client-side validation (such as real-time regex checking and the AJAX email availability check) is entirely bypassed. However, the system remains secure due to a defense-in-depth approach. When the unvalidated form is submitted, the backend (register.php) catches the request and re-evaluates every single field using strict PHP validation functions (such as preg_match and filter_var). If any data is invalid, the server rejects the submission and returns the appropriate error messages, ensuring no malformed data ever reaches the database.

# 2. How SQL injection is prevented? 
SQL Injection is prevented through the strict use of PHP Data Objects (PDO) Prepared Statements. Instead of concatenating user input directly into SQL query strings, the system prepares the database query structure first and then binds the user inputs as separate parameters (e.g., $stmt->execute([$email])). This ensures that the MySQL database treats the user input purely as literal string data rather than executable code, completely neutralizing any malicious SQL commands injected through the form fields.

# 3. How XSS is prevented? 
Cross-Site Scripting (XSS) is mitigated by sanitizing all user-supplied data before it is processed or displayed back to the user. The system utilizes a custom clean() function that wraps PHP's native htmlspecialchars() function. This converts special characters (like < and >) into safe HTML entities. Consequently, if an attacker attempts to input a malicious <script> tag, it is safely rendered as harmless text on the screen rather than being executed by the browser.

# 4. What happens if someone uploads a .php file renamed as .pdf? 
The system defends against this file upload exploit using a rigorous two-step verification process. First, it does not rely on the user-provided file extension; instead, it uses finfo_file() to deeply inspect the internal MIME type of the uploaded file. A .php file disguised as a .pdf will reveal its true MIME type and be immediately rejected. Second, the system discards the original filename entirely. It generates a secure, randomized string (bin2hex(random_bytes(16))) and explicitly appends the .pdf extension before saving it to the server, removing any possibility of the file being executed as a PHP script