<?php
include '../_base.php';
include '../lib/SimpleImage.php';

if (isset($_SESSION['user']['id'])) {
    $currentUserId = $_SESSION['user']['id']; // Access the ID from the session user object
} else {
    header("Location: /login.php");
    exit();
}
// Define the useProfile function
function useProfile($userId)
{
    global $_db;

    // Fetch user profile information
    $stmt = $_db->prepare("SELECT name, email, address, phone_number, photo AS photo FROM user WHERE id = :userId");
    $stmt->execute(['userId' => $userId]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Handle form submission for profile update
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['name'], $_POST['address'], $_POST['phone'])) {
        $name = post('name');
        $address = post('address');
        $phone = post('phone');
        $profilePicture = get_file('croppedImage'); // Get the cropped image from the form submission

        // Validate profile picture upload if a new one is provided
        if ($profilePicture) {
            // Validate the uploaded file before proceeding
            if (!str_ends_with($profilePicture->name, '.jpg') && !str_ends_with($profilePicture->name, '.png')) {
                temp_new('info', 'Invalid file extension. Only JPG and PNG are allowed.');
            } else if ($profilePicture->type != 'image/jpeg' && $profilePicture->type != 'image/png') {
                temp_new('info', 'Invalid file type. Only JPEG and PNG are allowed.');
            } else if ($profilePicture->size > 3 * 1024 * 1024) { // 3MB limit
                temp_new('info', 'Maximum file size exceeded. Limit is 3MB.');
            } else {
                $uploadFile = save_photo($profilePicture, '../images/members/'); // Save the new photo
            }
        } else {
            $uploadFile = $user['photo']; // Keep the existing picture if no new one is uploaded
        }

        // If there's an error, do not proceed with the update
        if (!empty($_err)) {
            return; // Stop further execution if there are validation errors
        }

        // Update user profile information in the database
        $updateStmt = $_db->prepare("UPDATE user SET name = :name, address = :address, phone_number = :phone, photo = :photo WHERE id = :userId");
        $updateStmt->execute([
            'name' => $name,
            'address' => $address,
            'phone' => $phone,
            'photo' => $uploadFile,
            'userId' => $userId,
        ]);

        $_SESSION['user']['name'] = $name;
        $_SESSION['user']['address'] = $address;
        $_SESSION['user']['phone'] = $phone;
        $_SESSION['user']['photo'] = $uploadFile;

        // Set success message and refresh the page
        temp_new('success', 'Profile updated successfully!');
        header("Location: " . $_SERVER['PHP_SELF']); // Refresh to show updated data
        exit();
    }

    return $user; // Return user data to populate the form
}

// Handle password change form submission
$userData = useProfile($currentUserId);

