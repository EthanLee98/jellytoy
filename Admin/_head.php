<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <!-- Font Awesome for Icons -->
    <link rel="shortcut icon" href="/images/favicon/favicon.ico">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/css/adminstyle.css">
    <style>
        #preloader {
            background: black url(/images/preloader.gif) no-repeat center center;
            height: 100vh;
            width: 100%;
            position: fixed;
            z-index: 1000;
            transition: transform 0.5s ease-in-out;
        }
    </style>
</head>

<body>
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

    <div class="sidebar">
        <div class="logo-details">
            <a href="/admin"><img src="/images/logo_text.png" alt="logo" width="200 px"></a>
        </div>
        <ul class="nav-links">
            <li>
                <a href="index.php" class="<?php echo $currentPage === 'index' ? 'active' : ''; ?>">
                    <i class="fas fa-home"></i>
                    <span class="links_name">Dashboard</span>
                </a>
            </li>
            <li>
                <a href="/admin/productspages.php" class="<?php echo $currentPage === 'productspages' ? 'active' : ''; ?>">
                    <i class="fas fa-plus"></i>
                    <span class="links_name">Product Management</span>
                </a>
            </li>
            <li>
                <a href="order.php" class="<?php echo $currentPage === 'order' ? 'active' : ''; ?>">
                    <i class="fas fa-shopping-cart"></i>
                    <span class="links_name">Orders Management</span>
                </a>
            </li>
            <li>
                <a href="member.php" class="<?php echo $currentPage === 'member' ? 'active' : ''; ?>">
                    <i class="fas fa-users"></i>
                    <span class="links_name">Members Management</span>
                </a>
            </li>
            <li>
                <a href="sales.php" class="<?php echo $currentPage === 'sales' ? 'active' : ''; ?>">
                    <i class="fas fa-chart-line"></i>
                    <span class="links_name">Sales Management</span>
                </a>
            </li>
            <li>
                <a href="#" class="<?php echo $currentPage === 'settings' ? 'active' : ''; ?>">
                    <i class="fas fa-cog"></i>
                    <span class="links_name">Settings</span>
                </a>
            </li>
            <li class="log_out">
                <a href="#">
                    <i class='bx bx-log-out'></i>
                    <span class="links_name">Log out</span>
                </a>
            </li>
        </ul>
    </div>

    <section class="home-section">
        <nav>
            <div class="sidebar-button">
                <i class='bx bx-menu sidebarBtn'></i>
                <span class="dashboard">Dashboard</span>
            </div>
            <div class="profile-details">
                <img src="images/profile.jpg" alt="">
                <span class="admin_name">Prem Shahi</span>
                <i class='bx bx-chevron-down'></i>
            </div>
        </nav>

        <div class="home-content">