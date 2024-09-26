<?php
include 'connectdb.php'; // Include the database connection

// Check if the ID is set
if (isset($_GET['id'])) {
    $product_id = $_GET['id'];

    // Fetch the product to display the name or details (optional)
    $query = "SELECT name FROM product WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();

    // Check if product exists
    if (!$product) {
        header("Location: productspages.php?message=Product+not+found");
        exit();
    }
} else {
    header("Location: productspages.php?message=No+product+ID+provided");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirm Deletion</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .confirmation-box {
            background: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .confirmation-box h2 {
            margin-bottom: 20px;
        }
        .btn {
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin: 5px;
            font-size: 16px;
        }
        .btn-confirm {
            background-color: #ff6347;
            color: white;
        }
        .btn-cancel {
            background-color: #007bff;
            color: white;
        }
    </style>
</head>
<body>
    <div class="confirmation-box">
        <h2>Are you sure you want to delete the product "<?php echo htmlspecialchars($product['name']); ?>"?</h2>
        <form action="deleteproduct_action.php" method="POST">
            <input type="hidden" name="id" value="<?php echo $product_id; ?>">
            <button type="submit" class="btn btn-confirm">Yes, Delete</button>
            <a href="productspages.php" class="btn btn-cancel">No, Cancel</a>
        </form>
    </div>
</body>
</html>
