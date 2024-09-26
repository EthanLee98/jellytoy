<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="../Css/adminstyle.css">
</head>
<body>
    <div class="wrapper">
        <!-- Top Navigation Bar -->
        <header class="top-nav">
            <div class="logo">
                <h2>Admin Panel</h2>
            </div>
            <div class="user-info">
                <span><i class="fas fa-user-circle"></i> Welcome, Admin</span>
                <a href="logout.php" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>
        </header>

        <!-- Sidebar Navigation -->
        <nav class="sidebar">
            <ul class="nav-list">
                <li><a href="adminhome.php" onclick="showSection('dashboard')"><i class="fas fa-home"></i> Dashboard</a></li>
                <li><a href="productspages.php" onclick="showSection('addProduct')"><i class="fas fa-plus"></i> Add Product</a></li>
                <li><a href="order.php" onclick="showSection('order')"><i class="fas fa-shopping-cart"></i> Orders</a></li>
                <li><a href="member.php" onclick="showSection('member')"><i class="fas fa-users"></i> Members</a></li>
                <li><a href="sales.php" onclick="showSection('sales')"><i class="fas fa-chart-line"></i> Sales</a></li>
                <li><a href="#" onclick="showSection('settings')"><i class="fas fa-cog"></i> Settings</a></li>
            </ul>
        </nav>

        <!-- Main Content -->
        <div class="content">
            <!-- Dashboard Section -->
            <div id="dashboard" class="section active">
                <h2>Welcome, Admin</h2>
                <div class="welcome-box">
                    <h3>Dashboard Overview</h3>
                    <p>This is your admin panel. From here, you can manage products, orders, members, sales, and settings.</p>
                    <div class="stats-grid">
                        <div class="stat-box">
                            <i class="fas fa-box"></i>
                            <h4>Products</h4>
                            <p>200 Products</p>
                        </div>
                        <div class="stat-box">
                            <i class="fas fa-shopping-cart"></i>
                            <h4>Orders</h4>
                            <p>500 Orders</p>
                        </div>
                        <div class="stat-box">
                            <i class="fas fa-users"></i>
                            <h4>Members</h4>
                            <p>300 Members</p>
                        </div>
                        <div class="stat-box">
                            <i class="fas fa-dollar-sign"></i>
                            <h4>Sales</h4>
                            <p>$15,000 Total Sales</p>
                        </div>
                        <div class="stat-box">
                            <i class="fas fa-coins"></i>
                            <h4>Profit</h4>
                            <p>$5,000 Profit</p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Additional sections like Add Product, Orders, Members, Sales, etc. -->
        </div>
    </div>

    <script src="adminscript.js"></script>
</body>
</html>
