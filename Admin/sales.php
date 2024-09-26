<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Management</title>
    <link rel="stylesheet" href="../Css/adminstyle.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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
            <h2>Sales Management</h2>
            <div class="button-container">
                <a href="addsale.php" class="btn btn-primary"><i class="fas fa-plus"></i> Add Sale</a>
            </div>

            <!-- Sales Table -->
            <table class="sales-table">
                <thead>
                    <tr>
                        <th>Sale ID</th>
                        <th>Product Name</th>
                        <th>Quantity Sold</th>
                        <th>Total Amount</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Example Sale Row -->
                    <tr>
                        <td>2001</td>
                        <td>Example Product</td>
                        <td>5</td>
                        <td>$99.95</td>
                        <td>2024-09-15</td>
                        <td>
                            <a href="viewsale.php?id=2001" class="btn btn-view"><i class="fas fa-eye"></i> View</a>
                            <a href="updatesale.php?id=2001" class="btn btn-edit"><i class="fas fa-edit"></i> Edit</a>
                            <a href="deletesale.php?id=2001" class="btn btn-delete"><i class="fas fa-trash"></i> Delete</a>
                        </td>
                    </tr>
                    <!-- Repeat rows as needed -->
                </tbody>
            </table>
        </div>
    </div>

    <script src="adminscript.js"></script>
</body>
</html>
