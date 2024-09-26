<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Details - ShopEase</title>
    <!-- Link to external CSS file -->
    <link rel="stylesheet" href="../Css/style.css">
</head>
<body>

    <!-- Header -->
    <header>
        <nav>
            <div class="logo">
                <h1>ShopEase</h1>
            </div>
            <ul class="nav-links">
                <li><a href="index.php">Home</a></li>
                <li><a href="shop.php">Shop</a></li>
                <li><a href="#">About</a></li>
                <li><a href="#">Contact</a></li>
                <li><a href="#">Cart</a></li>
            </ul>
            <div class="search-bar">
                <input type="text" placeholder="Search products...">
                <button>Search</button>
            </div>
        </nav>
    </header>

    <!-- Product Details Section -->
    <section class="product-details-container">
        <div class="product-details">
            <div class="product-image">
                <img src="../Images/product1.jpg" alt="Product Image">
            </div>
            <div class="product-info">
                <h2>Product Name</h2>
                <p class="product-brand">Brand: <span>Brand Name</span></p>
                <p class="product-price">$45.00</p>
                <p class="product-description">
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed malesuada consectetur nibh, 
                    non varius libero auctor vitae. Duis vehicula orci ut magna commodo, nec facilisis ligula vestibulum.
                </p>
                <form class="product-form">
                    <label for="quantity">Quantity:</label>
                    <input type="number" id="quantity" name="quantity" value="1" min="1">
                    <button type="submit">Add to Cart</button>
                </form>
            </div>
        </div>
    </section>

    <!-- Footer Section -->
    <footer>
        <div class="footer-container">
            <!-- Contact Info -->
            <div class="footer-section contact">
                <h3>Contact Us</h3>
                <p>Email: support@shopease.com</p>
                <p>Phone: +123 456 7890</p>
                <p>Address: 123 E-commerce St, ShopCity, SC 54321</p>
            </div>

            <!-- Quick Links -->
            <div class="footer-section links">
                <h3>Quick Links</h3>
                <ul>
                    <li><a href="#">About Us</a></li>
                    <li><a href="#">Privacy Policy</a></li>
                    <li><a href="#">Terms of Service</a></li>
                    <li><a href="#">FAQ</a></li>
                </ul>
            </div>

            <!-- Social Media -->
            <div class="footer-section social">
                <h3>Follow Us</h3>
                <ul class="social-icons">
                    <li><a href="#"><img src="images/facebook-icon.png" alt="Facebook"></a></li>
                    <li><a href="#"><img src="images/twitter-icon.png" alt="Twitter"></a></li>
                    <li><a href="#"><img src="images/instagram-icon.png" alt="Instagram"></a></li>
                    <li><a href="#"><img src="images/linkedin-icon.png" alt="LinkedIn"></a></li>
                </ul>
            </div>

            <!-- Map Embed -->
            <div class="footer-section map">
                <h3>Our Location</h3>
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3151.8354345093646!2d144.9537353156739!3d-37.816279742021504!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x6ad642af0f11fd81%3A0xf577e63d79476d0a!2sFederation%20Square!5e0!3m2!1sen!2sau!4v1602175022444!5m2!1sen!2sau"
                    width="100%" height="150" style="border:0;" allowfullscreen="" aria-hidden="false" tabindex="0">
                </iframe>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2024 ShopEase. All rights reserved.</p>
        </div>
    </footer>

    <!-- Link to external JS file -->
    <script src="../Js/script.js"></script>
</body>
</html>
