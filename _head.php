<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $_title ?? 'Untitled' ?></title>
    <link rel="shortcut icon" href="/images/favicon/favicon.ico">
    <link rel="stylesheet" type="text/css" href="/css/_head.css">
    <link rel="stylesheet" type="text/css" href="/css/_foot.css">
    <link rel="stylesheet" type="text/css" href="/css/app.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css" />

    <?php
    $height = ($currentPage === 'index') ? '100vh' : '100px';
    ?>

    <style>
        #preloader {
            background: black url(/images/preloader_alt.gif) no-repeat center center;
            height: 100vh;
            width: 100%;
            position: fixed;
            z-index: 100;
            transition: transform 0.5s ease-in-out;
        }

        .main {
            height: <?php echo $height; ?>;
            background-color: #FFF;
            border-bottom: 1px solid #e5e5e5;
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            <?php if ($currentPage === 'index'): ?>background-image: url('https://images.unsplash.com/photo-1485470733090-0aae1788d5af?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1517&q=80');
            <?php endif; ?>
        }
        
        <?php if ($currentPage === 'index'): ?>
            .navbar > ul > .input-box {
                background-color: transparent !important;
            }
            
            .input-box input {
                color: #FFF;
            }

            .uil-search:before, .input-box input::placeholder {
                color: #FFF;
            }
        <?php endif; ?>
        <?php
        if ($currentPage !== 'index') {
            $color = "dark";
        }
        ?>
    </style>
</head>

<body class="<?= $color ?>">
    <div id="preloader"></div>

    <!-- Flash message -->
    <div id="info"><?= temp('info') ?></div>
    <?php
    if (isset($_SESSION['toast'])) {
        $toastType = $_SESSION['toast']['type'];
        $toastMessage = $_SESSION['toast']['message'];
        unset($_SESSION['toast']);
    }
    ?>
    <ul class="notifications"></ul>

    <div class="main">
        <header>
            <div class="promo-bar">
                Summer Sale For All Swim Suits And Free Express Delivery - OFF 50%! SHOP NOW
            </div>
            <div class="navbar">
                <div class="logo"><a href="/"><img src="/images/logo_text.png" alt="logo" width="165px"></a></div>
                <ul class="links">
                    <li><a href="/">Home</a></li>
                    <li><a href="/product/product_list.php">Product</a></li>
                    <li><a href="/order/cart.php">Cart</a></li>
                    <li><a href="#">Contact</a></li>
                </ul>
                <ul>
                    <div class="input-box">
                        <i class="uil uil-search"></i>
                        <input type="text" placeholder="Search here..." />
                        <button class="button">Search</button>
                    </div>
                </ul>
                <ul class="links_btn">
                    <a href="/order/cart.php"><i class="fas fa-shopping-cart"></i></a>
                    <?php if (isset($_SESSION['user'])): ?>
                        <?php
                        $profilePicture = !empty($_SESSION['user']['photo']) ? $_SESSION['user']['photo'] : 'person_default.png';
                        ?>
                        <a href="/user/userProfile.php"><img src="/images/members/<?php echo $profilePicture; ?>" alt="User Image" class="user-image"></a>
                        <span><?= $_SESSION['user']['name']; ?> (<?= $_SESSION['user']['role']?>)</span>
                        <a href="/logout.php" class="action_btn">Logout</a>
                    <?php else: ?>
                        <a href="/login.php" class="action_btn">Login</a>
                        <a href="/user/register.php" class="action_btn">Register</a>
                    <?php endif; ?>
                    <div class="toggle_btn">
                        <div class="bar1"></div>
                        <div class="bar2"></div>
                        <div class="bar3"></div>
                    </div>
                </ul>
            </div>

            <div class="dropdown_menu">
                <li class="nav_button"><a href="/">Home</a></li>
                <li class="nav_button"><a href="/product/product_list.php">Product</a></li>
                <li class="nav_button"><a href="/order/cart.php">Cart</a></li>
                <li class="nav_button"><a href="#">Contact</a></li>
                <?php if (isset($_SESSION['user'])): ?>
                    <li class="nav_profile_button">
                        <div class="dropdown_menu_profile_icon">
                            <?php
                            $profilePicture = !empty($_SESSION['user']['photo']) ? $_SESSION['user']['photo'] : 'person_default.png';
                            ?>
                            <a href="/user/userProfile.php"><img src="/images/members/<?php echo $profilePicture; ?>" alt="User Image" class="user-image"></a>
                            <span><?php echo $_SESSION['user']['name']; ?></span>
                        </div>
                    </li>
                    <li class="nav_logout_button">
                        <a href="/logout.php" class="action_btn">Logout</a>
                    </li>
                <?php else: ?>
                    <li>
                        <a href="/login.php" class="action_btn">Login</a>&nbsp;
                        <a href="/user/register.php" class="action_btn">Register</a>
                    </li>
                <?php endif; ?>
            </div>
        </header>

        <?php if ($currentPage === 'index'): ?>
            <section id="hero">
                <h1>Welcome to <span></span></h1>
                <p>
                    Your satisfaction. Our mission.
                </p>
            </section>
        <?php endif; ?>
    </div>