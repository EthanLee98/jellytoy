<?php
include '../productdb.php'; // Database connection

// Handle AJAX request to add to cart
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];
    $product_id = intval($_POST['id']);
    $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;

    if ($action === 'add_to_cart') {
        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['error' => 'You need to login.']);
            exit;
        }

        // Add to cart logic
        $user_id = $_SESSION['user_id'];
        $_SESSION['cart'][] = ['product_id' => $product_id, 'quantity' => $quantity];
        
        echo json_encode(['message' => 'Product added to cart successfully.']);
        exit;
    }
}

// Fetch product data from the database
$productid = isset($_GET['id']) ? intval($_GET['id']) : 1;

// Use prepared statements for fetching the product
$sql = "SELECT * FROM product WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $productid);
$stmt->execute();
$result = $stmt->get_result();

// Check if product is found
if ($result->num_rows > 0) {
    $product = $result->fetch_assoc(); // Store the product details in a variable
    $category = $product['category_id']; // Fetch the category_id of the selected product
} else {
    echo "Product not found.";
    exit;
}

// Fetch all "Related Product" products using prepared statements
$relatedProductsQuery = "SELECT * FROM product WHERE category_id = ? AND id != ?";
$relatedStmt = $conn->prepare($relatedProductsQuery);
$relatedStmt->bind_param("ii", $category, $productid);
$relatedStmt->execute();
$relatedProductsResult = $relatedStmt->get_result();

// Fetch "Trending" products using prepared statements
$trendingProductsQuery = "SELECT * FROM product WHERE category_id = ? AND id != ?";
$trendingStmt = $conn->prepare($trendingProductsQuery);
$trendingStmt->bind_param("ii", $category, $productid);
$trendingStmt->execute();
$trendingProductsResult = $trendingStmt->get_result();
?>

<link rel="stylesheet" href="categorysection.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<body>

<div class="container">
    <!-- Navigation between sections -->
    <div class="section-toggle">
        <button id="relatedBtn" class="active">Related Product</button>
        <button id="trendingBtn">Trending</button>
    </div>

    <!-- Product section container -->
    <div id="product-container">
        <!-- "Related Product" products -->
        <div id="related-section" class="product-section active">
            <div class="product-wrapper">
                <?php if ($relatedProductsResult->num_rows > 0) : ?>
                    <?php while ($relatedProduct = $relatedProductsResult->fetch_assoc()) : ?>
                        <div class="product-card" style="display: none;" data-product-id="<?= $relatedProduct['id'] ?>">
                            <img src="/images/products/<?= $relatedProduct['photo1'] ?>" alt="<?= htmlspecialchars($relatedProduct['name']) ?>">
                            <div class="product-info">
                                <h3><?= htmlspecialchars($relatedProduct['name']) ?></h3>
                                <p>RM <?= number_format($relatedProduct['price'], 2) ?></p>
                            </div>
                            <div class="product-btn">
                                <button class="add-to-cart" data-product-id="<?= $relatedProduct['id'] ?>">Add To Cart</button>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else : ?>
                    <p>No products found in the "Related Product" section.</p>
                <?php endif; ?>
            </div>

            <!-- Navigation buttons for related products -->
            <div class="product-navigation">
                <button class="prev-btn" disabled><i class="fa-solid fa-angle-left"></i></button>
                <button class="next-btn"><i class="fa-solid fa-angle-right"></i></button>
            </div>
        </div>

        <!-- "Trending" products (hidden by default) -->
        <div id="trending-section" class="product-section">
            <div class="product-wrapper">
                <?php if ($trendingProductsResult->num_rows > 0) : ?>
                    <?php while ($trendingProduct = $trendingProductsResult->fetch_assoc()) : ?>
                        <div class="product-card">
                            <img src="/images/products/<?= $trendingProduct['photo1'] ?>" alt="<?= htmlspecialchars($trendingProduct['name']) ?>">
                            <div class="product-info">
                                <h3><?= htmlspecialchars($trendingProduct['name']) ?></h3>
                                <p>RM <?= number_format($trendingProduct['price'], 2) ?></p>
                            </div>
                            <div class="product-btn">
                                <button class="add-to-cart" data-product-id="<?= $trendingProduct['id'] ?>">Add To Cart</button>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else : ?>
                    <p>No products found in the "Trending" section.</p>
                <?php endif; ?>
            </div>
            <!-- Navigation buttons for trending products -->
            <div class="product-navigation">
                <button class="prev-btn" disabled><i class="fa-solid fa-angle-left"></i></button>
                <button class="next-btn"><i class="fa-solid fa-angle-right"></i></button>
            </div>
        </div>
    </div>
</div>

<script src="categoryjQ.js"></script>

</body>