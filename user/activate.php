<?php
include '../_base.php';

if (is_get() && isset($_GET['token'])) {
    $token = get('token');

    // Find the token in the database
    $stmt = $_db->prepare("SELECT * FROM token WHERE token = ? AND expire > NOW()");
    $stmt->execute([$token]);
    $token_data = $stmt->fetch();

    if ($token_data) {
        // Activate the user account
        $user_id = $token_data->user_id;
        $update_stmt = $_db->prepare("UPDATE user SET status = 'active' WHERE id = ?");
        $update_stmt->execute([$user_id]);

        // Delete the token as it has been used
        $delete_stmt = $_db->prepare("DELETE FROM token WHERE user_id = ?");
        $delete_stmt->execute([$user_id]);

        echo "Your account has been successfully activated!";
    } else {
        echo "Invalid or expired token.";
    }
} else {
    echo "No token provided.";
}
?>
