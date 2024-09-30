// Form validation and custom confirmation modal for profile update
$('#save-button').click(function (event) {
    event.preventDefault(); // Prevent immediate form submission

    let valid = true;

    // Remove previous error messages
    $('.error-message').remove();

    // Validate required fields in the profile form (excluding readonly email)
    $('#user-profile-form input[required]').each(function () {
        if ($(this).val().trim() === '') {
            $(this).css('border', '1px solid red');

            // Show an error message below the input field
            $(this).after('<span class="error-message" style="color: red; font-size: 12px;">This field is required and cannot be empty.</span>');
            valid = false;
        } else {
            $(this).css('border', '1px solid #ccc');
        }
    });

    if (valid) {
        // Show custom confirmation modal if all fields are valid
        $('#modal-overlay').fadeIn();
        $('#confirm-modal').fadeIn();
    }
});

$('#reset-button').click(function (event) {
    event.preventDefault();

    if (confirm("Are you sure you want to reset your profile?")) {
        window.location.href = '/user/userProfile.php';
    }
});


function isNumberKey(evt) {
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    // Allow: backspace, delete, tab, escape, enter and .
    if ([46, 8, 9, 27, 13].indexOf(charCode) !== -1 ||
        // Allow: Ctrl+A
        (charCode === 65 && evt.ctrlKey === true) ||
        // Allow: home, end, left, right
        (charCode >= 35 && charCode <= 39)) {
        return true;
    }
    // Ensure that it is a number and stop the keypress
    if (charCode < 48 || charCode > 57) {
        evt.preventDefault();
        return false;
    }
    return true;
}

function limitLength(input) {
    if (input.value.length > 10) {
        input.value = input.value.slice(0, 10); // Limit to 10 characters
    }
}

// Proceed with form submission if "Yes" is clicked in the confirmation modal
$('#confirm-yes').click(function () {
    $('#user-profile-form').submit(); // Submit the form
    $('#modal-overlay, #confirm-modal').fadeOut();
});

// Close the modal if "No" is clicked
$('#confirm-no').click(function () {
    $('#modal-overlay, #confirm-modal').fadeOut();
});

// Close the modal if overlay is clicked
$('#modal-overlay').click(function () {
    $(this).fadeOut();
    $('#confirm-modal').fadeOut();
});


// Change Password Form 
$('#changePass-saveBtn').click(function (event) {
    event.preventDefault(); // Prevent immediate form submission

    let valid = true;

    // Remove previous error messages
    $('.error-message').remove();

    // Check if current password is empty
    const currentPassword = $('#current_password').val().trim();
    if (currentPassword === '') {
        $('#current_password').css('border', '1px solid red');
        $('#current_password').after('<span class="error-message" style="color: red; font-size: 12px;">Current password is required.</span>');
        valid = false;
    } else {
        $('#current_password').css('border', '1px solid #ccc');
    }

    // Check if new password is empty
    const newPassword = $('#new_password').val().trim();
    if (newPassword === '') {
        $('#new_password').css('border', '1px solid red');
        $('#new_password').after('<span class="error-message" style="color: red; font-size: 12px;">New password is required.</span>');
        valid = false;
    } else {
        $('#new_password').css('border', '1px solid #ccc');
    }

    // Check if confirm password is empty
    const confirmPassword = $('#confirm_password').val().trim();
    if (confirmPassword === '') {
        $('#confirm_password').css('border', '1px solid red');
        $('#confirm_password').after('<span class="error-message" style="color: red; font-size: 12px;">Confirm password is required.</span>');
        valid = false;
    } else {
        $('#confirm_password').css('border', '1px solid #ccc');
    }

    // Check if new password and confirm password match
    if (valid && newPassword !== confirmPassword) {
        $('#confirm_password').css('border', '1px solid red');
        $('#confirm_password').after('<span class="error-message" style="color: red; font-size: 12px;">Passwords do not match.</span>');
        valid = false;
    }

    // If everything is valid, submit the form
    if (valid) {
        $('#change-password').submit(); // Explicitly target the form to submit
    }
});


