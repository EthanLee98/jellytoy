<?php
include '../_base.php'; // Include the base file with helper functions

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Check if the token is valid and not expired
    $stmt = $_db->prepare("SELECT user_id, expire FROM token WHERE token = ?");
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
            $delete_token_stmt = $_db->prepare("DELETE FROM token WHERE token = ?");
            $delete_token_stmt->execute([$token]);

            // Display success message with a link to login
            echo "Your account has been activated! <br>";
            echo '<a href="login.php">Click here to log in</a>';
        } else {
            // Delete the token record first
            $delete_token_stmt = $_db->prepare("DELETE FROM token WHERE user_id = ?");
            $delete_token_stmt->execute([$user_id]);

            // Now delete the user record
            $delete_user_stmt = $_db->prepare("DELETE FROM user WHERE id = ?");
            $delete_user_stmt->execute([$user_id]);

            echo "The activation link has expired. Your account has been deleted.";
        }
    } else {
        echo "Invalid activation link.";
    }
}
?>
