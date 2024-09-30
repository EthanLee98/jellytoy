<?php
include '_base.php';

// ----------------------------------------------------------------------------

if (is_post()) {
    $email = req('email');
    $password = req('password');

    // Initialize error array
    $_err = [];

    // Validate: email
    if ($email == '') {
        $_err['email'] = 'Required';
    } else if (!is_email($email)) {
        $_err['email'] = 'Invalid email';
    }

    // Validate: password
    if ($password == '') {
        $_err['password'] = 'Required';
    }

    // Check if the user is blocked
    if (!$_err) {
        $stm = $_db->prepare('
            SELECT * FROM user WHERE email = ?
        ');
        $stm->execute([$email]);
        $u = $stm->fetch(PDO::FETCH_ASSOC);

        if ($u) {
            if ($u['status'] === 'inactive') {
                temp_new('error', 'Account inactive. Kindly check your email for verification link.');
            } else {
                if ($u['status'] === 'blocked') {
                    if (time() < strtotime($u['blocked_until'])) {
                        $isBlocked = true;
                        $blockedUntil = date('Y-m-d H:i:s', strtotime($u['blocked_until']));
                        temp_new('error', 'Your account is blocked. Please try again after ' . $blockedUntil . '.');
                    } else {
                        $update_stmt = $_db->prepare('UPDATE user SET status = "Active", blocked_until = NULL WHERE email = ?');
                        $update_stmt->execute([$email]);
                    }
                }

                if (!isset($isBlocked)) {
                    if (password_verify($password, $u['password'])) {
                        temp_new('success', 'Login successfully');
                        login($u);
                    } else {
                        $failed_attempts = $u['login_attempts'] + 1;

                        if ($failed_attempts >= 3) {
                            $blocked_until = date('Y-m-d H:i:s', strtotime('+30 seconds'));
                            $update_stmt = $_db->prepare('UPDATE user SET status = "blocked", blocked_until = ?, login_attempts = 0 WHERE email = ?');
                            $update_stmt->execute([$blocked_until, $email]);
                            temp_new('warning', 'Your account has been temporarily blocked due to too many failed login attempts. Please try again in 30 seconds.');
                        } else {
                            $update_stmt = $_db->prepare('UPDATE user SET login_attempts = ? WHERE email = ?');
                            $update_stmt->execute([$failed_attempts, $email]);
                            temp_new('info', 'Either Email Address or Password is Incorrect.');
                        }
                    }
                }
            }
        } else {
            temp_new('info', 'Email address not found.');
        }
    }
}

// ----------------------------------------------------------------------------

$_title = 'Login';
include '_head.php';
?>

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
<link rel="stylesheet" type="text/css" href="/css/login.css">

<video id="fullscreen-video" autoplay loop muted>
    <source src="/video/login.mp4" type="video/mp4">
</video>

<div class="form">
    <form method="post" id="login_form" enctype="multipart/form-data">
        <div class="Form-header">
            <h2 class="Form-title">Member Login</h2>
        </div>

        <?= html_text_new('email', 'Email Address', 'maxlength="100" placeholder="Enter your email address"') ?>
        <?= html_password_new('password', 'Password', 'maxlength="100" placeholder="Enter your password"') ?>

        <input type="submit" value="Login" class="submit_btn">
        <input type="reset" value="Reset" class="reset_btn">
        <div class="form-group text-center">
            <a href="/user/forgotPassword.php" class="forgot-password">Forgot Password?</a>
        </div>
    </form>
</div>

<?php
include '_foot.php';
?>

<script src="js/login.js"></script>