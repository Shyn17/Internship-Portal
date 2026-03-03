<?php
session_start();
require 'config/db.php';

// Clean helper
function clean($data) {
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

$email = isset($_POST['email']) ? clean($_POST['email']) : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';

// Basic server-side validation
if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['error'] = 'Please provide a valid email address.';
    header('Location: login.php');
    exit();
}

if (empty($password)) {
    $_SESSION['error'] = 'Password cannot be empty.';
    header('Location: login.php');
    exit();
}

// Prepared statement prevents SQL injection
$stmt = $pdo->prepare("SELECT * FROM students WHERE email = ?");
$stmt->execute([$email]);

$user = $stmt->fetch();

if ($user && password_verify($password, $user['password'])) {
    // store only safe values in session
    $_SESSION['student_id'] = $user['student_id'];
    $_SESSION['full_name'] = $user['full_name'];
    $_SESSION['email'] = $user['email'];

    header('Location: dashboard.php');
    exit();
} else {
    // generic error to avoid user enumeration
    $_SESSION['error'] = 'Invalid email or password';
    header('Location: login.php');
    exit();
}
?>