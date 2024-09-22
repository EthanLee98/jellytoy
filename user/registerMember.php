<?php
include '../_base.php'; // Include the base file with helper functions

// Check for expired tokens and delete associated users
function cleanup_expired_tokens() {
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
    if (strlen($password) < 6) {
        $_err['password'] = 'Password must be at least 6 characters';
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
        $expiry = date('Y-m-d H:i:s', strtotime('+30 seconds'));

        // Store the token and its expiry in the token table
        $token_stmt = $_db->prepare("INSERT INTO token (user_id, token, expire) VALUES (?, ?, ?)");
        $token_stmt->execute([$user_id, $token, $expiry]);

        // Send email with the activation link
        $activation_link = base("jellytoy/user/activate.php?token=$token");
        $subject = "Activate your account";
        $message = "Hi $name, \n\nPlease click the link below to activate your account: \n$activation_link \n\nThis link will expire in 30 seconds.";

        $mail = get_mail();
        $mail->addAddress($email);
        $mail->Subject = $subject;
        $mail->Body    = $message;

        try {
            $mail->send();
            echo "Registration successful! Please check your email to activate your account.";
        } catch (Exception $e) {
            echo "Failed to send activation email. Mailer Error: {$mail->ErrorInfo}";
        }
    }
}

$_title = 'Register Member';
include '../_head.php';
?>

<form method="POST" action="">
    <h2>Register</h2>
    <label for="name">Name</label>
    <?php html_text('name', 'required'); ?>
    <?php err('name'); ?>

    <label for="email">Email</label>
    <?php html_text('email', 'required'); ?>
    <?php err('email'); ?>

    <label for="password">Password</label>
    <?php html_password('password', 'required'); ?>
    <?php err('password'); ?>

    <label for="confirm_password">Confirm Password</label>
    <?php html_password('confirm_password', 'required'); ?>
    <?php err('confirm_password'); ?>

    <button type="submit">Register</button>
</form>

<?php include '../_foot.php'; ?>
