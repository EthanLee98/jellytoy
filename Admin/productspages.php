<?php
include 'connectdb.php'; // Include the database connection

// Initialize the search query and sorting options if set
$search_query = "";
$sort_column = isset($_GET['sort_column']) ? $_GET['sort_column'] : 'id';
$sort_order = isset($_GET['sort_order']) ? $_GET['sort_order'] : 'ASC';

if (isset($_GET['search'])) {
    $search_query = mysqli_real_escape_string($conn, $_GET['search']);
}

// Toggle sorting order
$sort_order = ($sort_order === 'ASC') ? 'DESC' : 'ASC';

// Fetch products from the database based on search and sort
$sql = "SELECT p.*, c.name AS category_name 
        FROM product p 
        LEFT JOIN category c ON p.category_id = c.id 
        WHERE p.name LIKE '%$search_query%' 
        OR p.brand LIKE '%$search_query%' 
        OR p.description LIKE '%$search_query%' 
        OR c.name LIKE '%$search_query%'
        ORDER BY $sort_column $sort_order";

$result = mysqli_query($conn, $sql);

// Check if the query was successful
if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Management</title>
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
                <li><a href="adminhome.php"><i class="fas fa-home"></i> Dashboard</a></li>
                <li><a href="addproduct.php"><i class="fas fa-plus"></i> Add Product</a></li>
                <li><a href="order.php"><i class="fas fa-shopping-cart"></i> Orders</a></li>
                <li><a href="member.php"><i class="fas fa-users"></i> Members</a></li>
                <li><a href="sales.php"><i class="fas fa-chart-line"></i> Sales</a></li>
                <li><a href="#"><i class="fas fa-cog"></i> Settings</a></li>
            </ul>
        </nav>

        <!-- Main Content -->
        <div class="content">
            <h2>Product Management</h2>
            

            <!-- Search Bar -->
            <div class="top-bar">
                <form method="GET" action="productspages.php" class="search-form">
                    <div class="search-container">
                        <input type="text" name="search" placeholder="Search by name, brand, category..." value="<?php echo htmlspecialchars($search_query); ?>" class="search-input">
                        <button type="submit" class="search-btn"><i class="fas fa-search"></i></button>
                    </div>
                </form>

                <!-- Add Product Button -->
                <div class="button-container">
                    <a href="addproduct.php" class="btn btn-primary"><i class="fas fa-plus"></i> Add Product</a>
                </div>
            </div>


            <!-- Products Table -->
            <table class="product-table">
                <?php
                // Determine the opposite sort order for toggling between ASC and DESC
                function getSortIcon($column, $current_column, $current_order)
                {
                    if ($column == $current_column) {
                        return $current_order == 'ASC' ? 'fa-sort-up' : 'fa-sort-down';
                    }
                    return ''; // No icon if it's not the current sorted column
                }

                // Current sorting column and order (default to ID and ASC)
                $current_column = isset($_GET['sort_column']) ? $_GET['sort_column'] : 'id';
                $current_order = isset($_GET['sort_order']) && $_GET['sort_order'] == 'DESC' ? 'DESC' : 'ASC';

                // Toggle between ASC and DESC
                $next_order = ($current_order == 'ASC') ? 'DESC' : 'ASC';
                ?>
                <thead>
                    <tr>
                        <th>
                            <a href="?sort_column=id&sort_order=<?php echo $next_order; ?>" class="btn btn-primary">
                                ID <i class="fas <?php echo getSortIcon('id', $current_column, $current_order); ?>"></i>
                            </a>
                        </th>
                        <th>
                            <a href="?sort_column=name&sort_order=<?php echo $next_order; ?>" class="btn btn-primary">
                                Name <i class="fas <?php echo getSortIcon('name', $current_column, $current_order); ?>"></i>
                            </a>
                        </th>
                        <th>
                            <a href="?sort_column=brand&sort_order=<?php echo $next_order; ?>" class="btn btn-primary">
                                Brand <i class="fas <?php echo getSortIcon('brand', $current_column, $current_order); ?>"></i>
                            </a>
                        </th>
                        <th>
                            <a href="?sort_column=description&sort_order=<?php echo $next_order; ?>" class="btn btn-primary">
                                Description <i class="fas <?php echo getSortIcon('description', $current_column, $current_order); ?>"></i>
                            </a>
                        </th>
                        <th>
                            <a href="?sort_column=price&sort_order=<?php echo $next_order; ?>" class="btn btn-primary">
                                Price <i class="fas <?php echo getSortIcon('price', $current_column, $current_order); ?>"></i>
                            </a>
                        </th>
                        <th>
                            <a href="?sort_column=stock&sort_order=<?php echo $next_order; ?>" class="btn btn-primary">
                                Stock <i class="fas <?php echo getSortIcon('stock', $current_column, $current_order); ?>"></i>
                            </a>
                        </th>
                        <th>Photo1</th>
                        <th>Photo2</th>
                        <th>Photo3</th>
                        <th>Video</th>
                        <th>
                            <a href="?sort_column=category_name&sort_order=<?php echo $next_order; ?>" class="btn btn-primary">
                                Category <i class="fas <?php echo getSortIcon('category_name', $current_column, $current_order); ?>"></i>
                            </a>
                        </th>
                        <th>
                            <a href="?sort_column=date_created&sort_order=<?php echo $next_order; ?>" class="btn btn-primary">
                                Date Created <i class="fas <?php echo getSortIcon('date_created', $current_column, $current_order); ?>"></i>
                            </a>
                        </th>
                        <th>Actions</th>
                    </tr>
                </thead>


                <tbody>
                    <?php
                    if (mysqli_num_rows($result) > 0) {
                        // Output data of each row
                        while ($row = mysqli_fetch_assoc($result)) {
                            // Extract YouTube video ID from the video URL
                            $videoID = '';
                            if (!empty($row['video_url'])) {
                                preg_match('/(?:https?:\/\/)?(?:www\.)?(?:youtube\.com\/(?:[^\/\n\s]+\/\S+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([a-zA-Z0-9_-]{11})/', $row['video_url'], $matches);
                                if (isset($matches[1])) {
                                    $videoID = $matches[1];
                                }
                            }

                            echo "<tr>
                                <td>{$row['id']}</td>
                                <td>{$row['name']}</td>
                                <td>{$row['brand']}</td>
                                <td>{$row['description']}</td>
                                <td>\${$row['price']}</td>
                                <td>{$row['stock']}</td>
                                <td><img src='{$row['photo1']}' alt='Photo1' width='50'></td>
                                <td><img src='{$row['photo2']}' alt='Photo2' width='50'></td>
                                <td><img src='{$row['photo3']}' alt='Photo3' width='50'></td>
                                <td>" . ($videoID ? "<iframe class='video-iframe' src='https://www.youtube.com/embed/$videoID' allowfullscreen></iframe>" : "No Video") . "</td>
                                <td>{$row['category_name']}</td>
                                <td>{$row['date_created']}</td>
                                <td>
                                    <a href='updateproduct.php?id={$row['id']}' class='btn btn-edit'><i class='fas fa-edit'></i> Edit</a>
                                    <a href='deleteproduct.php?id={$row['id']}' class='btn btn-delete'><i class='fas fa-trash'></i> Delete</a>
                                </td>
                            </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='12'>No products found</td></tr>";
                    }
                    mysqli_close($conn); // Close the connection
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="adminscript.js"></script>
</body>

</html>