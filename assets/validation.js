// Form Validation
document.getElementById("regForm").addEventListener("submit", function(e) {

    const studentPattern = /^[A-Z]{2}[0-9]{2}-[A-Z]{3}-[0-9]{3}$/;
    const cnicPattern = /^[0-9]{5}-[0-9]{7}-[0-9]{1}$/;
    const phonePattern = /^03[0-9]{9}$/;
    const passwordPattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W]).{8,}$/;

    const studentID = document.getElementById("student_id").value;
    const password = document.getElementById("password").value;
    const confirmPassword = document.getElementById("confirm_password").value;
    const cgpa = parseFloat(document.getElementById("cgpa").value);
    const cnic = document.getElementById("cnic").value;
    const phone = document.getElementById("phone").value;
    const resume = document.getElementById("resume").files[0];

    let errors = [];

    if (!studentID) {
        errors.push("Student ID is required");
    } else if (!studentPattern.test(studentID)) {
        errors.push("Invalid Student ID Format (e.g., FA21-BCS-001)");
    }

    if (!password) {
        errors.push("Password is required");
    } else if (!passwordPattern.test(password)) {
        errors.push("Password must be at least 8 characters with uppercase, lowercase, number, and special character");
    }

    if (password !== confirmPassword) {
        errors.push("Passwords do not match");
    }

    if (cgpa < 0 || cgpa > 4) {
        errors.push("CGPA must be between 0 and 4");
    }

    if (!cnic) {
        errors.push("CNIC is required");
    } else if (!cnicPattern.test(cnic)) {
        errors.push("Invalid CNIC Format (e.g., 12345-1234567-1)");
    }

    if (!phone) {
        errors.push("Phone number is required");
    } else if (!phonePattern.test(phone)) {
        errors.push("Invalid Phone Number (must start with 03 and be 11 digits)");
    }

    if (!resume) {
        errors.push("Resume is required");
    } else if (resume.type !== "application/pdf") {
        errors.push("Resume must be a PDF file");
    } else if (resume.size > 2 * 1024 * 1024) {
        errors.push("Resume file is too large (maximum 2MB)");
    }

    if (errors.length > 0) {
        e.preventDefault();
        showValidationErrors(errors);
    }
});

// Show validation errors
function showValidationErrors(errors) {
    const errorMessage = errors.join("\n");
    alert("Please fix the following errors:\n\n" + errorMessage);
}

// AJAX Email Check with JSON response
document.getElementById("email").addEventListener("blur", function() {
    const email = this.value.trim();
    const emailStatus = document.getElementById("emailStatus");

    if (!email) {
        emailStatus.innerHTML = "";
        return;
    }

    fetch("check_email.php?email=" + encodeURIComponent(email))
        .then(response => response.json())
        .then(data => {
            if (data.status === 'exists') {
                emailStatus.innerHTML = data.html;
                emailStatus.style.color = '#c53030';
                emailStatus.style.fontSize = '0.875rem';
            } else if (data.status === 'available') {
                emailStatus.innerHTML = data.html;
                emailStatus.style.color = '#22543d';
                emailStatus.style.fontSize = '0.875rem';
            }
        })
        .catch(error => {
            console.error('Error checking email:', error);
        });
});

// Password strength indicator
document.getElementById("password").addEventListener("input", function() {
    const password = this.value;
    const strength = calculatePasswordStrength(password);

    // Optional: You can add a visual strength indicator here
    console.log("Password strength:", strength);
});

function calculatePasswordStrength(password) {
    let strength = 0;

    if (password.length >= 8) strength++;
    if (/[a-z]/.test(password)) strength++;
    if (/[A-Z]/.test(password)) strength++;
    if (/[0-9]/.test(password)) strength++;
    if (/[\W]/.test(password)) strength++;

    return strength;
}

// Real-time validation feedback
function setupRealtimeValidation() {
    const fields = {
        'student_id': /^[A-Z]{2}[0-9]{2}-[A-Z]{3}-[0-9]{3}$/,
        'cnic': /^[0-9]{5}-[0-9]{7}-[0-9]{1}$/,
        'phone': /^03[0-9]{9}$/,
        'cgpa': null // Custom validation
    };

    for (const [fieldId, pattern] of Object.entries(fields)) {
        const field = document.getElementById(fieldId);
        if (field) {
            field.addEventListener("blur", function() {
                if (fieldId === 'cgpa') {
                    const value = parseFloat(this.value);
                    if (value < 0 || value > 4) {
                        this.style.borderColor = '#f56565';
                    } else {
                        this.style.borderColor = '#48bb78';
                    }
                } else if (pattern) {
                    if (this.value && !pattern.test(this.value)) {
                        this.style.borderColor = '#f56565';
                    } else if (this.value) {
                        this.style.borderColor = '#48bb78';
                    } else {
                        this.style.borderColor = '#e2e8f0';
                    }
                }
            });
        }
    }
}

// Initialize real-time validation when DOM is ready
document.addEventListener("DOMContentLoaded", setupRealtimeValidation);