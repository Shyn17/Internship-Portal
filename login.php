<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Login - Internship Portal</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>

<div class="container">
    <h2>Student Login</h2>
    <p style="text-align: center; color: #718096; margin-bottom: 2rem;">Access your internship dashboard</p>

    <?php
    if (isset($_SESSION['error'])) {
        echo "<div class='alert alert-error'>" . htmlspecialchars($_SESSION['error']) . "</div>";
        unset($_SESSION['error']);
    }
    ?>

    <form action="login_process.php" method="POST">
        <div class="form-group">
            <label for="email">Email Address</label>
            <input type="email" name="email" id="email" placeholder="your.email@example.com" required>
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" name="password" id="password" placeholder="••••••••" required>
        </div>

        <button type="submit" class="btn">Login</button>
    </form>

    <div class="link-text">
        Don't have an account? <a href="index.php">Register here</a>
    </div>
</div>

</body>
</html>