// Handle password change form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['current_password'], $_POST['new_password'], $_POST['confirm_password'])) {
    $currentPassword = post('current_password');
    $newPassword = post('new_password');
    $confirmPassword = post('confirm_password');

    // Fetch the user's current password from the database
    $stmt = $_db->prepare("SELECT password FROM user WHERE id = :userId");
    $stmt->execute(['userId' => $currentUserId]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Check if user was found
    if (!$user) {
        temp_new('error', 'User not found.');
        header("Location: /login.php");
        exit();
    }

    // Verify the current password
    if (password_verify($currentPassword, $user['password'])) {
        // Check if new password matches the confirm password
        if ($newPassword === $confirmPassword) {
            // Check if new password is strong
            if (is_strong_password($newPassword)) {
                // Update the password in the database
                $hashedNewPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                $updatePasswordStmt = $_db->prepare("UPDATE user SET password = :new_password WHERE id = :userId");
                $updatePasswordStmt->execute([
                    'new_password' => $hashedNewPassword,
                    'userId' => $currentUserId,
                ]);

                // Set success message and log out
                temp_new('success', 'Password changed successfully!');
                session_destroy(); // Logout user

                // Use output buffering to capture the HTML output
                ob_start();
?>
                <!DOCTYPE html>
                <html lang="en">

                <head>
                    <meta charset="UTF-8">
                    <meta name="viewport" content="width=device-width, initial-scale=1.0">
                    <title>Password Change Success</title>
                    <style>
                        body {
                            display: flex;
                            justify-content: center;
                            align-items: center;
                            height: 100vh;
                            text-align: center;
                        }

                        .message {
                            padding: 20px;
                            border: 1px solid #4CAF50;
                            color: #4CAF50;
                            background-color: #f9f9f9;
                            border-radius: 5px;
                        }

                        h2 {
                            color: black;
                        }
                    </style>
                    <script>
                        // Set a timeout for redirection
                        setTimeout(function() {
                            window.location.href = '/login.php'; // Redirect to login page
                        }, 5000); // 3 seconds delay
                    </script>
                </head>

                <body>
                    <div class="message">
                        <h2>Your Password has been changed. Please login again!</h2>
                        <p>You will be redirected to the login page in 5 seconds...</p>
                    </div>
                </body>

                </html>
    <?php
                // Flush the output buffer
                ob_end_flush();
                exit();
            } else {
                temp_new('error', 'New password must be at least 8 characters long and include uppercase, lowercase, digit, and symbol.');
            }
        } else {
            temp_new('error', 'New password and confirm password do not match.');
        }
    } else {
        temp_new('error', 'Current password is incorrect.');
    }
}

//Freeze Acoount
// Handle delete account form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete-account'])) {
    // Update the user's status to 'freeze'
    $updateStatusStmt = $_db->prepare("UPDATE user SET status = 'freeze' WHERE id = :userId");
    $updateStatusStmt->execute(['userId' => $currentUserId]);

    // Set success message
    temp_new('info', 'Your account has been frozen and email unable to use for register again.');

    // Use output buffering to capture the HTML output
    ob_start();
    ?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Account Frozen</title>
        <style>
            body {
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
                text-align: center;
            }

            .message {
                padding: 20px;
                border: 1px solid #4CAF50;
                color: #4CAF50;
                background-color: #f9f9f9;
                border-radius: 5px;
            }

            h2 {
                color: black;
            }
        </style>
        <script>
            // Set a timeout for redirection
            setTimeout(function() {
                window.location.href = '/login.php'; // Redirect to login page
            }, 5000); // 3 seconds delay
        </script>
    </head>

    <body>
        <div class="message">
            <h2>Your account has been frozen successful.</h2>
            <p>You will be redirected to the login page in 5 seconds...</p>
        </div>
    </body>

    </html>
<?php
    // Flush the output buffer
    ob_end_flush();
    exit();
}



$_title = 'User Profile';
include '../_head.php';
?>

<link rel="stylesheet" href="/css/userProfile.css">
<link rel="stylesheet" href="/lib/cropper.min.css" />
<script src="/lib/cropper.min.js"></script>

