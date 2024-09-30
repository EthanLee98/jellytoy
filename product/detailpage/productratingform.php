<?php
include '../productdb.php';

// Fetch product data from the database
$productid = isset($_GET['id']) ? intval($_GET['id']) : 1;
$sql = "SELECT * FROM product WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $productid);
$stmt->execute();
$result = $stmt->get_result();

// Check if product is found
if ($result->num_rows > 0) {
    $product = $result->fetch_assoc();
} else {
    echo "Product not found.";
    exit;
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate input
    $rating = isset($_POST['rating']) ? (int)$_POST['rating'] : null;
    $text = trim($_POST['review']);
    $product_id = $productid;
    $user_id = isset($_POST['user_id']) ? (int)$_POST['user_id'] : 1; // Set user_id to 1 for testing
    $timestamp = date('Y-m-d H:i:s'); // Capture the current timestamp

    // Basic validation
    if ($rating < 1 || $rating > 5 || empty($text) || empty($user_id)) {
        echo "Invalid input. Please fill out all fields correctly.";
    } else {
        // Prepare the SQL statement to include the timestamp
        $sql = "INSERT INTO product_review (rating, review, product_id, user_id, created_at) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("isiss", $rating, $text, $product_id, $user_id, $timestamp); // Bind the timestamp as well

        if ($stmt->execute()) {
            echo "Review submitted successfully!";
        } else {
            echo "Error: " . $stmt->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rating Form</title>
    <link rel="stylesheet" href="ratingform.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>

<div class="form-container">
    <h2>Submit Your Review for <?= htmlspecialchars($product['name']) ?></h2>
    <form id="rating-form" method="POST" action="">
        <div id="rating-stars">
            <span class="star" data-value="1">&#9733;</span>
            <span class="star" data-value="2">&#9733;</span>
            <span class="star" data-value="3">&#9733;</span>
            <span class="star" data-value="4">&#9733;</span>
            <span class="star" data-value="5">&#9733;</span>
        </div>
        <input type="hidden" name="rating" id="rating" required>
        <input type="hidden" name="user_id" value="1"> <!-- Set user ID to 1 for testing -->

        <label for="review">Review:</label>
        <textarea name="review" id="review" rows="4" required></textarea>

        <button id="review-form-btn" type="submit">Submit Review</button>
    </form>
</div>

<script src="ratingformJQ.js"></script>
</body>
</html>
