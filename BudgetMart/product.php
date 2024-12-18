<?php
session_start();

// Initialize the cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Handle adding a product to the cart
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product = [
        'name' => $_POST['name'],
        'price' => $_POST['price'],
        'image' => $_POST['image']
    ];
    
    // Add the product to the session cart
    $_SESSION['cart'][] = $product;
    header("Location: cart.php"); // Redirect to cart page after adding the product
    exit();
}

// Database connection
$conn = new mysqli("localhost", "root", "", "user_accounts");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product List</title>
    <!-- Include TailwindCSS -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<?php include('templates/header.php'); ?>

<body class="bg-gray-100 text-gray-800">
    <div class="container mx-auto py-8">
        <h1 class="text-2xl font-bold text-center mb-6">Product List</h1>

        <div class="overflow-x-auto">
            <table class="min-w-full bg-white shadow-md rounded-lg overflow-hidden">
                <thead class="bg-gray-800 text-white">
                    <tr>
                        <th class="py-3 px-6 text-left">Name</th>
                        <th class="py-3 px-6 text-left">Description</th>
                        <th class="py-3 px-6 text-left">Price</th>
                        <th class="py-3 px-6 text-left">Image</th>
                        <th class="py-3 px-6 text-left">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php
                    // Fetch products from the database
                    $sql = "SELECT id, name, description, price, image FROM products";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $imagePath = "uploads/" . htmlspecialchars($row['image']); // Ensure correct image path
                            echo "<tr class='hover:bg-gray-100'>
                                    <td class='py-3 px-6'>
                                        <a href='product_detail.php?id=" . $row['id'] . "' class='text-blue-600 hover:text-blue-800'>" . htmlspecialchars($row['name']) . "</a>
                                    </td>
                                    <td class='py-3 px-6'>" . htmlspecialchars($row['description']) . "</td>
                                    <td class='py-3 px-6'>Rs. " . htmlspecialchars($row['price']) . "</td>
                                    <td class='py-3 px-6'>
                                        <img src='$imagePath' alt='Product Image' class='w-20 h-auto rounded-lg'>
                                    </td>
                                    <td class='py-3 px-6'>
                                        <form action='' method='POST'>
                                            <input type='hidden' name='name' value='" . htmlspecialchars($row['name']) . "'>
                                            <input type='hidden' name='price' value='" . htmlspecialchars($row['price']) . "'>
                                            <input type='hidden' name='image' value='" . htmlspecialchars($row['image']) . "'>
                                            <button type='submit' class='bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600'>
                                                Add to Cart
                                            </button>
                                        </form>
                                    </td>
                                  </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='5' class='text-center text-gray-500 py-4'>No products found</td></tr>";
                    }

                    // Close connection
                    $conn->close();
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>

<?php include('templates/footer.php'); ?>

</html>
