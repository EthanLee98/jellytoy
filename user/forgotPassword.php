<?php
include '../_base.php'; // Include base functions (database, mail, etc.)

// Initialize variables for error and success messages
$_err = [];
$success = '';

// ----------------------------------------------------------------------------
// Handle POST request when the user submits the form
// ----------------------------------------------------------------------------
if (is_post()) {
    $email = post('email'); // Get email from POST request

    // Validate email
    if (!is_email($email)) {
        $_err['email'] = 'Please enter a valid email address.';
    } elseif (!is_exists($email, 'user', 'email')) { // Check if email exists in the database
        $_err['email'] = 'Email does not exist.';
    } else {
        // Generate password reset token and expiry (e.g., 15 minutes)
        $token = bin2hex(random_bytes(32));
        $expiry = date('Y-m-d H:i:s', strtotime('+15 minutes'));

        // Insert or update the reset token in the database
        $stm = $_db->prepare("INSERT INTO password_resets (email, token, expires_at) VALUES (?, ?, ?)
                              ON DUPLICATE KEY UPDATE token = ?, expires_at = ?");
        $stm->execute([$email, $token, $expiry, $token, $expiry]);

        // Prepare reset password email
        $reset_link = base("jellytoy/user/resetPassword.php?token=$token");
        $mail = get_mail();
        $mail->addAddress($email);
        $mail->Subject = 'Reset your password';
        $mail->Body = "Click on the link below to reset your password:\n\n$reset_link\n\nThis link will expire in 15 minutes.";

        // Attempt to send the email
        if ($mail->send()) {
            $success = 'A password reset link has been sent to your email.';
        } else {
            $_err['email'] = 'Failed to send reset password email. Please try again later.';
        }
    }
}

// ----------------------------------------------------------------------------
// Page setup and form display
// ----------------------------------------------------------------------------
$_title = 'User | Forgot Password';
include '../_head.php'; // Include header file
?>

<!-- HTML form for email input -->
<div class="container">
    <h2>Forgot Password</h2>

    <!-- Display success message if email is sent -->
    <?php if (!empty($success)): ?>
        <p class="success"><?php echo $success; ?></p>
    <?php endif; ?>

    <!-- Forgot password form -->
    <form id="forgotPasswordForm" method="post">
        <div>
            <label for="email">Enter your registered email address:</label>
            <input type="email" id="email" name="email" required value="<?php echo htmlspecialchars(post('email')); ?>">
            <span id="emailError" class="err"><?php echo isset($_err['email']) ? $_err['email'] : ''; ?></span>
        </div>
        <div>
            <button type="submit" id="submitBtn">Submit</button>
        </div>
    </form>
</div>

<!-- jQuery for error display and form validation -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    $('#forgotPasswordForm').on('submit', function(event) {
        $('#emailError').text(''); // Clear previous errors

        var email = $('#email').val();
        var emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;

        // Simple email validation using regex
        if (!emailPattern.test(email)) {
            event.preventDefault(); // Prevent form submission
            $('#emailError').text('Please enter a valid email address.');
        } else {
            // Disable the button to prevent multiple submissions
            $('#submitBtn').prop('disabled', true);
        }
    });
});
</script>

<?php
include '../_foot.php'; // Include footer file
?>
