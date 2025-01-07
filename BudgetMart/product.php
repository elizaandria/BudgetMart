<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product List</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<?php include('templates/header.php'); ?>

<body class="bg-gray-100 text-gray-800">
    <div class="container mx-auto px-6 sm:px-8 md:px-12 pl-16 py-8"> <!-- Added pl-16 for extra left padding -->
        <h1 class="text-5xl font-bold text-center mb-6">Products</h1>

        <!-- Category Filter Section on the Right -->
        <div class="flex justify-end mb-6">
            <div class="flex space-x-4">
                <a href="?category=" class="bg-gray-200 text-gray-800 px-4 py-2 rounded hover:bg-blue-500 hover:text-white">All</a>
                <a href="?category=Electronics" class="bg-gray-200 text-gray-800 px-4 py-2 rounded hover:bg-blue-500 hover:text-white">Electronics</a>
                <a href="?category=Sports" class="bg-gray-200 text-gray-800 px-4 py-2 rounded hover:bg-blue-500 hover:text-white">Sports</a>
                <a href="?category=Clothes" class="bg-gray-200 text-gray-800 px-4 py-2 rounded hover:bg-blue-500 hover:text-white">Clothes</a>
                <a href="?category=Kids" class="bg-gray-200 text-gray-800 px-4 py-2 rounded hover:bg-blue-500 hover:text-white">Kids</a>
                <a href="?category=Furniture" class="bg-gray-200 text-gray-800 px-4 py-2 rounded hover:bg-blue-500 hover:text-white">Furniture</a>
            </div>
        </div>

        <!-- Product Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8">
            <?php
            // Database connection
            $conn = new mysqli("localhost", "root", "", "user_accounts");

            // Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Get category filter from GET request
            $categoryFilter = isset($_GET['category']) ? $_GET['category'] : '';

            // Query to fetch products with optional category filter
            $sql = "SELECT id, name, description, price, image, category FROM products";

            if ($categoryFilter) {
                $sql .= " WHERE category = '" . $conn->real_escape_string($categoryFilter) . "'";
            }

            $result = $conn->query($sql);

            // Display products
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $imagePath = "uploads/" . htmlspecialchars($row['image']);
                    echo "<div class='bg-white rounded-lg shadow-lg overflow-hidden w-72'>
                            <img src='$imagePath' alt='Product Image' class='w-full h-40 object-cover'>
                            <div class='p-4'>
                                <h3 class='text-lg font-bold text-gray-800'>
                                    <a href='product_detail.php?id=" . $row['id'] . "' class='text-blue-600 hover:text-blue-800'>" . htmlspecialchars($row['name']) . "</a>
                                </h3>
                                <p class='text-gray-600 mt-2 text-sm'>" . htmlspecialchars($row['description']) . "</p>
                                <div class='flex items-center justify-between mt-4'>
                                    <span class='text-xl font-bold text-gray-800'>Rs. " . htmlspecialchars($row['price']) . "</span>
                                    <form action='' method='POST'>
                                        <input type='hidden' name='name' value='" . htmlspecialchars($row['name']) . "'>
                                        <input type='hidden' name='price' value='" . htmlspecialchars($row['price']) . "'>
                                        <input type='hidden' name='image' value='" . htmlspecialchars($row['image']) . "'>
                                        <button type='submit' class='bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600'>
                                            Add to Cart
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>";
                }
            } else {
                echo "<p class='text-center text-gray-500 col-span-full'>No products found</p>";
            }

            // Close connection
            $conn->close();
            ?>
        </div>
    </div>
</body>

<?php include('templates/footer.php'); ?>

</html>
