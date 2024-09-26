<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $_title ?? 'Untitled' ?></title>
    <link rel="shortcut icon" href="/images/favicon/android-chrome-192x192.png">
    <link rel="stylesheet" type="text/css" href="/css/_head.css">
    <link rel="stylesheet" type="text/css" href="/css/_foot.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="js/app.js"></script>

    <style>
        #preloader {
            background: black url(/images/preloader.gif) no-repeat center center;
            height: 100vh;
            width: 100%;
            position: fixed;
            z-index: 100;
            transition: transform 0.5s ease-in-out;
        }
    </style>
</head>

<body>
    <div id="preloader"></div>

    <!-- Flash message -->
    <div id="info"><?= temp('info') ?></div>

    <header class="custom-header-container">
        <div class="custom-promo-bar">
            Summer Sale For All Swim Suits And Free Express Delivery - OFF 50%! SHOP NOW
        </div>
        <nav class="custom-navbar">
            <div class="custom-logo">
                <a href="/index.php">JellyToy</a>
            </div>
            <ul class="custom-nav-links">
                <li><a href="/index.php">Home</a></li>
                <li><a href="#">About</a></li>
                <li><a href="#">Contact</a></li>
            </ul>
            <div class="custom-search-cart">
                <form action="/search.php" method="GET" class="custom-search-form">
                    <input type="text" name="query" placeholder="Search here..." required>
                    <button type="submit"><i class="fas fa-search"></i></button>
                </form>
                <a href="#"><i class="fas fa-heart"></i></a>
                <a href="#"><i class="fas fa-shopping-cart"></i></a>

                <!-- User Authentication (Login/Register or Profile) -->
                <div class="user-auth">
                    <?php if (isset($_SESSION['user'])): ?>
                        <div class="header-user-profile">
                            <?php if (!empty($_SESSION['user']['profile_picture'])): ?>
                                <img src="<?php echo $_SESSION['user']['profile_picture']; ?>" alt="User Image" class="header-user-icon">
                            <?php endif; ?>
                            <span><?php echo $_SESSION['user']['name']; ?></span>
                            <ul class="header-dropdown-menu">
                                <li><a href="/userProfile.php">My Profile</a></li>
                                <li><a href="/logout.php">Logout</a></li>
                            </ul>
                        </div>
                    <?php else: ?>
                        <a href="/login.php" class="auth-link">Login</a> / <a href="/user/registerMember.php" class="auth-link">Register</a>
                    <?php endif; ?>
                </div>
            </div>
        </nav>
    </header>



    <script src="/js/_head.js"></script>


    <main>

        <!-- Main content goes here -->