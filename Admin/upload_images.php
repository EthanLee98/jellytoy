<?php
if (isset($_FILES['images']) && is_array($_FILES['images']['name'])) {
    $uploadDirectory = "uploads/";

    foreach ($_FILES['images']['name'] as $key => $name) {
        $tempName = $_FILES['images']['tmp_name'][$key];
        $uniqueFileName = uniqid() . '_' . basename($name); // Make the file name unique
        $path = $uploadDirectory . $uniqueFileName;

        if (move_uploaded_file($tempName, $path)) {
            echo $path; // Return the uploaded file path
        } else {
            echo "Failed to upload: " . $name;
        }
    }
} else {
    echo "No files uploaded or files are not in the expected format.";
}
?>
