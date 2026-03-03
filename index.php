<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Internship Registration Portal</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>

<div class="container">
    <h2>Secure Internship Registration</h2>
    <p style="text-align: center; color: #718096; margin-bottom: 2rem;">Join our internship program and kickstart your career</p>

    <form id="regForm" action="register.php" method="POST" enctype="multipart/form-data">

        <div class="form-group">
            <label for="student_id">Student ID</label>
            <input type="text" name="student_id" id="student_id" placeholder="B23F000SE000" required>
        </div>

        <div class="form-group">
            <label for="full_name">Full Name</label>
            <input type="text" name="full_name" id="full_name" placeholder="Shayan Ullah" required>
        </div>

        <div class="form-group">
            <label for="email">Email Address</label>
            <input type="email" name="email" id="email" placeholder="your.email@paf-iast.edu.pk" required>
            <small id="emailStatus"></small>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" placeholder="••••••••" required>
            </div>
            <div class="form-group">
                <label for="confirm_password">Confirm Password</label>
                <input type="password" name="confirm_password" id="confirm_password" placeholder="••••••••" required>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="cnic">CNIC</label>
                <input type="text" name="cnic" id="cnic" placeholder="12345-1234567-1" required>
            </div>
            <div class="form-group">
                <label for="phone">Phone Number</label>
                <input type="text" name="phone" id="phone" placeholder="03**-*******" required>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="cgpa">CGPA</label>
                <input type="number" step="0.01" name="cgpa" id="cgpa" placeholder="3.50" min="0" max="4" required>
            </div>
            <div class="form-group">
                <label for="department">Department</label>
                <input type="text" name="department" id="department" placeholder="Computer Science" required>
            </div>
        </div>

        <div class="form-group">
            <label for="resume">Upload Resume (PDF)</label>
            <input type="file" name="resume" id="resume" accept=".pdf" required>
        </div>

        <button type="submit" class="btn">Register Now</button>
    </form>

    <div class="link-text">
        Already have an account? <a href="login.php">Login here</a>
    </div>
</div>

<script src="assets/validation.js"></script>
</body>
</html>