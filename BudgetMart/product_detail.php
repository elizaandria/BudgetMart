<?php
session_start();

// Database connection (replace with actual database connection code)
$conn = new mysqli("localhost", "root", "", "user_accounts");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the product ID from the URL
$product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Fetch product details from the database
$sql = "SELECT id, name, description, price, image FROM products WHERE id = $product_id";
$result = $conn->query($sql);
$product = $result->fetch_assoc();

if (!$product) {
    echo "Product not found.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($product['name']); ?> - Product Detail</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<?php include('templates/header.php'); ?>

<body class="bg-gray-100 text-gray-800">
    <div class="container mx-auto py-8">
        <div class="flex justify-center">
            <div class="max-w-4xl w-full flex bg-white shadow-md rounded-lg p-6">
                <!-- Product Image -->
                <div class="w-1/2 pr-8">
                    <img src="uploads/<?php echo htmlspecialchars($product['image']); ?>" alt="Product Image" class="w-full h-auto rounded-lg">
                </div>

                <!-- Product Details -->
                <div class="w-1/2">
                    <h1 class="text-3xl font-bold text-gray-800 mb-4"><?php echo htmlspecialchars($product['name']); ?></h1>
                    <p class="text-lg text-gray-700 mb-6"><?php echo htmlspecialchars($product['description']); ?></p>
                    <p class="text-xl font-semibold text-gray-800 mb-6">Rs. <?php echo htmlspecialchars($product['price']); ?></p>

                    <!-- Add to Cart Form -->
                    <form action="cart.php" method="POST">
                        <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                        <button type="submit" class="bg-blue-500 text-white px-6 py-3 rounded hover:bg-blue-600">Add to Cart</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

<?php include('templates/footer.php'); ?>

</html>

<?php $conn->close(); ?>
