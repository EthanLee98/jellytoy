<?php
include '_base.php';

// ----------------------------------------------------------------------------



// ----------------------------------------------------------------------------

$_title = 'Lozodo - Product Management';
include '_head.php';
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

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
                        <th>*</th> <!-- New column for row number -->
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
                        $row_counter = 1; // Initialize row counter to 1

                        // Output data of each row
                        while ($row = mysqli_fetch_assoc($result)) {
                            // Pad the ID with leading zeros, making it 4 digits long and prefix with 'P'
                            $formatted_id = 'P' . str_pad($row['id'], 4, '0', STR_PAD_LEFT);

                            // Extract YouTube video ID from the video URL
                            $videoID = '';
                            if (!empty($row['video_url'])) {
                                preg_match('/(?:https?:\/\/)?(?:www\.)?(?:youtube\.com\/(?:[^\/\n\s]+\/\S+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([a-zA-Z0-9_-]{11})/', $row['video_url'], $matches);
                                if (isset($matches[1])) {
                                    $videoID = $matches[1];
                                }
                            }

                            echo "<tr>
                    <td>{$row_counter}</td> <!-- Display the row number without an asterisk -->
                    <td>{$formatted_id}</td>
                    <td>{$row['name']}</td>
                    <td>{$row['brand']}</td>
                    <td>{$row['description']}</td>
                    <td>\${$row['price']}</td>
                    <td>{$row['stock']}</td>
                     <td><img src='../images/products/{$row['photo1']}' alt='Photo1' width='50'></td>
                                <td><img src='../images/products/{$row['photo2']}' alt='Photo2' width='50'></td>
                                <td><img src='../images/products/{$row['photo3']}' alt='Photo3' width='50'></td>
                    <td>" . ($videoID ? "<iframe class='video-iframe' src='https://www.youtube.com/embed/$videoID' allowfullscreen></iframe>" : "No Video") . "</td>
                    <td>{$row['category_name']}</td>
                    <td>{$row['date_created']}</td>
                    <td>
                        <a href='updateproduct.php?id={$row['id']}' class='btn btn-edit'><i class='fas fa-edit'></i> Edit</a>
                        <a href='deleteproduct.php?id={$row['id']}' class='btn btn-delete'><i class='fas fa-trash'></i> Delete</a>
                    </td>
                </tr>";

                            $row_counter++; // Increment the row counter for each row
                        }
                    } else {
                        echo "<tr><td colspan='12'>No products found</td></tr>"; // Adjusted colspan to fit the new column count
                    }
                    mysqli_close($conn); // Close the connection
                    ?>
                </tbody>
            </table>
</div>
</div>

<?php
include '_foot.php';
?>