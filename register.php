<?php
require 'config/db.php';

function clean($data) {
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

function showResult($message, $type = 'success') {
    $alertClass = $type === 'error' ? 'alert-error' : 'alert-success';
    $icon = $type === 'error' ? '✗' : '✓';
    $buttonText = $type === 'error' ? 'Go Back' : 'Continue to Login';
    $href = $type === 'error' ? 'javascript:history.back()' : 'login.php';
    
    echo <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration - Internship Portal</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<div class="container">
    <div class="alert $alertClass" style="text-align: center; padding: 2rem;">
        <div style="font-size: 3rem; margin-bottom: 1rem;">$icon</div>
        <p style="font-size: 1.25rem; font-weight: 600; margin-bottom: 0;">$message</p>
    </div>
    <a href="$href" class="btn" style="margin-top: 1rem;">$buttonText</a>
</div>
</body>
</html>
HTML;
}

try {
    $student_id = clean($_POST['student_id'] ?? '');
    $full_name = clean($_POST['full_name'] ?? '');
    $email = clean($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $cnic = clean($_POST['cnic'] ?? '');
    $phone = clean($_POST['phone'] ?? '');
    $cgpa = isset($_POST['cgpa']) ? floatval($_POST['cgpa']) : -1;
    $department = clean($_POST['department'] ?? '');

    // server-side validation
    $errors = [];

    if (!preg_match('/^[A-Z]{2}[0-9]{2}-[A-Z]{3}-[0-9]{3}$/', $student_id)) {
        $errors[] = 'Student ID format is invalid.';
    }

    if (empty($full_name) || strlen($full_name) < 3) {
        $errors[] = 'Full name is required.';
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'A valid email address is required.';
    }

    if (strlen($password) < 8) {
        $errors[] = 'Password must be at least 8 characters.';
    }

    if ($password !== $confirm_password) {
        $errors[] = 'Passwords do not match.';
    }

    if (!preg_match('/^[0-9]{5}-[0-9]{7}-[0-9]{1}$/', $cnic)) {
        $errors[] = 'CNIC format is invalid.';
    }

    if (!preg_match('/^03[0-9]{9}$/', $phone)) {
        $errors[] = 'Phone number format is invalid.';
    }

    if ($cgpa < 0 || $cgpa > 4) {
        $errors[] = 'CGPA must be between 0 and 4.';
    }

    if (empty($department)) {
        $errors[] = 'Department is required.';
    }

    if (!isset($_FILES['resume']) || !is_uploaded_file($_FILES['resume']['tmp_name'])) {
        $errors[] = 'Resume upload is required.';
    }

    if (count($errors) > 0) {
        showResult(implode(' ', $errors), 'error');
        exit();
    }

    // Duplicate Check
    $stmt = $pdo->prepare("SELECT id FROM students WHERE student_id = ? OR email = ?");
    $stmt->execute([$student_id, $email]);
    if ($stmt->rowCount() > 0) {
        showResult("Student ID or Email already exists.", 'error');
        exit();
    }

    // Secure Password Hashing
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // File Upload Protection
    $allowed = ['application/pdf'];
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = $finfo ? finfo_file($finfo, $_FILES['resume']['tmp_name']) : '';

    if (!in_array($mime, $allowed)) {
        showResult("Invalid file type. Only PDF files are allowed.", 'error');
        exit();
    }

    if ($_FILES['resume']['size'] > 2 * 1024 * 1024) {
        showResult("File too large. Maximum size is 2MB.", 'error');
        exit();
    }

    $filename = bin2hex(random_bytes(16)) . ".pdf";
    $uploadPath = "uploads/" . $filename;

    if (!move_uploaded_file($_FILES['resume']['tmp_name'], $uploadPath)) {
        showResult("Failed to save resume. Please try again.", 'error');
        exit();
    }

    // Insert Data (prepared statement guards against SQL injection)
    $stmt = $pdo->prepare("
        INSERT INTO students 
        (student_id, full_name, email, password, cnic, phone, cgpa, department, resume_path)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");

    $stmt->execute([
        $student_id,
        $full_name,
        $email,
        $hashed_password,
        $cnic,
        $phone,
        $cgpa,
        $department,
        $uploadPath
    ]);

    showResult("Registration Successful! Your account has been created. Please log in to continue.", 'success');
} catch (Exception $e) {
    // log the error in real application
    showResult("An error occurred during registration. Please try again.", 'error');
}
?>
?>