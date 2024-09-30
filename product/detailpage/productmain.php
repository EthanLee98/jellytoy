<?php
//session_start(); // Start the session

include '../productdb.php';

// Fetch product data from the database
$product_id = isset($_GET['id']) ? intval($_GET['id']) : 1; // Default to 1 if no ID is provided
$sql = "SELECT * FROM product WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();

// Check if product is found
if ($result->num_rows > 0) {
    $product = $result->fetch_assoc();
} else {
    echo "<p>Product not found.</p>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($product['name']); ?> - Product Page</title>
    <style>
        .close-button {
            position: absolute;
            top: 10px;
            right: 10px;
            padding: 10px 15px;
            background-color: #f44336;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
    </style>
</head>
<body>
    <button class="close-button" onclick="window.history.back();">Close</button>

    <?php
    include 'productdetail.php';
    include 'productcategorysection.php';
    include 'productrating.php';
    include 'productratingform.php';
    ?>
</body>
</html>
