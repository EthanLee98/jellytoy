<?php
include 'connectdb.php';

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $brand = mysqli_real_escape_string($conn, $_POST['brand']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $stock = mysqli_real_escape_string($conn, $_POST['stock']);
    $video_url = mysqli_real_escape_string($conn, $_POST['video_url']);
    $category_id = mysqli_real_escape_string($conn, $_POST['category_id']);
    
    // Retrieve the uploaded image paths from the hidden inputs
    $uploaded_images = isset($_POST['uploaded_images']) ? $_POST['uploaded_images'] : [];

    // Assign image paths to variables
    $photo1 = isset($uploaded_images[0]) ? $uploaded_images[0] : null;
    $photo2 = isset($uploaded_images[1]) ? $uploaded_images[1] : null;
    $photo3 = isset($uploaded_images[2]) ? $uploaded_images[2] : null;

    // Insert the product into the database
    $sql = "INSERT INTO product (name, brand, description, price, stock, photo1, photo2, photo3, video_url, category_id) 
            VALUES ('$name', '$brand', '$description', '$price', '$stock', '$photo1', '$photo2', '$photo3', '$video_url', '$category_id')";

    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Product added successfully!'); window.location.href = 'productspages.php';</script>";
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }

    mysqli_close($conn);
}
?>