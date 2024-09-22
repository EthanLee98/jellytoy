<?php
include '../_base.php'; // Including base.php with necessary functions

// If form is submitted, handle registration
if (is_post()) {
    $name = post('name');
    $email = post('email');
    $password = post('password');
    $confirm_password = post('confirm_password');

    // Initialize error array
    $_err = [];

    // Simple validation
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

    // If no errors, proceed with registration
    if (!$_err) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert new user with inactive status
        $stmt = $_db->prepare("INSERT INTO user (name, email, password, status, role) VALUES (?, ?, ?, 'inactive', 'Member')");
        $stmt->execute([$name, $email, $hashed_password]);

        // Get the user ID for the inserted user
        $user_id = $_db->lastInsertId();

        // Generate a unique token for email verification
        $token = bin2hex(random_bytes(32)); // Create a 64-character random token
        $expiry = date('Y-m-d H:i:s', strtotime('+1 day')); // Set token expiry for 24 hours

        // Insert the token into the token table
        $token_stmt = $_db->prepare("INSERT INTO token (user_id, token, expire) VALUES (?, ?, ?)");
        $token_stmt->execute([$user_id, $token, $expiry]);

        // Send email with activation link
        require '../lib/PHPMailer.php';
        require '../lib/SMTP.php';

        $activation_link = base("activate.php?token=$token"); // Create activation link
        $subject = "Activate your account";
        $message = "Hi $name,<br><br>Please click the link below to activate your account:<br>$activation_link<br><br>This link will expire in 24 hours.";

        $m = new PHPMailer(true); // Create a new PHPMailer instance
        try {
            // Server settings
            $m->isSMTP(); // Set mailer to use SMTP
            $m->Host = 'smtp.gmail.com'; // Specify main and backup SMTP servers
            $m->SMTPAuth = true; // Enable SMTP authentication
            $m->Username = 'your-email@gmail.com'; // Your SMTP username
            $m->Password = 'your-email-password'; // Your SMTP password or app password
            $m->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Enable TLS encryption
            $m->Port = 587; // TCP port to connect to

            // Recipients
            $m->setFrom('your-email@gmail.com', 'Admin'); // Set the sender's email and name
            $m->addAddress($email); // Add a recipient

            // Content
            $m->isHTML(true); // Set email format to HTML
            $m->Subject = $subject;
            $m->Body    = $message; // Use the message created earlier
            $m->AltBody = strip_tags($message); // Provide a plain-text version of the email

            $m->send(); // Send the email
            echo "Registration successful! Please check your email to activate your account.";
        } catch (Exception $e) {
            echo "Failed to send activation email. Mailer Error: {$m->ErrorInfo}";
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