<div class="profile-container">
    <div class="sidebar">
        <div class="profile-info">
            <?php
            // Determine the path for the profile picture
            $profilePicturePath = !empty($userData['photo']) && file_exists('../images/members/' . $userData['photo'])
                ? htmlspecialchars($userData['photo'])
                : 'person_default.png'; // Fallback to default image
            ?>
            <img src="/images/members/<?php echo $profilePicturePath; ?>" alt="User Icon" class="profile-avatar">
            <p class="username"><?php echo htmlspecialchars($userData['name']); ?></p>
            <a href="#" class="edit-profile">Edit Personal Info</a>
        </div>
        <ul class="profile-menu">
            <li><a href="#" id="show-profile">My Profile</a></li>
            <li><a href="#" id="show-change-password">Change Password</a></li>
            <li><a href="#" id="show-delete-account">Delete Account</a></li>
        </ul>
    </div>

    <div class="profile-content">
        <!-- Profile Form -->
        <div id="profile-form">
            <h2 class="profile-title">My Profile</h2>
            <p class="profile-description">Manage your profile</p>

            <form id="user-profile-form" method="POST" action="" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" id="name" name="name" class="input-field" value="<?php echo htmlspecialchars($userData['name']); ?>" required>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" class="input-field" value="<?php echo htmlspecialchars($userData['email']); ?>" disabled>
                </div>

                <div class="form-group">
                    <label for="address">Address</label>
                    <input type="text" id="address" name="address" class="input-field" value="<?php echo htmlspecialchars($userData['address']); ?>" required>
                </div>

                <div class="form-group">
                    <label for="phone">Phone</label>
                    <input type="text" id="phone" name="phone" class="input-field" value="<?php echo htmlspecialchars($userData['phone_number']); ?>" required maxlength="10" onkeypress="return isNumberKey(event)" oninput="limitLength(this)">
                </div>


                <div class="form-group">
                    <label>Profile Picture</label>
                    <div class="profile-picture">
                        <img src="/images/members/<?php echo $profilePicturePath; ?>" alt="Profile Picture" class="profile-avatar" id="uploaded-picture">
                        <input type="file" name="photo" accept="image/jpeg, image/png" class="upload-picture" id="file-input">
                        <input type="file" id="cropped-image-input" name="croppedImage" style="display: none;">
                        <small class="upload-info">File size: 1MB only <br>File extensions: JPEG, PNG only</small>
                    </div>

                </div>
                <div id="img-error"></div>

                <button type="submit" class="save-button" id="save-button">Save</button>
                <button type="reset" class="reset-button" id="reset-button">Reset</button>
            </form>
        </div>

        <!-- Cropping Modal -->
        <div id="crop-modal" style="display:none;">
            <div class="crop-modal-content">
                <h2>Crop Your Image</h2>
                <img id="crop-image" src="" alt="Crop Image" style="max-width: 100%; height: auto;">
                <button id="crop-button">Save & Crop</button>
                <button id="close-crop-modal">Cancel</button>
            </div>
        </div>

        <!-- Save Confirmation Modal -->
        <div id="modal-overlay"></div>
        <div id="confirm-modal">
            <div class="modal-content">
                <p>Are you sure you want to save changes?</p>
                <div class="modal-buttons">
                    <button class="modal-btn-yes" id="confirm-yes">Yes</button>
                    <button class="modal-btn-no" id="confirm-no">No</button>
                </div>
            </div>
        </div>


        <!-- Change Password Form -->
        <div id="change-password-form" style="display: none;">
            <h2 class="profile-title">Change Password</h2>

            <form id="change-password" method="POST" action="">
                <div class="form-group">
                    <label for="current_password">Current Password</label>
                    <input type="password" id="current_password" name="current_password" class="input-field">
                </div>

                <div class="form-group">
                    <label for="new_password">New Password</label>
                    <input type="password" id="new_password" name="new_password" class="input-field">
                </div>

                <div class="form-group">
                    <label for="confirm_password">Confirm Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" class="input-field">
                </div>

                <button type="submit" class="save-button" id="changePass-saveBtn">Change Password</button>
            </form>
        </div>


        <!-- Delete Account Form -->
        <div id="delete-account-form" style="display: none;">
            <h2 class="profile-title">Delete Account</h2>
            <p class="warning-message">Are you sure you want to delete your account? <br>
                This action is irreversible and all your data will be lost.</p>
            <form id="delete-account" method="POST" action="">
                <input type="hidden" name="delete-account" value="1">
                <button type="submit" class="delete-button" id="delete-account-btn">Delete</button>
            </form>
        </div>

        <!-- Delete Confirmation Modal -->
        <div id="del-modal-overlay"></div>
        <div id="confirm-del-modal">
            <div class="modal-content">
                <p>Are you sure you want to delete account?</p>
                <div class="modal-buttons">
                    <button class="modal-btn-yes" id="delete-yes">Yes</button>
                    <button class="modal-btn-no" id="delete-no">No</button>
                </div>
            </div>
        </div>
    </div>
</div>


<?php
include '../_foot.php';
?>

<script src="/js/userProfile.js"></script>