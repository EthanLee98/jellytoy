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
    } else {
        // Check if email exists in the database and if the status is active
        $stm = $_db->prepare("SELECT status FROM user WHERE email = ?");
        $stm->execute([$email]);
        $user = $stm->fetch();

        if (!$user) {
            $_err['email'] = 'Email does not exist.';
        } elseif ($user->status !== 'active') {
            $_err['email'] = 'Your account is not active. Please contact support.';
        } else {
            // Check if there's already a valid (not expired) token for this email
            $stm = $_db->prepare("SELECT token, expires_at FROM password_resets WHERE email = ? AND expires_at > NOW()");
            $stm->execute([$email]);
            $result = $stm->fetch();

            if ($result) {
                // If a valid token exists, prevent resending the email
                temp_new('info', 'A password reset link has already been sent to your email and is still active.');
                $success = 'A password reset link has already been sent to your email and is still active.';
            } else {
                // Generate password reset token and expiry (e.g., 15 minutes)
                $token = bin2hex(random_bytes(32));
                $expiry = date('Y-m-d H:i:s', strtotime('+3 minutes'));

                // Insert or update the reset token in the database
                $stm = $_db->prepare("INSERT INTO password_resets (email, token, expires_at) VALUES (?, ?, ?)
                                      ON DUPLICATE KEY UPDATE token = ?, expires_at = ?");
                $stm->execute([$email, $token, $expiry, $token, $expiry]);

                // Prepare reset password email
                $reset_link = base("user/resetPassword.php?token=$token");
                $mail = get_mail();
                $mail->addAddress($email);
                $mail->Subject = 'Reset your password';
                $mail->Body = "Click on the link below to reset your password:\n\n$reset_link\n\nThis link will expire in 3 minutes.";

                // Attempt to send the email
                if ($mail->send()) {
                    $success = 'A password reset link has been sent to your email.';
                    temp_new('info', 'A password reset link has been sent to your email.');
                } else {
                    $_err['email'] = 'Failed to send reset password email. Please try again later.';
                }
            }
        }
    }
}

// ----------------------------------------------------------------------------
// Page setup and form display
// ----------------------------------------------------------------------------
$_title = 'User | Forgot Password';
include '../_head.php'; // Include header file
?>

<link rel="stylesheet" href="/css/forgotPassword.css">

<!-- HTML form for email input -->
<div class="container">
    <div class="forgot-password-container">
        <h2 id="forgotPasswordTitle">Forgot Password</h2>

        <!-- Display success message if email is sent -->
        <?php if (!empty($success)): ?>
            <p class="forgot-password-success"><?php echo $success; ?></p>
        <?php endif; ?>

        <!-- Forgot password form -->
        <form id="forgotPasswordForm" method="post">
            <div>
                <label for="forgotEmailInput">Enter your registered email address:</label>
                <input type="email" id="forgotEmailInput" class="forgot-password-input" name="email" required>
                <span id="forgotEmailError" class="forgot-password-error"><?php echo isset($_err['email']) ? $_err['email'] : ''; ?></span>
            </div>
            <div>
                <button type="submit" id="forgotPasswordSubmitBtn">Submit</button>
            </div>
        </form>
    </div>
</div>

<!-- jQuery for error display and form validation -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {

        // Reset form fields on page load
        $('#forgotPasswordForm')[0].reset();

        $('#forgotPasswordForm').on('submit', function(event) {
            $('#forgotEmailError').text(''); // Clear previous errors

            var email = $('#forgotEmailInput').val(); // Get the correct email field
            var emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;

            // Simple email validation using regex
            if (!emailPattern.test(email)) {
                event.preventDefault(); // Prevent form submission
                $('#forgotEmailError').text('Please enter a valid email address.');
            } else {
                // Disable the button to prevent multiple submissions
                $('#forgotPasswordSubmitBtn').prop('disabled', true); // Use the correct ID for the submit button
            }
        });
    });
</script>

<?php
include '../_foot.php'; // Include footer file
?>