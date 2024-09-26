$(document).ready(function () {
    // Clear form data on page load
    $('#loginForm')[0].reset(); // Reset form fields

    // Display error messages
    if (window.errors) {
        if (window.errors.email) {
            $('#email-error').text(window.errors.email);
        }
        if (window.errors.password) {
            $('#password-error').text(window.errors.password);
        }
        if (window.errors.blocked) {
            alert(window.errors.blocked);
        }
    }

    // Prevent form submission if there are errors
    $('#loginForm').on('submit', function (e) {
        let hasError = false;

        // Clear previous error messages
        $('#email-error').text('');
        $('#password-error').text('');

        // Check for empty fields
        if ($('#email').val().trim() === '') {
            $('#email-error').text('Email address are required!');
            hasError = true;
        }
        if ($('#password').val().trim() === '') {
            $('#password-error').text('Password cannot be blank!');
            hasError = true;
        }

        // If there are errors, prevent submission
        if (hasError) {
            e.preventDefault();
        }
    });
});