// Form switch toggle
document.getElementById('show-profile').addEventListener('click', function () {
    document.getElementById('profile-form').style.display = 'block';
    document.getElementById('change-password-form').style.display = 'none';
    document.getElementById('delete-account-form').style.display = 'none'; // Hide delete account form
});

document.getElementById('show-change-password').addEventListener('click', function () {
    document.getElementById('profile-form').style.display = 'none';
    document.getElementById('change-password-form').style.display = 'block';
    document.getElementById('delete-account-form').style.display = 'none'; // Hide delete account form
});

// Add this event listener for the delete account toggle
document.getElementById('show-delete-account').addEventListener('click', function () {
    document.getElementById('profile-form').style.display = 'none';
    document.getElementById('change-password-form').style.display = 'none'; // Hide change password form
    document.getElementById('delete-account-form').style.display = 'block'; // Show delete account form
});



//User Profile Picture
document.getElementById('user-profile-form').addEventListener('submit', function (e) {
    e.preventDefault();

    // Confirmation modal logic
    const modalOverlay = document.getElementById('modal-overlay');
    const confirmModal = document.getElementById('confirm-modal');

    modalOverlay.style.display = 'block';
    confirmModal.style.display = 'block';

    document.getElementById('confirm-yes').onclick = function () {
        // Close modal and submit the form
        modalOverlay.style.display = 'none';
        confirmModal.style.display = 'none';
        document.getElementById('user-profile-form').submit();
    };

    document.getElementById('confirm-no').onclick = function () {
        // Close modal
        modalOverlay.style.display = 'none';
        confirmModal.style.display = 'none';
    };
});


// Handle profile picture upload
// userProfile.js
document.getElementById('user-profile-form').addEventListener('submit', function (e) {
    e.preventDefault();

    // Confirmation modal logic
    const modalOverlay = document.getElementById('modal-overlay');
    const confirmModal = document.getElementById('confirm-modal');

    modalOverlay.style.display = 'block';
    confirmModal.style.display = 'block';

    document.getElementById('confirm-yes').onclick = function () {
        // Close modal and submit the form
        modalOverlay.style.display = 'none';
        confirmModal.style.display = 'none';
        document.getElementById('user-profile-form').submit();
    };

    document.getElementById('confirm-no').onclick = function () {
        // Close modal
        modalOverlay.style.display = 'none';
        confirmModal.style.display = 'none';
    };
});

let cropper;
const fileInput = document.getElementById('file-input');
const cropModal = document.getElementById('crop-modal');
const cropImage = document.getElementById('crop-image');
const cropButton = document.getElementById('crop-button');
const closeCropModal = document.getElementById('close-crop-modal');
const imgError = document.getElementById('img-error');
const form = document.getElementById('profile-form'); // Form element

// Define valid image types and max file size
const validImageTypes = ['image/jpeg', 'image/png'];
const maxFileSize = 3 * 1024 * 1024; // 3MB

// Event listener for the file input
fileInput.addEventListener('change', function (event) {
    const files = event.target.files;

    if (files.length > 0) {
        const file = files[0];

        // Clear previous error messages
        imgError.textContent = '';

        // Validate file type
        if (!validImageTypes.includes(file.type)) {
            imgError.textContent = 'Invalid file type. Only JPG and PNG are allowed.';
            imgError.style.color = 'red';
            return; // Stop if invalid
        }

        // Validate file size
        if (file.size > maxFileSize) {
            imgError.textContent = 'File size exceeds 3MB. Please upload a smaller file.';
            imgError.style.color = 'red';
            return; // Stop if invalid
        }

        const reader = new FileReader();
        reader.onload = function (e) {
            cropImage.src = e.target.result; // Set the image source to the uploaded file
            cropModal.style.display = 'block'; // Show the cropping modal

            // Initialize Cropper.js
            if (cropper) {
                cropper.destroy(); // Destroy previous cropper instance
            }
            cropper = new Cropper(cropImage, {
                aspectRatio: 1, // Fixed aspect ratio (1:1)
                viewMode: 1, // Show the entire image in the view
                autoCropArea: 1, // Enable automatic cropping
                movable: true, // Allow moving the crop box
                resizable: true, // Allow resizing the crop box
                zoomable: true, // Allow zooming in/out on the image
                cropBoxResizable: true, // Allow the crop box to be resized
                cropBoxMovable: true, // Allow the crop box to be moved
                minCropBoxWidth: 100, // Minimum width of the crop box
                minCropBoxHeight: 100, // Minimum height of the crop box
            });
        };

        reader.readAsDataURL(file); // Read the file
    }
});

