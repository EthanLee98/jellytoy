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
            SELECT * FROM user WHERE email = ? AND status = "active"
        ');
        $stm->execute([$email]);
        $u = $stm->fetch(PDO::FETCH_ASSOC);

        if ($u) {
            // Check if the user is blocked
            if ($u['isblocked'] === 'yes') {
                // Check if the block duration has expired
                if (time() < strtotime($u['blocked_until'])) {
                    $_err['blocked'] = 'Your account is temporarily blocked. Please try again later.';
                } else {
                    // Reset the block status
                    $update_stmt = $_db->prepare('UPDATE user SET isblocked = "no", blocked_until = NULL WHERE email = ?');
                    $update_stmt->execute([$email]);
                }
            }

            if (!isset($_err['blocked'])) {
                // Verify password using password_verify
                if (password_verify($password, $u['password'])) {
                    // Password is correct, proceed with login
                    temp('info', 'Login successfully');
                    login($u);
                } else {
                    // Increment failed attempts
                    $failed_attempts = $u['login_attempts'] + 1;

                    // Check if the user should be blocked
                    if ($failed_attempts >= 3) {
                        $blocked_until = date('Y-m-d H:i:s', strtotime('+30 seconds'));
                        $update_stmt = $_db->prepare('UPDATE user SET isblocked = "yes", blocked_until = ?, login_attempts = 0 WHERE email = ?');
                        $update_stmt->execute([$blocked_until, $email]);
                        $_err['blocked'] = 'Your account has been temporarily blocked due to too many failed login attempts. Please try again in 30 seconds.';
                    } else {
                        // Update failed attempts
                        $update_stmt = $_db->prepare('UPDATE user SET login_attempts = ? WHERE email = ?');
                        $update_stmt->execute([$failed_attempts, $email]);
                        $_err['password'] = 'Not matched';
                    }
                }
            }
        } else {
            $_err['email'] = 'Email not registered or inactive.';
        }
    }
}

// ----------------------------------------------------------------------------

$_title = 'Login';
include '_head.php';
?>

<form method="post" class="form" id="loginForm">
    <label for="email">Email</label>
    <?= html_text('email', 'maxlength="100" id="email"') ?>
    <div id="email-error" style="color: red;"><?= err('email') ?></div>
    <div id="blocked-error" style="color: red;"><?= err('blocked') ?></div>

    <label for="password">Password</label>
    <?= html_password('password', 'maxlength="100" id="password"') ?>
    <div id="password-error" style="color: red;"><?= err('password') ?></div>

    <section>
        <button>Login</button>
        <button type="reset">Reset</button>
    </section>
</form>

<?php
include '_foot.php';
?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="js/login.js"></script>