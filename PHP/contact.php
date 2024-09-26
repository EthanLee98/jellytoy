<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - ShopEase</title>
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
                <li><a href="#">Cart</a></li>
            </ul>
        </nav>
    </header>

    <!-- Contact Us Section -->
    <section class="contact-container">
        <h2>Contact Us</h2>
        <p>If you have any questions, feel free to reach out to us. We'd love to hear from you!</p>

        <!-- Contact Form -->
        <form class="contact-form" action="send_message.php" method="POST">
            <label for="name">Your Name:</label>
            <input type="text" id="name" name="name" required>

            <label for="email">Your Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="message">Your Message:</label>
            <textarea id="message" name="message" required></textarea>

            <button type="submit">Send Message</button>
        </form>
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
