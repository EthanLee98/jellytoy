    </main>
    <footer class="custom-footer">
        <div class="custom-footer-container">

            <!-- Products -->
            <div class="custom-footer-column">
                <h3 class="footer-title">Products</h3>
                <ul class="custom-footer-list">
                    <li><a href="#">Prices Drop</a></li>
                    <li><a href="#">New Products</a></li>
                    <li><a href="#">Best Sales</a></li>
                    <li><a href="#">Contact Us</a></li>
                    <li><a href="#">Sitemap</a></li>
                </ul>
            </div>

            <!-- Our Company -->
            <div class="custom-footer-column">
                <h3 class="footer-title">Our Company</h3>
                <ul class="custom-footer-list">
                    <li><a href="#">Delivery</a></li>
                    <li><a href="#">Legal Notice</a></li>
                    <li><a href="#">Terms and Conditions</a></li>
                    <li><a href="#">About Us</a></li>
                    <li><a href="#">Secure Payment</a></li>
                </ul>
            </div>

            <!-- Services -->
            <div class="custom-footer-column">
                <h3 class="footer-title">Services</h3>
                <ul class="custom-footer-list">
                    <li><a href="#">Prices Drop</a></li>
                    <li><a href="#">New Products</a></li>
                    <li><a href="#">Best Sales</a></li>
                    <li><a href="#">Contact Us</a></li>
                    <li><a href="#">Sitemap</a></li>
                </ul>
            </div>

            <!-- Contact Us -->
            <div class="custom-footer-column custom-footer-contact">
                <h3 class="footer-title">Contact</h3>
                <ul class="custom-footer-list">
                    <li><i class="fas fa-map-marker-alt"></i> 419 State 414 Rte Beaver Dams, New York(NY), 14812, USA</li>
                    <li><i class="fas fa-phone"></i> (607) 936-8058</li>
                    <li><i class="fas fa-envelope"></i> JellyToy@business.com.my</li>
                </ul>
                <!-- Embedded Map -->
                <iframe class="custom-footer-map" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3153.8353122437034!2d-122.4013788843152!3d37.784746979756796!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x8085817c7d0a0437%3A0x79cba12345b123c7!2sSOMA%2C%20San%20Francisco%2C%20CA!5e0!3m2!1sen!2sus!4v1619238463455!5m2!1sen!2sus"
                    width="100%" height="150" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
            </div>
        </div>
        <div class="custom-footer-bottom">
            <p>Copyright © JellyToy All Rights Reserved. @ <?= date('Y') ?></p>

        </div>
    </footer>

    <script src="/lib/jquery.min.js"></script>
    <script src="/js/app.js"></script>
    <script>
        var loader = document.getElementById("preloader");
    window.addEventListener("load", function() {
            setTimeout(function() {
                loader.style.transform = "translateY(-100%)";
            }, <?php echo rand(150, 1000); ?>);

            setTimeout(function() {
                loader.style.display = "none";
            }, 1000);
        });
    </script>
    </body>

    </html>