<?php
session_start();

if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Internship Portal</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>

<header>
    <div class="nav-container">
        <a href="dashboard.php" class="logo">🎓 Internship Portal</a>
        <nav>
            <a href="dashboard.php">Dashboard</a>
            <a href="logout.php">Logout</a>
        </nav>
    </div>
</header>

<div class="container">
    <div class="dashboard-header">
        <h2 style="border: none; color: white;">Welcome Back!</h2>
        <p class="welcome-message"><?php echo htmlspecialchars($_SESSION['full_name']); ?></p>
    </div>

    <div class="card">
        <h3>Your Profile</h3>
        <p><strong>Student ID:</strong> <?php echo htmlspecialchars($_SESSION['student_id']); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($_SESSION['email']); ?></p>
        <p style="color: #48bb78; font-weight: 600; margin-top: 1.5rem;">✓ You are successfully logged in.</p>
    </div>

    <div class="card text-center">
        <p style="color: #718096; margin-bottom: 1.5rem;">Ready to explore your internship opportunities?</p>
        <a href="logout.php" class="btn btn-danger">Logout</a>
    </div>
</div>

</body>
</html>