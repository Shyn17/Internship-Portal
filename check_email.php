<?php
header('Content-Type: application/json');
require 'config/db.php';

$email = isset($_GET['email']) ? filter_var($_GET['email'], FILTER_SANITIZE_EMAIL) : '';

if ($email && filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $stmt = $pdo->prepare("SELECT id FROM students WHERE email = ?");
    $stmt->execute([$email]);

    if ($stmt->rowCount() > 0) {
        echo json_encode([
            'status' => 'exists',
            'message' => 'Email already exists',
            'html' => "<span style='color: #c53030; font-weight: 500;'>✗ Email already exists</span>"
        ]);
    } else {
        echo json_encode([
            'status' => 'available',
            'message' => 'Email available',
            'html' => "<span style='color: #22543d; font-weight: 500;'>✓ Email available</span>"
        ]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid or missing email']);
}
?>