    </main>
    <section class="footer">
        <div class="footer-row">
            <div class="footer-col">
                <h4 class="footer-title">Info</h4>
                <ul class="links">
                    <li><a href="#">About Us</a></li>
                    <li><a href="#">Compressions</a></li>
                    <li><a href="#">Customers</a></li>
                    <li><a href="#">Service</a></li>
                    <li><a href="#">Collection</a></li>
                </ul>
            </div>

            <div class="footer-col">
                <h4 class="footer-title">Explore</h4>
                <ul class="links">
                    <li><a href="#">Free Designs</a></li>
                    <li><a href="#">Latest Designs</a></li>
                    <li><a href="#">Themes</a></li>
                    <li><a href="#">Popular Designs</a></li>
                    <li><a href="#">Art Skills</a></li>
                    <li><a href="#">New Uploads</a></li>
                </ul>
            </div>

            <div class="footer-col">
                <h4 class="footer-title">Legal</h4>
                <ul class="links">
                    <li><a href="#">Customer Agreement</a></li>
                    <li><a href="#">Privacy Policy</a></li>
                    <li><a href="#">GDPR</a></li>
                    <li><a href="#">Security</a></li>
                    <li><a href="#">Testimonials</a></li>
                    <li><a href="#">Media Kit</a></li>
                </ul>
            </div>

            <!-- Contact Us -->
            <div class="footer-col">
                <h4 class="footer-title">Contact Us</h4>
                <p>
                    Your satisfaction. Our mission.
                </p>
                <ul class="links">
                    <li><a href="https://www.google.com/maps?q=419+State+414+Rte+Beaver+Dams,+New+York+(NY),+14812,+USA" target="_blank"><i class="fas fa-map-marker-alt"></i> 419 State 414 Rte Beaver Dams, New York (NY), 14812, USA</a></li>
                    <li><a href="tel:+16079368058"><i class="fas fa-phone"></i> (607) 936-8058</a></li>
                    <li><a href="mailto:JellyToy@business.com.my"><i class="fas fa-envelope"></i> JellyToy@business.com.my</a></li>
                </ul>
                <div class="icons">
                    <i class="fa-brands fa-facebook-f"></i>
                    <i class="fa-brands fa-twitter"></i>
                    <i class="fa-brands fa-linkedin"></i>
                    <i class="fa-brands fa-github"></i>
                </div>
                <!-- Embedded Map -->
                <iframe class="footer-map"
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3153.8353122437034!2d-122.4013788843152!3d37.784746979756796!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x8085817c7d0a0437%3A0x79cba12345b123c7!2sSOMA%2C%20San%20Francisco%2C%20CA!5e0!3m2!1sen!2sus!4v1619238463455!5m2!1sen!2sus"
                    width="100%" height="150" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
            </div>
    </section>
    <div class="footer-bottom">
        <p>Copyright © JellyToy All Rights Reserved. @ <?= date('Y') ?></p>
    </div>

    <script src="/lib/jquery.min.js"></script>
    <script src="/js/app.js"></script>
    <script>
        var loader = document.getElementById("preloader");
        window.addEventListener("load", function() {
            setTimeout(function() {
                loader.style.transform = "translateY(-100%)";
            }, <?php echo rand(150, 500); ?>);

            setTimeout(function() {
                loader.style.display = "none";
            }, 1000);
        });
    </script>

    <!-- Link to the JS file that has the same name with PHP file -->
    <?php
    $current_page = basename($_SERVER['PHP_SELF'], '.php');
    $specific_css = '/js/' . $current_page . '.js';
    echo '<script src="' . htmlspecialchars($specific_css) . '"></script>';
    ?>

    </body>

    </html>