// Save & Crop button functionality
cropButton.addEventListener('click', function () {
    if (!cropper) {
        console.error('Cropper instance is not available.');
        return;
    }

    const canvas = cropper.getCroppedCanvas({
        width: 300, // Set width for the cropped image
        height: 300, // Set height for the cropped image
    });

    // Convert the canvas to a blob and save it temporarily
    canvas.toBlob(function (blob) {
        const formData = new FormData();
        formData.append('croppedImage', blob, 'profile.jpg'); // Append the cropped image to formData

        // Reference the hidden input
        const croppedInput = document.getElementById('cropped-image-input');

        // Check if the input exists, then set the cropped image as a file to be submitted
        if (croppedInput) {
            const file = new File([blob], 'profile.jpg', { type: blob.type });
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(file);
            croppedInput.files = dataTransfer.files; // Set the cropped image to the hidden input
        } else {
            console.error('Hidden input with ID "cropped-image-input" not found.');
        }

        // Display the cropped image in the profile preview
        const imgElement = document.getElementById('uploaded-picture');
        if (imgElement) {
            imgElement.src = canvas.toDataURL(); // Set the base64 data URL of the cropped image as the src of the img tag
        } else {
            console.error('Image element with ID "uploaded-picture" not found.');
        }

        cropModal.style.display = 'none'; // Close the cropping modal
    });
});


// Convert a blob to a file (useful for setting the file in an input)
function blobToFile(blob, filename) {
    blob.lastModifiedDate = new Date();
    blob.name = filename;
    return blob;
}

// Close crop modal functionality
closeCropModal.addEventListener('click', function () {
    cropModal.style.display = 'none'; // Close the cropping modal
    if (cropper) {
        cropper.destroy(); // Destroy cropper instance on close
    }
});

// Ensure that if the image exceeds 3MB, the form does not submit
form.addEventListener('submit', function (event) {
    const file = fileInput.files[0];
    if (file && file.size > maxFileSize) {
        imgError.textContent = 'File size exceeds 3MB. Please upload a smaller file.';
        imgError.style.color = 'red';
        event.preventDefault(); // Prevent form submission
    }
});


// Form validation and custom confirmation modal for profile update
$('#delete-account-btn').click(function (event) {
    $('#del-modal-overlay').fadeIn();
    $('#confirm-del-modal').fadeIn();
});

// User Profile Picture
document.getElementById('delete-account-form').addEventListener('submit', function (e) {
    e.preventDefault(); // Prevent default form submission

    // Confirmation modal logic
    const modalOverlay = document.getElementById('del-modal-overlay');
    const confirmModal = document.getElementById('confirm-del-modal');

    modalOverlay.style.display = 'block';
    confirmModal.style.display = 'block';

    document.getElementById('delete-yes').onclick = function () {
        // Close modal and submit the form
        modalOverlay.style.display = 'none';
        confirmModal.style.display = 'none';

        // Submit the form directly
        document.getElementById('delete-account').submit();
    };

    document.getElementById('delete-no').onclick = function () {
        // Close modal
        modalOverlay.style.display = 'none';
        confirmModal.style.display = 'none';
    };
});