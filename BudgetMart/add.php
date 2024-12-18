<?php
include('db.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Initialize variables and handle missing fields
    $name = isset($_POST['name']) ? trim($_POST['name']) : null;
    $description = isset($_POST['description']) ? trim($_POST['description']) : null;
    $price = isset($_POST['price']) ? trim($_POST['price']) : null;
    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

    if (empty($name) || empty($price)) {
        echo "<p style='color: red;'>Product name and price are required.</p>";
    } elseif (!$user_id) {
        echo "<p style='color: red;'>You must be logged in to add a product.</p>";
    } else {
        // Handle Image Upload
        $image = null; // Default value for image
        if (!empty($_FILES['image']['name'])) {
            $target_dir = "uploads/";
            if (!file_exists($target_dir)) {
                mkdir($target_dir, 0777, true);
            }

            $image = basename($_FILES['image']['name']);
            $target_file = $target_dir . $image;

            // Validate and move the uploaded file
            if (!move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
                $image = null; // If upload fails, set image to null
            }
        }

        // Insert product data into the database
        $sql = "INSERT INTO products (name, description, price, image, user_id) 
                VALUES (:name, :description, :price, :image, :user_id)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':image', $image);
        $stmt->bindParam(':user_id', $user_id);

        try {
            if ($stmt->execute()) {
                echo "<p style='color: green;'>Product added successfully!</p>";
            } else {
                echo "<p style='color: red;'>Error adding product.</p>";
            }
        } catch (PDOException $e) {
            echo "<p style='color: red;'>Database error: " . htmlspecialchars($e->getMessage()) . "</p>";
        }
    }
}

// Delete functionality
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete'])) {
    $product_id = isset($_POST['product_id']) ? $_POST['product_id'] : null;

    if ($product_id) {
        $sql = "DELETE FROM products WHERE id = :product_id AND user_id = :user_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':product_id', $product_id);
        $stmt->bindParam(':user_id', $_SESSION['user_id']);

        if ($stmt->execute()) {
            echo "<p style='color: green;'>Product deleted successfully!</p>";
        } else {
            echo "<p style='color: red;'>Error deleting product.</p>";
        }
    }
}

// Fetch all products for the logged-in user
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
if ($user_id) {
    $sql = "SELECT id, name, description, price, image FROM products WHERE user_id = :user_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    $products = [];
    echo "<p style='color: red;'>Please log in to view your products.</p>";
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-800">
    <!-- Sidebar -->
    <div class="flex">
        <div class="w-1/4 bg-gray-800 text-white h-screen p-6">
            <div class="flex flex-col items-center">
                <!-- Profile Picture -->
                <div class="w-20 h-20 rounded-full bg-gray-500 mb-4"></div>
                <h2 class="text-lg font-bold">Account Owner's Name</h2>
            </div>
            <!-- Sidebar Links -->
            <nav class="mt-10">
                <ul>
                    <li class="mb-4"><a href="index.php" class="text-gray-300 hover:text-white">Home</a></li>
                    <li class="mb-4"><a href="userdashboard.php" class="text-gray-300 hover:text-white">Dashboard</a></li>
                    <li class="mb-4"><a href="userdashboard.php" class="text-gray-300 hover:text-white">Budget Plan</a></li>
                    <li class="mb-4"><a href="userdashboard.php" class="text-gray-300 hover:text-white">Expenses</a></li>
                    <li class="mb-4"><a href="add.php" class="text-gray-300 hover:text-white">Add Product</a></li>
                </ul>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="w-3/4 p-6">
            <h1 class="text-2xl font-bold mb-6">Dashboard</h1>

            <!-- Add Product Form -->
            <div class="bg-white p-6 rounded-lg shadow-lg mb-6">
                <h2 class="text-xl font-bold mb-4">Add Product</h2>
                <form action="" method="POST" enctype="multipart/form-data">
                    <div class="mb-4">
                        <label class="block mb-2 font-semibold">Product Name</label>
                        <input type="text" name="name" class="w-full border border-gray-300 p-2 rounded" required>
                    </div>
                    <div class="mb-4">
                        <label class="block mb-2 font-semibold">Description</label>
                        <textarea name="description" class="w-full border border-gray-300 p-2 rounded" rows="4"></textarea>
                    </div>
                    <div class="mb-4">
                        <label class="block mb-2 font-semibold">Price</label>
                        <input type="number" name="price" class="w-full border border-gray-300 p-2 rounded" step="0.01" required>
                    </div>
                    <div class="mb-4">
                        <label class="block mb-2 font-semibold">Image</label>
                        <input type="file" name="image" class="w-full border border-gray-300 p-2 rounded" accept="image/*">
                    </div>
                    <button type="submit" class="bg-blue-600 text-white py-2 px-4 rounded">Add Product</button>
                </form>
            </div>

            <!-- Product List -->
            <div class="bg-gray-200 p-6 rounded-lg shadow-lg">
                <h2 class="text-xl font-bold mb-4">Your Products</h2>
                <table class="w-full border-collapse border border-gray-300 bg-white rounded-lg">
                    <thead>
                        <tr class="bg-gray-800 text-white">
                            <th class="p-3 border border-gray-300">Name</th>
                            <th class="p-3 border border-gray-300">Description</th>
                            <th class="p-3 border border-gray-300">Price</th>
                            <th class="p-3 border border-gray-300">Image</th>
                            <th class="p-3 border border-gray-300">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($products): ?>
                            <?php foreach ($products as $product): ?>
                                <tr class="hover:bg-gray-100">
                                    <td class="p-3 border border-gray-300"><?php echo htmlspecialchars($product['name']); ?></td>
                                    <td class="p-3 border border-gray-300"><?php echo htmlspecialchars($product['description']); ?></td>
                                    <td class="p-3 border border-gray-300">Rs.<?php echo number_format($product['price'], 2); ?></td>
                                    <td class="p-3 border border-gray-300">
                                        <?php if (!empty($product['image'])): ?>
                                            <img src="uploads/<?php echo htmlspecialchars($product['image']); ?>" 
                                                 alt="Product Image" class="w-16 h-16 object-cover rounded">
                                        <?php else: ?>
                                            No Image
                                        <?php endif; ?>
                                    </td>
                                    <td class="p-3 border border-gray-300">
                                        <form action="" method="POST">
                                            <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                            <button type="submit" name="delete" class="bg-red-600 text-white py-1 px-2 rounded">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="p-3 text-center border border-gray-300">No products found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
