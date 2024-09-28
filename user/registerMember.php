<?php
include '/_base.php'; // Include the base file with helper functions

// Check for expired tokens and delete associated users
function cleanup_expired_tokens()
{
    global $_db;

    // Get the current time
    $current_time = date('Y-m-d H:i:s');

    // First, delete expired tokens
    $delete_tokens_stmt = $_db->prepare("
        DELETE FROM token 
        WHERE expire < ?
    ");
    $delete_tokens_stmt->execute([$current_time]);

    // Then, delete users who have no active tokens (only inactive users)
    $delete_users_stmt = $_db->prepare("
        DELETE FROM user 
        WHERE status = 'inactive' AND id NOT IN (
            SELECT DISTINCT user_id FROM token
        )
    ");
    $delete_users_stmt->execute();
}

// Call the cleanup function when loading the register page
cleanup_expired_tokens();

// Check if form is submitted
if (is_post()) {
    $name = post('name');
    $email = post('email');
    $password = post('password');
    $confirm_password = post('confirm_password');

    // Initialize error array
    $_err = [];

    // Validate user input
    if (!$name) {
        $_err['name'] = 'Name is required';
    }
    if (!is_email($email)) {
        $_err['email'] = 'Valid email is required';
    } elseif (!is_unique($email, 'user', 'email')) {
        $_err['email'] = 'Email is already taken';
    }
    if (!is_strong_password($password)) {
        $_err['password'] = 'Password must be at least 8 characters, include uppercase, lowercase, digit, and symbol';
    }
    if ($password !== $confirm_password) {
        $_err['confirm_password'] = 'Passwords do not match';
    }

    // Proceed if no errors
    if (!$_err) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert new user with 'inactive' status
        $stmt = $_db->prepare("INSERT INTO user (name, email, password, status) VALUES (?, ?, ?, 'inactive')");
        $stmt->execute([$name, $email, $hashed_password]);

        // Retrieve last inserted user ID
        $user_id = $_db->lastInsertId();

        // Generate a unique token and set expiration time
        $token = bin2hex(random_bytes(32));
        $expiry = date('Y-m-d H:i:s', strtotime('+60 seconds'));

        // Store the token and its expiry in the token table
        $token_stmt = $_db->prepare("INSERT INTO token (user_id, token, expire) VALUES (?, ?, ?)");
        $token_stmt->execute([$user_id, $token, $expiry]);

        // Send email with the activation link
        $activation_link = base("user/activate.php?token=$token");
        $subject = "Activate your account";
        $message = "Hi $name, \n\nPlease click the link below to activate your account: \n$activation_link \n\nThis link will expire within 60 seconds.";

        $mail = get_mail();
        $mail->addAddress($email);
        $mail->Subject = $subject;
        $mail->Body    = $message;

        try {
            $mail->send();
            temp('info','Registration successful! Please check your email to activate your account.');
            
        } catch (Exception $e) {
            echo "Failed to send activation email. Mailer Error: {$mail->ErrorInfo}";
        }
    }
}

function is_strong_password($password)
{
    return strlen($password) >= 8 && // Minimum 8 characters
        preg_match('/[A-Z]/', $password) && // Contain uppercase
        preg_match('/[a-z]/', $password) && // Contain lowercase
        preg_match('/[0-9]/', $password) && // Contain digit
        preg_match('/[_\W]/', $password); // Contain symbol
}

$_title = 'Register Member';
include '/_head.php';
?>
<link rel="stylesheet" type="text/css" href="/css/registerMember.css">
<link rel="stylesheet" type="text/css" href="/css/jquery-ui.css"> <!-- Optional: for jQuery UI if needed -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> <!-- Font Awesome CDN -->

<form method="POST" action="" id="registerForm">
    <h1 id="registerTitle"><?= $_title ?></h1>

    <label for="name">Name</label>
    <?php html_text('name', 'class="register-input" required'); ?>
    <div id="name-error" class="error-msg"><?= err('name'); ?></div>

    <label for="email">Email</label>
    <?php html_text('email', 'class="register-input" required'); ?>
    <div id="email-error" class="error-msg"><?= err('email'); ?></div>

    <label for="password">Password</label>
    <div class="password-toggle">
        <?php html_password('password', 'class="register-input" required id="password"'); ?>
        <span class="password-toggle-icon" id="togglePassword"><i class="icon-eye"></i></span>
    </div>
    <div id="password-error" class="error-msg"><?= err('password'); ?></div>

    <label for="confirm_password">Confirm Password</label>
    <div class="password-toggle">
        <?php html_password('confirm_password', 'class="register-input" required id="confirm_password"'); ?>
        <span class="password-toggle-icon" id="toggleConfirmPassword"><i class="icon-eye"></i></span>
    </div>
    <div id="confirm-password-error" class="error-msg"><?= err('confirm_password'); ?></div>

    <button type="submit" id="registerButton">Register</button>
</form>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="/js/register.js"></script> <!-- External jQuery file -->

<?php include '/_foot.php'; ?>