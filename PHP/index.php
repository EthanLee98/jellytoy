<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-commerce Homepage</title>
    <link rel="stylesheet" href="../Css/style.css">
</head>

<body>
    <!-- Navigation Bar -->
    <header>
        <nav>       
            <div class="logo">
                <h1>ShopEase</h1>
            </div>
            <ul class="nav-links">
                <li><a href="home.php">Home</a></li>
                <li><a href="shop.php">Shop</a></li>
                <li><a href="about.php">About</a></li>
                <li><a href="contact.php">Contact</a></li>
                <li><a href="cart.php">Cart</a></li>
            </ul>
            <div class="search-bar">
                <input type="text" placeholder="Search products...">
                <button>Search</button>
            </div>
        </nav>
    </header>

    <!-- Hero Section -->
    <section class="hero">
        <h2>Welcome to ShopEase</h2>
        <p>Find the best products at unbeatable prices!</p>
        <a href="#" class="cta">Shop Now</a>
    </section>

    <!-- Hero Section with Carousel -->
    <section class="carousel-container">
        <div class="carousel">
            <div class="carousel-slide">
                <img src="../Source/images/slider1.jpeg" alt="Slide 1">
                <img src="../Source/images/slider2.jpg" alt="Slide 2">
                <img src="../Source/images/slider3.jpg" alt="Slide 3">
                <img src="../Source/images/slider4.jpg" alt="Slide 4">
            </div>
        </div>

        <!-- Carousel Buttons -->
        <button id="prevBtn" class="carousel-btn prev">❮</button>
        <button id="nextBtn" class="carousel-btn next">❯</button>
    </section>


    <!-- Product Section -->
    <section class="products">
        <h2>Featured Products</h2>
        <div class="product-grid">
            <!-- Product Item -->
            <div class="product-item">
                <img src="images/product1.jpg" alt="Product 1">
                <h3>Product 1</h3>
                <p>$25.00</p>
                <button>Add to Cart</button>
            </div>
            <div class="product-item">
                <img src="images/product2.jpg" alt="Product 2">
                <h3>Product 2</h3>
                <p>$35.00</p>
                <button>Add to Cart</button>
            </div>
            <!-- Add more products as needed -->
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


    <!-- Include JS File -->
    <script src="../Js/script.js"></script>
</body>

</html>