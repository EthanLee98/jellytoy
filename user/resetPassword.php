<?php
include '../_base.php'; // Include base functions (database, utilities, etc.)

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
include '../_head.php'; // Include header file
?>

<div class="container">
    <h2>Reset Password</h2>

    <!-- Display success message -->
    <?php if (!empty($success)): ?>
        <p class="success"><?php echo $success; ?></p>
    <?php else: ?>

        <!-- Display errors -->
        <?php if (!empty($_err['token'])): ?>
            <p class="err"><?php echo $_err['token']; ?></p>
        <?php endif; ?>

        <!-- Reset Password Form -->
        <?php if (empty($_err['token'])): ?>
            <form id="resetPasswordForm" method="post">
                <div>
                    <label for="password">New Password:</label>
                    <input type="password" id="password" name="password" required>
                    <span id="passwordError" class="err"><?php echo isset($_err['password']) ? $_err['password'] : ''; ?></span>
                </div>
                <div>
                    <label for="confirm_password">Confirm Password:</label>
                    <input type="password" id="confirm_password" name="confirm_password" required>
                    <span id="confirmPasswordError" class="err"><?php echo isset($_err['confirm_password']) ? $_err['confirm_password'] : ''; ?></span>
                </div>
                <div>
                    <button type="submit">Reset Password</button>
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
        $('#passwordError').text(''); // Clear previous errors
        $('#confirmPasswordError').text(''); // Clear previous errors

        var password = $('#password').val();
        var confirm_password = $('#confirm_password').val();

        // Password validation: Check length
        if (password.length < 8) {
            event.preventDefault(); // Prevent form submission
            $('#passwordError').text('Password must be at least 8 characters long.');
        }

        // Check if passwords match
        if (password !== confirm_password) {
            event.preventDefault(); // Prevent form submission
            $('#confirmPasswordError').text('Passwords do not match.');
        }
    });
});
</script>

<?php
include '../_foot.php'; // Include footer file
?>
