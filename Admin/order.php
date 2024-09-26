<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Management</title>
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
            <h2>Order Management</h2>
            
            <!-- Sorting and Add Order Button -->
            <div class="top-bar">
                <div class="sorting-options">
                    <label>Sort By:</label>
                    <button class="btn btn-sort" onclick="sortTable('id')">Order ID</button>
                    <button class="btn btn-sort" onclick="sortTable('date')">Order Date</button>
                    <button class="btn btn-sort" onclick="sortTable('status')">Status</button>
                    <button class="btn btn-sort" onclick="sortTable('total')">Total Amount</button>
                </div>
                <div class="button-container">
                    <a href="addorder.php" class="btn btn-primary"><i class="fas fa-plus"></i> Add Order</a>
                </div>
            </div>

            <!-- Orders Table -->
            <table class="order-table">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Order Date</th>
                        <th>Status</th>
                        <th>Total Amount</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Example Order Row -->
                    <tr>
                        <td>1</td>
                        <td>2024-01-20</td>
                        <td>Shipped</td>
                        <td>$39.99</td>
                        <td>
                            <a href="vieworder.php?id=1" class="btn btn-view"><i class="fas fa-eye"></i> View</a>
                            <a href="updateorder.php?id=1" class="btn btn-edit"><i class="fas fa-edit"></i> Edit</a>
                            <a href="deleteorder.php?id=1" class="btn btn-delete"><i class="fas fa-trash"></i> Delete</a>
                        </td>
                    </tr>
                    <!-- Repeat rows as needed -->
                </tbody>
            </table>
        </div>
    </div>

    <script src="adminscript.js"></script>
    <script>
        function sortTable(column) {
            // Implement sorting logic here, possibly by using JavaScript to sort table rows.
            console.log('Sorting by ' + column);
        }
    </script>
</body>
</html>
