<?php
// Include the database connection file
include 'connectdb.php';

// Fetch the product to be updated
$product_id = $_GET['id']; // Get the product ID from the URL

$query = "SELECT * FROM product WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Product</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* Reset and General Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            display: flex;
            flex-direction: column;
            height: 100vh;
            background-color: #f4f4f4;
        }

        /* Top Navigation Bar */
        .top-nav {
            background-color: #333;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 20px;
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1;
        }

        .logo h2 {
            margin: 0;
        }

        .user-info {
            display: flex;
            align-items: center;
        }

        .user-info span {
            margin-right: 20px;
            font-size: 16px;
        }

        .logout-btn {
            color: white;
            text-decoration: none;
            font-size: 16px;
            transition: color 0.3s;
        }

        .logout-btn:hover {
            color: #ff6347;
        }

        /* Main Content */
        .wrapper {
            display: flex;
            margin-top: 50px;
            justify-content: center;
            height: auto;
        }

        .content {
            width: 600px;
            padding: 30px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            min-height: 800px;
            margin-bottom: 20px;
        }

        .content h2 {
            text-align: center;
            color: #333;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            margin: 10px 0 5px;
            color: #555;
        }

        input[type="text"],
        input[type="number"],
        textarea,
        select {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 14px;
            margin-bottom: 15px;
        }

        input[type="file"] {
            margin: 10px 0;
        }

        button {
            background-color: #007bff;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #0056b3;
        }

        /* Video preview */
        #video_preview {
            margin-top: 15px;
            width: 100%;
            height: 300px;
            border: 1px solid #ccc;
        }

        /* Existing images section */
        .existing-images img {
            margin-right: 10px;
            margin-top: 10px;
            width: 50px;
            height: auto;
            border: 1px solid #ddd;
        }
    </style>
    <script>
        function updateVideoPreview() {
            const videoUrl = document.getElementById('video_url').value;
            const videoID = getYouTubeID(videoUrl);
            const videoPreview = document.getElementById('video_preview');
            if (videoID) {
                videoPreview.src = 'https://www.youtube.com/embed/' + videoID;
            } else {
                videoPreview.src = '';
            }
        }

        function getYouTubeID(url) {
            const regex = /(?:https?:\/\/)?(?:www\.)?(?:youtube\.com\/(?:[^\/\n\s]+\/\S+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([a-zA-Z0-9_-]{11})/;
            const matches = url.match(regex);
            return matches ? matches[1] : null;
        }

        window.onload = function() {
            updateVideoPreview(); // Initialize video preview with current product's video URL
        }
    </script>
</head>

<body>
    <div class="top-nav">
        <div class="logo">
            <h2>Admin Panel</h2>
        </div>
        <div class="user-info">
            <span><i class="fas fa-user-circle"></i> Welcome, Admin</span>
            <a href="logout.php" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
    </div>

    <div class="wrapper">
        <div class="content">
            <h2>Update Product</h2>
            <form action="update_product.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?php echo $product['id']; ?>">

                <label for="name">Product Name:</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($product['name']); ?>" required>

                <label for="brand">Brand:</label>
                <input type="text" id="brand" name="brand" value="<?php echo htmlspecialchars($product['brand']); ?>" required>

                <label for="description">Description:</label>
                <textarea id="description" name="description" rows="4" required><?php echo htmlspecialchars($product['description']); ?></textarea>

                <label for="price">Price:</label>
                <input type="number" id="price" name="price" step="0.01" value="<?php echo $product['price']; ?>" required>

                <label for="stock">Stock:</label>
                <input type="number" id="stock" name="stock" value="<?php echo $product['stock']; ?>" required>

                <label for="category_id">Category:</label>
                <select id="category_id" name="category_id" required>
                    <option value="">Select a Category</option>
                    <?php
                    // Fetch categories from the database
                    $query = "SELECT id, name FROM category";
                    $result = mysqli_query($conn, $query);

                    // Loop through categories
                    while ($row = mysqli_fetch_assoc($result)) {
                        $selected = ($row['id'] == $product['category_id']) ? 'selected' : '';
                        echo "<option value='" . $row['id'] . "' $selected>" . $row['name'] . "</option>";
                    }
                    ?>
                </select>

                <label for="images">Product Images:</label>
                <input type="file" id="images" name="images[]" multiple>

                <!-- Display existing images for reference with checkboxes to remove -->
                <div class="existing-images">
                    <label>Existing Images:</label><br>
                    <?php if ($product['photo1']) { ?>
                        <div>
                            <input type="checkbox" name="remove_photos[]" value="photo1">
                            <img src="<?php echo $product['photo1']; ?>" alt="Photo1">
                        </div>
                    <?php } ?>
                    <?php if ($product['photo2']) { ?>
                        <div>
                            <input type="checkbox" name="remove_photos[]" value="photo2">
                            <img src="<?php echo $product['photo2']; ?>" alt="Photo2">
                        </div>
                    <?php } ?>
                    <?php if ($product['photo3']) { ?>
                        <div>
                            <input type="checkbox" name="remove_photos[]" value="photo3">
                            <img src="<?php echo $product['photo3']; ?>" alt="Photo3">
                        </div>
                    <?php } ?>
                </div>

                <label for="video_url">Product Video URL (YouTube):</label>
                <input type="text" id="video_url" name="video_url" value="<?php echo htmlspecialchars($product['video_url']); ?>" placeholder="https://www.youtube.com/watch?v=VIDEO_ID" oninput="updateVideoPreview()" required>

                <iframe id="video_preview" src="" frameborder="0" allowfullscreen></iframe>

                <button type="submit" class="btn btn-primary">Update Product</button>
            </form>
        </div>
    </div>
</body>

</html>
