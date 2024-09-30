<?php
include '../productdb.php';

// Fetch product data from the database
$product_id = isset($_GET['id']) ? intval($_GET['id']) : 1;

// Prepare the SQL statement
$sql = "SELECT * FROM product WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $product = $result->fetch_assoc();
} else {
    echo "Product not found.";
    exit;
}

// Fetch the average rating and count of reviews from the product_review table
$sql_review = "SELECT AVG(rating) as average_rating, COUNT(*) as review_count FROM product_review WHERE product_id = ?";
$stmt_review = $conn->prepare($sql_review);
$stmt_review->bind_param("i", $product_id);
$stmt_review->execute();
$result_review = $stmt_review->get_result();
$row_review = $result_review->fetch_assoc();
$average_rating = $row_review['average_rating'] ? round($row_review['average_rating'], 1) : 0;
$review_count = $row_review['review_count'] ?? 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];
    $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;

    if ($action === 'add_to_cart') {
        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            // Redirect to login page
            header("Location: login.php");
            exit;
        }
    
        // Check if stock is sufficient
        if ($product['stock'] >= $quantity) {
            // Insert into cart database
            $user_id = $_SESSION['user_id']; // Assuming you have user_id in session
            $date_added = date('Y-m-d H:i:s'); // Current date and time
            $sql_insert_cart = "INSERT INTO cart (user_id, product_id, quantity, created_at) VALUES (?, ?, ?, ?)";
            $stmt_insert_cart = $conn->prepare($sql_insert_cart);
            $stmt_insert_cart->bind_param("iiis", $user_id, $product_id, $quantity, $date_added);
    
            if ($stmt_insert_cart->execute()) {
                echo json_encode(['message' => 'Product added to cart successfully.']);
            } else {
                echo json_encode(['error' => 'Failed to add product to cart.']);
            }
            $stmt_insert_cart->close();
        } else {
            echo json_encode(['error' => 'Insufficient stock.']);
        }
        exit;
    }    

    if ($action === 'buy_now') {
        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            // Redirect to login page
            header("Location: login.php");
            exit;
        }

        // Check if stock is sufficient
        if ($product['stock'] >= $quantity) {
            // Insert into orders database
            $user_id = $_SESSION['user_id'];
            $sql_insert_order = "INSERT INTO orders (user_id, product_id, quantity) VALUES (?, ?, ?)";
            $stmt_insert_order = $conn->prepare($sql_insert_order);
            $stmt_insert_order->bind_param("iii", $user_id, $product_id, $quantity);

            if ($stmt_insert_order->execute()) {
                // Reduce stock
                $new_stock = $product['stock'] - $quantity;
                $sql_update = "UPDATE product SET stock = ? WHERE id = ?";
                $stmt_update = $conn->prepare($sql_update);
                $stmt_update->bind_param("ii", $new_stock, $product_id);
                $stmt_update->execute();
                
                echo json_encode(['message' => 'Purchase completed successfully.']);
            } else {
                echo json_encode(['error' => 'Failed to complete purchase.']);
            }
            $stmt_insert_order->close();
        } else {
            echo json_encode(['error' => 'Insufficient stock.']);
        }
        exit;
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($product['name']); ?> - Product Page</title>
    <link rel="stylesheet" href="detail.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>

<div class="main-wrapper">
    <div class="container">
        <div class="product-div">
            <div class="product-div-left">
                <div class="img-container">
                    <img src="/images/products/<?php echo htmlspecialchars($product['photo1']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                </div>
                <div class="hover-container">
                    <div><img src="/images/products/<?php echo htmlspecialchars($product['photo1']); ?>" alt="Product Image"></div>
                    <div><img src="/images/products/<?php echo htmlspecialchars($product['photo2']); ?>" alt="Product Image"></div>
                    <div><img src="/images/products/<?php echo htmlspecialchars($product['photo3']); ?>" alt="Product Image"></div>
                    <?php if (!empty($product['photo4'])): ?>
                    <div><img src="/images/products/<?php echo htmlspecialchars($product['photo4']); ?>" alt="Product Image"></div>
                    <?php endif; ?>
                    <?php if (!empty($product['photo5'])): ?>
                    <div><img src="/images/products/<?php echo htmlspecialchars($product['photo5']); ?>" alt="Product Image"></div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="product-div-right">
                <span class="product-name"><?php echo htmlspecialchars($product['name']); ?></span>
                <span class="product-price">RM <?php echo number_format($product['price'], 2); ?></span>
                <div class="product-rating">
                    <span>Average Rating: <?php echo $average_rating; ?> (<?php echo $review_count; ?> reviews)</span>
                </div>
                <p class="product-description"><?php echo htmlspecialchars($product['description']); ?></p>
                <div class="quantity-container">
                    <label class="quantity-label" for="quantity">Quantity:</label>
                    <input type="number" id="quantity" name="quantity" class="quantity-input" min="1" max="<?php echo $product['stock']; ?>" value="1">
                </div>

                <div class="btn-groups">
                    <button type="button" class="add-cart-btn" data-product-id="<?= $product_id ?>">
                        <i class="fas fa-shopping-cart"></i> Add to Cart
                    </button>
                    <button type="button" class="buy-now-btn" data-product-id="<?= $product_id ?>">
                        <i class="fas fa-wallet"></i> Buy Now
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="detailjQ.js"></script>
</body>

</html>

<?php
// Close the database connection here
$stmt->close();
$conn->close();
?>
