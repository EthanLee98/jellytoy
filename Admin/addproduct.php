<?php
// Include the database connection file
include 'connectdb.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.2/dropzone.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.2/dropzone.min.js"></script>
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

        /* Dropzone */
        #image-dropzone {
            margin: 20px 0;
            padding: 30px;
            border: 2px dashed #007bff;
            background-color: #f9f9f9;
            text-align: center;
        }

        /* Image preview thumbnails */
        .dz-image img {
            width: 100px;
            height: auto;
        }
    </style>
    <script>
        Dropzone.autoDiscover = false;

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

        document.addEventListener("DOMContentLoaded", function() {
            var uploadedImages = [];
            var imageDropzone = new Dropzone("#image-dropzone", {
                url: "upload_images.php",
                paramName: "images[]", // The name that will be used to transfer the file
                maxFilesize: 5, // Max file size in MB
                maxFiles: 10, // Max number of images
                acceptedFiles: "image/*", // Only accept images
                addRemoveLinks: true,
                dictDefaultMessage: "Drag & Drop images here or click to upload",
                init: function() {
                    this.on("success", function(file, response) {
                        uploadedImages.push(response); // Store the image path

                        // Append image path to hidden input
                        let hiddenInput = document.createElement("input");
                        hiddenInput.type = "hidden";
                        hiddenInput.name = "uploaded_images[]"; // Use an array for multiple images
                        hiddenInput.value = response; // Path returned from upload_images.php
                        document.querySelector("form").appendChild(hiddenInput);
                    });
                }
            });
        });
    </script>
</head>

<body>
    <div class="wrapper">
        <div class="content">
            <h2>Add New Product</h2>
            <form action="add_product.php" method="POST" enctype="multipart/form-data">
                <label for="name">Product Name:</label>
                <input type="text" id="name" name="name" required>

                <label for="brand">Brand:</label>
                <input type="text" id="brand" name="brand" required>

                <label for="description">Description:</label>
                <textarea id="description" name="description" rows="4" required></textarea>

                <label for="price">Price:</label>
                <input type="number" id="price" name="price" step="0.01" required>

                <label for="stock">Stock:</label>
                <input type="number" id="stock" name="stock" required>

                <label for="category_id">Category:</label>
                <select id="category_id" name="category_id" required>
                    <!-- Populate category options from database -->
                    <option value="">Select a Category</option>
                    <?php
                    // Fetch categories from the database
                    $query = "SELECT id, name FROM category";
                    $result = mysqli_query($conn, $query);

                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<option value='" . $row['id'] . "'>" . $row['name'] . "</option>";
                        }
                    } else {
                        echo "<option value=''>No categories available</option>";
                    }
                    ?>
                </select>

                <!-- Dropzone for uploading images -->
                <label for="images">Product Images:</label>
                <div id="image-dropzone" class="dropzone"></div>

                <label for="video_url">Product Video URL (YouTube):</label>
                <input type="text" id="video_url" name="video_url" oninput="updateVideoPreview()" placeholder="https://www.youtube.com/watch?v=VIDEO_ID" required>

                <iframe id="video_preview" src="" frameborder="0" allowfullscreen></iframe>

                <button type="submit">Add Product</button>
            </form>
        </div>
    </div>
</body>

</html>