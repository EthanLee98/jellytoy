<?php
include '/_base.php';

// Define the useProfile function
function useProfile($userId)
{
    // Use the global PDO object
    global $_db;

    // Fetch user profile information
    $stmt = $_db->prepare("SELECT name, email, delivery_address, phone_number, photo AS profile_picture FROM user WHERE id = :userId");
    $stmt->execute(['userId' => $userId]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Handle form submission for profile update
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['name'], $_POST['delivery_address'], $_POST['phone'])) {
        $name = post('name');
        $deliveryAddress = post('delivery_address');
        $phone = post('phone');
        $profilePicture = get_file('profile_picture');

        // Process profile picture upload if a new one is provided
        if ($profilePicture) {
            $uploadDir = 'uploads/';
            $uploadFile = save_photo($profilePicture, $uploadDir); // Save the new photo
        } else {
            $uploadFile = $user['profile_picture']; // Keep the existing picture if no new one is uploaded
        }

        // Update user profile information in the database
        $updateStmt = $_db->prepare("UPDATE user SET name = :name, delivery_address = :delivery_address, phone_number = :phone, photo = :profile_picture WHERE id = :userId");
        $updateStmt->execute([
            'name' => $name,
            'delivery_address' => $deliveryAddress,
            'phone' => $phone,
            'profile_picture' => $uploadFile,
            'userId' => $userId,
        ]);

        // Optionally, you can redirect or display a success message here
        temp('success', 'Profile updated successfully!'); // Example of setting a success message
    }

    return $user; // Return user data to populate the form
}

if (isset($_SESSION['user']['id'])) {
    $currentUserId = $_SESSION['user']['id']; // Access the ID from the session user object
} else {
    header("Location: /login.php");
    exit();
}

$userData = useProfile($currentUserId);

include '/_head.php';
?>

<link rel="stylesheet" href="/css/userProfile.css">

<div class="profile-container">
    <div class="sidebar">
        <div class="profile-info">
            <img src="<?php echo htmlspecialchars($userData['profile_picture'] ?? 'default-avatar.png'); ?>" alt="User Avatar" class="profile-avatar">
            <p class="username"><?php echo htmlspecialchars($userData['name']); ?></p>
            <a href="#" class="edit-profile">Edit Personal Info</a>
        </div>
        <ul class="profile-menu">
            <li><a href="#" id="show-profile">My Profile</a></li>
            <li><a href="#" id="show-change-password">Change Password</a></li>
        </ul>
    </div>

    <div class="profile-content">
        <!-- Profile Form -->
        <div id="profile-form">
            <h2 class="profile-title">My profile</h2>
            <p class="profile-description">Manage your profile</p>

            <form id="user-profile-form" method="POST" action="" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="name">Name</label>
                    <?php html_text('name', 'class="input-field" value="' . htmlspecialchars($userData['name']) . '"'); ?>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" class="input-field" value="<?php echo htmlspecialchars($userData['email']); ?>" readonly>
                </div>

                <div class="form-group">
                    <label for="delivery_address">Delivery Address</label>
                    <?php html_text('delivery_address', 'class="input-field" value="' . htmlspecialchars($userData['delivery_address']) . '"'); ?>
                </div>

                <div class="form-group">
                    <label for="phone">Phone</label>
                    <?php html_text('phone', 'class="input-field" value="' . htmlspecialchars($userData['phone_number']) . '"'); ?>
                </div>

                <div class="form-group">
                    <label>Profile Picture</label>
                    <div class="profile-picture">
                        <img src="<?php echo htmlspecialchars($userData['profile_picture'] ?? 'default-avatar.png'); ?>" alt="Profile Picture" class="profile-avatar" id="uploaded-picture">
                        <?php html_file('profile_picture', 'image/jpeg, image/png', 'class="upload-picture"'); ?>
                        <small class="upload-info">File size: max 1MB, File extensions: JPEG, PNG</small>
                    </div>
                </div>

                <button type="submit" class="save-button">Save</button>
            </form>
        </div>

        <!-- Change Password Form -->
        <div id="change-password-form" style="display: none;">
            <h2 class="profile-title">Change Password</h2>

            <form id="change-password" method="POST" action="change_password.php">
                <div class="form-group">
                    <label for="current_password">Current Password</label>
                    <?php html_password('current_password', 'class="input-field"'); ?>
                </div>

                <div class="form-group">
                    <label for="new_password">New Password</label>
                    <?php html_password('new_password', 'class="input-field"'); ?>
                </div>

                <div class="form-group">
                    <label for="confirm_password">Confirm Password</label>
                    <?php html_password('confirm_password', 'class="input-field"'); ?>
                </div>

                <button type="submit" class="save-button">Change Password</button>
            </form>
        </div>
    </div>
</div>

<script>
    // JavaScript to toggle between profile and change password forms
    document.getElementById('show-profile').addEventListener('click', function() {
        document.getElementById('profile-form').style.display = 'block';
        document.getElementById('change-password-form').style.display = 'none';
    });

    document.getElementById('show-change-password').addEventListener('click', function() {
        document.getElementById('profile-form').style.display = 'none';
        document.getElementById('change-password-form').style.display = 'block';
    });
</script>

<?php
include '/_foot.php';
?>