$(document).ready(function () {
    // Clear form data on page load
    $('#registerForm')[0].reset(); // Reset form fields

    // Display error messages if any from server-side
    if (window.errors) {
        for (const [field, message] of Object.entries(window.errors)) {
            $(`#${field}-error`).text(message).css('color', 'red');
        }
    }

    // Prevent form submission if there are errors
    $('#registerForm').on('submit', function (e) {
        let hasError = false;

        // Clear previous error messages
        $('.error-msg').text('');

        // Check for empty fields and validate
        if ($('#name').val().trim() === '') {
            $('#name-error').text('Name is required!').css('color', 'red');
            hasError = true;
        }

        const email = $('#email').val().trim();
        if (email === '') {
            $('#email-error').text('Email address is required!').css('color', 'red');
            hasError = true;
        } else if (!is_email(email)) {
            $('#email-error').text('Invalid email format!').css('color', 'red');
            hasError = true;
        }

        const password = $('#password').val().trim();
        if (password === '') {
            $('#password-error').text('Password cannot be blank!').css('color', 'red');
            hasError = true;
        } else if (!is_strong_password(password)) {
            $('#password-error').text('Password must be at least 8 characters, include uppercase, lowercase, digit, and symbol').css('color', 'red');
            hasError = true;
        }

        if ($('#confirm_password').val().trim() === '') {
            $('#confirm-password-error').text('Please confirm your password!').css('color', 'red');
            hasError = true;
        } else if ($('#confirm_password').val() !== password) {
            $('#confirm-password-error').text('Passwords do not match!').css('color', 'red');
            hasError = true;
        }

        // If there are errors, prevent submission
        if (hasError) {
            e.preventDefault();
        } else {
            // Assuming registration was successful, reset the form
            $('#registerForm')[0].reset(); // Reset form fields on success
        }
    });
});

// Helper function to validate email format
function is_email(email) {
    const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/; // Simple regex for email validation
    return regex.test(email);
}
