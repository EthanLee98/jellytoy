<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart - ShopEase</title>
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
                <li><a href="about.php">About</a></li>
                <li><a href="contact.php">Contact</a></li>
                <li><a href="cart.php">Cart</a></li>
            </ul>
        </nav>
    </header>

    <!-- Cart Section -->
    <section class="cart-container">
        <h2>Your Shopping Cart</h2>

        <div class="cart-items">
            <!-- Example of a single cart item -->
            <div class="cart-item">
                <img src="../Images/product1.jpg" alt="Product Image">
                <div class="cart-item-info">
                    <h3>Product Name</h3>
                    <p>Brand: Brand Name</p>
                    <p>Price: $45.00</p>
                    <p>Quantity: 
                        <input type="number" value="1" min="1">
                    </p>
                </div>
                <button class="remove-item">Remove</button>
            </div>

            <!-- Add more cart items dynamically here based on session/cart data -->
        </div>

        <div class="cart-summary">
            <h3>Cart Summary</h3>
            <p>Total Items: 3</p>
            <p>Total Price: $135.00</p>
            <button class="checkout-button">Proceed to Checkout</button>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="footer-container">
            <div class="footer-section contact">
                <h3>Contact Us</h3>
                <p>Email: support@shopease.com</p>
                <p>Phone: +123 456 7890</p>
                <p>Address: 123 E-commerce St, ShopCity, SC 54321</p>
            </div>
            <div class="footer-section links">
                <h3>Quick Links</h3>
                <ul>
                    <li><a href="about.php">About Us</a></li>
                    <li><a href="#">Privacy Policy</a></li>
                    <li><a href="#">Terms of Service</a></li>
                    <li><a href="#">FAQ</a></li>
                </ul>
            </div>
            <div class="footer-section social">
                <h3>Follow Us</h3>
                <ul class="social-icons">
                    <li><a href="#"><img src="../Images/facebook-icon.png" alt="Facebook"></a></li>
                    <li><a href="#"><img src="../Images/twitter-icon.png" alt="Twitter"></a></li>
                    <li><a href="#"><img src="../Images/instagram-icon.png" alt="Instagram"></a></li>
                    <li><a href="#"><img src="../Images/linkedin-icon.png" alt="LinkedIn"></a></li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2024 ShopEase. All rights reserved.</p>
        </div>
    </footer>

    <script src="../Js/script.js"></script>
</body>
</html>
