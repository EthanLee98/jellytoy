<?php
include '../_base.php'; // Include the base file with helper functions

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    $stmt = $_db->prepare("SELECT user_id, expire FROM token WHERE id = ?");
    $stmt->execute([$token]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        $user_id = $result['user_id'];
        $expiry = $result['expire'];

        // Check if the token has expired
        if (strtotime($expiry) > time()) {
            // Activate user
            $update_stmt = $_db->prepare("UPDATE user SET status = 'active' WHERE id = ?");
            $update_stmt->execute([$user_id]);

            // Clean up token
            $delete_token_stmt = $_db->prepare("DELETE FROM token WHERE id = ?");
            $delete_token_stmt->execute([$token]);

            $stmt = $_db->prepare('SELECT * FROM user WHERE id = ?');
            $stmt->execute([$user_id]);
            $u = $stmt->fetch(PDO::FETCH_ASSOC);

            temp_new('success', 'Your account has been activated!');
            login($u);
        } else {
            // Delete the token record first
            $delete_token_stmt = $_db->prepare("DELETE FROM token WHERE user_id = ?");
            $delete_token_stmt->execute([$user_id]);

            // Now delete the user record
            $delete_user_stmt = $_db->prepare("DELETE FROM user WHERE id = ?");
            $delete_user_stmt->execute([$user_id]);

            temp_new('info', 'The activation link has expired. Please register again.');
            redirect('/login.php');
        }
    } else {
        temp_new('error', 'Invalid activation link.');
        redirect('/login.php');
    }
}
