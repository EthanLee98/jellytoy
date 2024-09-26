<?php
include 'connectdb.php'; // Include the database connection

// Check if the ID is set in the POST request
if (isset($_POST['id'])) {
    $product_id = $_POST['id'];

    // Delete the product
    $delete_query = "DELETE FROM product WHERE id = ?";
    $stmt = $conn->prepare($delete_query);
    $stmt->bind_param("i", $product_id);

    if ($stmt->execute()) {
        // Show alert and redirect back to productspages.php
        echo "<script>
                alert('Product deleted successfully');
                window.location.href = 'productspages.php';
              </script>";
    } else {
        echo "Error deleting product: " . $conn->error;
    }
} else {
    echo "No product ID provided.";
}
?>
