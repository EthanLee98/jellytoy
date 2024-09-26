<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - ShopEase</title>
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

    <!-- Checkout Section -->
    <section class="checkout-container">
        <h2>Checkout</h2>

        <div class="checkout-forms">
            <!-- Billing Information -->
            <div class="billing-info">
                <h3>Billing Information</h3>
                <form action="process_checkout.php" method="POST">
                    <label for="full-name">Full Name:</label>
                    <input type="text" id="full-name" name="full_name" required>

                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>

                    <label for="phone">Phone Number:</label>
                    <input type="text" id="phone" name="phone" required>

                    <label for="address">Address:</label>
                    <input type="text" id="address" name="address" required>

                    <label for="city">City:</label>
                    <input type="text" id="city" name="city" required>

                    <label for="state">State:</label>
                    <input type="text" id="state" name="state" required>

                    <label for="zip">Zip Code:</label>
                    <input type="text" id="zip" name="zip" required>
                </form>
            </div>

            <!-- Shipping Information -->
            <div class="shipping-info">
                <h3>Shipping Information</h3>
                <form action="process_checkout.php" method="POST">
                    <label for="shipping-full-name">Full Name:</label>
                    <input type="text" id="shipping-full-name" name="shipping_full_name" required>

                    <label for="shipping-address">Address:</label>
                    <input type="text" id="shipping-address" name="shipping_address" required>

                    <label for="shipping-city">City:</label>
                    <input type="text" id="shipping-city" name="shipping_city" required>

                    <label for="shipping-state">State:</label>
                    <input type="text" id="shipping-state" name="shipping_state" required>

                    <label for="shipping-zip">Zip Code:</label>
                    <input type="text" id="shipping-zip" name="shipping_zip" required>
                </form>
            </div>
        </div>

        <!-- Order Summary -->
        <div class="order-summary">
            <h3>Order Summary</h3>
            <div class="order-item">
                <p>Product Name 1 x 2 - $90.00</p>
            </div>
            <div class="order-item">
                <p>Product Name 2 x 1 - $45.00</p>
            </div>

            <p><strong>Total: $135.00</strong></p>
            <button type="submit" class="checkout-button">Place Order</button>
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
