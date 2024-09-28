<?php
include '/_base.php'; // Include base functions (database, utilities, etc.)

// Initialize variables for error and success messages
$_err = [];
$success = '';
$token = get('token'); // Get the token from URL

// ----------------------------------------------------------------------------
// Validate the token and handle password reset
// ----------------------------------------------------------------------------
if ($token) {
    // Fetch the reset token data from the database
    $stm = $_db->prepare("SELECT * FROM password_resets WHERE token = ? AND expires_at > NOW()");
    $stm->execute([$token]);
    $reset = $stm->fetch(PDO::FETCH_OBJ); // Fetch as object (stdClass)

    if (!$reset) {
        $_err['token'] = 'The password reset link is invalid or expired.';
    } elseif (is_post()) {
        // Handle POST request for updating the password
        $password = post('password');
        $confirm_password = post('confirm_password');

        // Validate password fields
        if (empty($password)) {
            $_err['password'] = 'Please enter a new password.';
        } elseif (strlen($password) < 8) {
            $_err['password'] = 'Password must be at least 8 characters long.';
        }

        if ($password !== $confirm_password) {
            $_err['confirm_password'] = 'Passwords do not match.';
        }

        // If there are no errors, update the password in the database
        if (empty($_err)) {
            // Hash the new password
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);

            // Update the user's password in the 'user' table
            $update_user = $_db->prepare("UPDATE user SET password = ? WHERE email = ?");
            $update_user->execute([$hashed_password, $reset->email]); // Access email via object notation

            // Delete the reset token once the password is successfully updated
            $delete_token = $_db->prepare("DELETE FROM password_resets WHERE token = ?");
            $delete_token->execute([$token]);

            // Display success message and prevent further processing
            $success = 'Your password has been successfully updated.';
        }
    }
} else {
    $_err['token'] = 'Invalid password reset request.';
}

// ----------------------------------------------------------------------------
// Page setup and form display
// ----------------------------------------------------------------------------
$_title = 'User | Reset Password';
include '/_head.php'; // Include header file
?>

<link rel="stylesheet" href="/css/resetPassword.css">

<div class="reset-password-container">

    <!-- Display success message -->
    <?php if (!empty($success)): ?>
        <p class="reset-password-success"><?php echo $success; ?></p>
    <?php else: ?>

        <!-- Display errors -->
        <?php if (!empty($_err['token'])): ?>
            <p class="reset-password-error"><?php echo $_err['token']; ?></p>
        <?php endif; ?>

        <!-- Reset Password Form -->
        <?php if (empty($_err['token'])): ?>
            <form id="resetPasswordForm" method="post">
                <h1 id="resetPasswordTitle"><?= $_title ?></h1>
                <div>
                    <label for="resetPasswordInput">New Password:</label>
                    <input type="password" id="resetPasswordInput" class="reset-password-input" name="password" required>
                    <span id="resetPasswordError" class="reset-password-error"><?php echo isset($_err['password']) ? $_err['password'] : ''; ?></span>
                </div>
                <div>
                    <label for="resetConfirmPasswordInput">Confirm Password:</label>
                    <input type="password" id="resetConfirmPasswordInput" class="reset-password-input" name="confirm_password" required>
                    <span id="resetConfirmPasswordError" class="reset-password-error"><?php echo isset($_err['confirm_password']) ? $_err['confirm_password'] : ''; ?></span>
                </div>
                <div>
                    <button type="submit" id="resetPasswordSubmitBtn">Reset Password</button>
                </div>
            </form>
        <?php endif; ?>

    <?php endif; ?>
</div>

<!-- jQuery for validation and form handling -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('#resetPasswordForm').on('submit', function(event) {
            $('#resetPasswordError').text(''); // Clear previous errors
            $('#resetConfirmPasswordError').text(''); // Clear previous errors

            var password = $('#resetPasswordInput').val();
            var confirm_password = $('#resetConfirmPasswordInput').val();

            // Password validation: Check length
            if (password.length < 8) {
                event.preventDefault(); // Prevent form submission
                $('#resetPasswordError').text('Password must be at least 8 characters long.');
            }

            // Check if passwords match
            if (password !== confirm_password) {
                event.preventDefault(); // Prevent form submission
                $('#resetConfirmPasswordError').text('Passwords do not match.');
            }
        });
    });
</script>

<?php
include '/_foot.php'; // Include footer file
?>