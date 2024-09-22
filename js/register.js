document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('form');
    const password = document.getElementById('password');
    const confirmPassword = document.getElementById('confirm_password');

    form.addEventListener('submit', function (e) {
        if (password.value !== confirmPassword.value) {
            alert('Passwords do not match');
            e.preventDefault(); // Stop form submission
        }
    });
});
