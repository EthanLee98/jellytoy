<?php
include '../productdb.php';

// Get the product ID from the URL
$product_id = isset($_GET['id']) ? intval($_GET['id']) : 1;

// Fetch reviews from the database for the specific product ID, joining with the user table
$sql = "SELECT pr.*, u.name FROM product_review pr JOIN user u ON pr.user_id = u.id WHERE pr.product_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();

// Initialize an array for reviews
$reviews = [];

if ($result->num_rows > 0) {
    // Output data of each row
    while ($row = $result->fetch_assoc()) {
        $reviews[] = $row;
    }
} else {
    $noReviewsMessage = "No reviews have been made yet. Make yours now!";
}

// Function to convert rating to stars
function ratingToStars($rating) {
    $stars = '';
    for ($i = 1; $i <= 5; $i++) {
        if ($i <= $rating) {
            $stars .= '★'; // Full star
        } else {
            $stars .= '☆'; // Empty star
        }
    }
    return $stars;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rotating Reviews</title>
    <link rel="stylesheet" href="rating.css"> <!-- Link to your CSS file -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- jQuery -->
</head>
<body>

<div class="review-container">
    <h2 class="section-title">Customer Reviews</h2>
    
    <div class="carousel">
    <div class="carousel-inner">
        <?php if (!empty($reviews)) : ?>
            <?php foreach ($reviews as $review): ?>
                <div class="review-item card">
                    <div class="review-rating"><?= ratingToStars(htmlspecialchars($review['rating'])) ?></div>
                    <p class="review-text">"<?= htmlspecialchars($review['review']) ?>"</p>
                    <div class="reviewer-name">- <?= htmlspecialchars($review['name']) ?></div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="no-reviews-message"><?= $noReviewsMessage; ?></p>
        <?php endif; ?>
    </div>
        <button class="carousel-control prev">❮</button>
        <button class="carousel-control next">❯</button>
    </div>
    
</div>

<script src="ratingJQ.js"></script>
</body>
</html>
