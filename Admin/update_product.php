<?php
include 'connectdb.php';

// Fetch the product to be updated
$product_id = $_POST['id']; // Get the product ID from the URL

$query = "SELECT * FROM product WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $id = $_POST['id'] ?? null; // Use null coalescing operator to avoid undefined index notice
    if ($id === null) {
        die("Product ID not set.");
    }

    $name = $_POST['name'];
    $brand = $_POST['brand'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $category_id = $_POST['category_id'];
    $video_url = $_POST['video_url'];

    // Handle image removal
    if (isset($_POST['remove_photos'])) {
        foreach ($_POST['remove_photos'] as $photo) {
            $query = "UPDATE product SET $photo = NULL WHERE id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $stmt->close();
        }
    }

    // Prepare the SQL update statement
    $query = "UPDATE product SET name = ?, brand = ?, description = ?, price = ?, stock = ?, date_created = NOW(), category_id = ?, video_url = ? WHERE id = ?";
    
    $stmt = $conn->prepare($query);
    
    // Bind parameters (adjust types as needed)
    $stmt->bind_param("sssdissi", $name, $brand, $description, $price, $stock, $category_id, $video_url, $id);
    
    // Execute the statement
    if ($stmt->execute()) {
        // Handle image uploads
        if (isset($_FILES['images']) && $_FILES['images']['error'][0] !== UPLOAD_ERR_NO_FILE) {
            foreach ($_FILES['images']['tmp_name'] as $index => $tmp_name) {
                if ($_FILES['images']['error'][$index] === UPLOAD_ERR_OK) {
                    $file_name = basename($_FILES['images']['name'][$index]);
                    $target_dir = "../images/products/"; // Directory where images will be saved
                    $target_file = $target_dir . $file_name;

                    // Move the uploaded file to the target directory
                    if (move_uploaded_file($tmp_name, $target_file)) {
                        // Update the product image path in the database
                        $image_column = "photo" . ($index + 1); // Assuming photo1, photo2, photo3...
                        $update_query = "UPDATE product SET $image_column = ? WHERE id = ?";
                        $update_stmt = $conn->prepare($update_query);
                        $update_stmt->bind_param("si", $file_name, $id);
                        $update_stmt->execute();
                        $update_stmt->close();
                    }
                }
            }
        }
        $message = "Product updated successfully."; // Success message
    } else {
        $message = "Error updating product: " . $stmt->error; // Error message
    }

    // Close the statement
    $stmt->close();
} else {
    $message = "No data submitted."; // Fallback message
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Product</title>
    <script>
        window.onload = function() {
            alert("<?php echo addslashes($message); ?>"); // Display alert with the message
            window.location.href = "productspages.php"; // Redirect to product management page
        };
    </script>
</head>
<body>
</body>
</html>
