<?php
include '../_base.php';

// ----------------------------------------------------------------------------

$_err = [];

$_db->query('DELETE FROM token WHERE expire < NOW()');
$delete_users_stmt = $_db->prepare("
    DELETE FROM user 
    WHERE status = 'inactive' AND id NOT IN (
        SELECT DISTINCT user_id FROM token WHERE expire >= NOW()
    )
");
$delete_users_stmt->execute();

if (is_post()) {
    $email = req('email');
    $name = req('name');
    $password  = req('password');
    $confirm_password = req('confirm_password');
    $address = req('address');
    $phone = req('phone');
    $f = get_file('photo');

    // Validate: email
    if (!$email) {
        $_err['email'] = 'Required';
    } else if (strlen($email) > 100) {
        $_err['email'] = 'Maximum 100 characters';
    } else if (!is_email($email)) {
        $_err['email'] = 'Invalid email';
    } else if (!is_unique($email, 'user', 'email')) {
        $_err['email'] = 'Duplicated';
    }

    // Validate: name
    if (!$name) {
        $_err['name'] = 'Required';
    } else if (strlen($name) > 100) {
        $_err['name'] = 'Maximum 100 characters';
    }

    // Validate: password
    if (!$password) {
        $_err['password'] = 'Required';
    } else if (strlen($password) < 8 || strlen($password) > 100) {
        $_err['password'] = 'Password must be between 8-100 characters';
    } else if (!preg_match('/[A-Z]/', $password)) {
        $_err['password'] = 'Must contain at least one uppercase letter';
    } else if (!preg_match('/[a-z]/', $password)) {
        $_err['password'] = 'Must contain at least one lowercase letter';
    } else if (!preg_match('/[0-9]/', $password)) {
        $_err['password'] = 'Must contain at least one digit';
    } else if (!preg_match('/[\W_]/', $password)) {
        $_err['password'] = 'Must contain at least one special character';
    }

    // Validate: confirm_password
    if (!$confirm_password) {
        $_err['confirm_password'] = 'Required';
    } else if (strlen($confirm_password) < 5 || strlen($confirm_password) > 100) {
        $_err['confirm_password'] = 'Between 5-100 characters';
    } else if ($confirm_password != $password) {
        $_err['confirm_password'] = 'Not matched';
    }

    // Validate: photo (file)
    if (!$f) {
        // $_err['photo'] = 'Required';
    } else if (!str_starts_with($f->type, 'image/')) {
        $_err['photo'] = 'Must be image';
    } else if ($f->size > 1 * 1024 * 1024) {
        $_err['photo'] = 'Maximum 1MB';
    }

    // Validate: address
    if (!$address) {
        $_err['address'] = 'Required';
    } else if (strlen($address) > 255) {
        $_err['address'] = 'Maximum 255 characters';
    }

    // Validate: phone
    if (!$phone) {
        $_err['phone'] = 'Required';
    } else if (strlen($phone) > 15) {
        $_err['phone'] = 'Maximum 15 characters';
    }

    // DB operation
    if (!$_err) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        if (!empty($_err['photo'])) {
            // (1) Save photo
            $photo = save_photo($f, '../images/members/');
        } else {
            $photo = null;
        }

        // (2) Insert user (member)
        $stm = $_db->prepare('
            INSERT INTO user (email, name, password, role, address, phone_number, photo)
            VALUES (?, ?, ?, "Member", ?, ?, ?)
        ');
        $stm->execute([$email, $name, $hashed_password, $address, $phone, $photo]);

        // Retrieve last inserted user ID
        $user_id = $_db->lastInsertId();

        // Generate a unique token and set expiration time
        $token = bin2hex(random_bytes(32));
        $expiry = date('Y-m-d H:i:s', strtotime('+60 seconds'));

        // Store the token and its expiry in the token table
        $token_stmt = $_db->prepare("INSERT INTO token (id, user_id, expire) VALUES (?, ?, ?)");
        $token_stmt->execute([$token, $user_id, $expiry]);

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
            temp_new('info', 'Registration successful! Please check your email to activate your account.');
            redirect('/login.php');
        } catch (Exception $e) {
            temp_new('error', "Failed to send activation email. Mailer Error: {$mail->ErrorInfo}");
        }
    }
}

// ----------------------------------------------------------------------------

$_title = 'User | Register Member';
include '../_head.php';
?>
<link rel="stylesheet" type="text/css" href="/css/register.css">

<style>
    #fullscreen-video {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        z-index: -1;
    }
</style>

<video id="fullscreen-video" autoplay loop muted>
    <source src="/video/register.mp4" type="video/mp4">
</video>

<div class="form">
    <form method="post" enctype="multipart/form-data">
        <div class="Form-header" style="grid-column: span 2;">
            <h2 class="Form-title">Member Register</h2>
        </div>

        <?= html_text_new('email', 'Email Address', 'maxlength="100" placeholder="Enter your email address"') ?>
        <?= html_text_new('name', 'Name', 'maxlength="100" placeholder="Enter your name"') ?>
        <?= html_password_new('password', 'Password', 'maxlength="100" placeholder="Enter your password"') ?>
        <?= html_password_new('confirm_password', 'Confirm Password', 'maxlength="100" placeholder="Re-enter your password"') ?>
        <?= html_text_new('address', 'Address', 'maxlength="255" placeholder="Enter your address"') ?>
        <?= html_text_new('phone', 'Phone No.', 'maxlength="15" placeholder="Enter your phone number"') ?>
        <?= html_file_new('photo', 'Profile Picture', 'accept="image/members/*"') ?>

        <div class="form-group submit-btn">
            <input type="submit" value="Submit" class="submit_btn">
            <input type="reset" value="Reset" class="reset_btn">
        </div>
    </form>
</div>

<?php
include '../_foot.php';
?>