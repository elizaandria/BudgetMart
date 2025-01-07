<?php
include('db.php');
session_start();

// Redirect to login page if user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Handle Logout
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit();
}

// Handle Add Product
if ($_SERVER['REQUEST_METHOD'] == 'POST' && !isset($_POST['delete'])) {
    // Initialize variables and handle missing fields
    $name = isset($_POST['name']) ? trim($_POST['name']) : null;
    $description = isset($_POST['description']) ? trim($_POST['description']) : null;
    $price = isset($_POST['price']) ? trim($_POST['price']) : null;
    $category = isset($_POST['category']) ? trim($_POST['category']) : null;
    $user_id = $_SESSION['user_id'];

    if (empty($name) || empty($price) || empty($category)) {
        echo "<p style='color: red;'>Product name, price, and category are required.</p>";
    } else {
        // Handle Image Upload
        $image = null;
        if (!empty($_FILES['image']['name'])) {
            $target_dir = "uploads/";
            if (!file_exists($target_dir)) {
                mkdir($target_dir, 0777, true);
            }

            $image = basename($_FILES['image']['name']);
            $target_file = $target_dir . $image;

            if (!move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
                $image = null; // If upload fails, set image to null
            }
        }

        // Insert product data into the database
        $sql = "INSERT INTO products (name, description, price, image, category, user_id) 
                VALUES (:name, :description, :price, :image, :category, :user_id)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':image', $image);
        $stmt->bindParam(':category', $category);
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

// Handle Delete Product
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
$user_id = $_SESSION['user_id'];
$sql = "SELECT id, name, description, price, image, category FROM products WHERE user_id = :user_id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':user_id', $user_id);
$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include('templates/userpanel.php'); ?>

        <div class="w-3/4 p-6">
            <h1 class="text-2xl font-bold mb-6">Dashboard</h1>
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
                        <label class="block mb-2 font-semibold">Category</label>
                        <select name="category" class="w-full border border-gray-300 p-2 rounded" required>
                            <option value="" disabled selected>Select a category</option>
                            <option value="electronics">Electronics</option>
                            <option value="sports">Sports</option>
                            <option value="clothes">Clothes</option>
                            <option value="kids">Kids</option>
                            <option value="furniture">Furniture</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="block mb-2 font-semibold">Image</label>
                        <input type="file" name="image" class="w-full border border-gray-300 p-2 rounded" accept="image/*">
                    </div>
                    <button type="submit" class="bg-blue-600 text-white py-2 px-4 rounded">Add Product</button>
                </form>
            </div>

            <div class="bg-gray-200 p-6 rounded-lg shadow-lg">
                <h2 class="text-xl font-bold mb-4">Your Products</h2>
                <table class="w-full border-collapse border border-gray-300 bg-white rounded-lg">
                    <thead>
                        <tr class="bg-gray-800 text-white">
                            <th class="p-3 border border-gray-300">Name</th>
                            <th class="p-3 border border-gray-300">Description</th>
                            <th class="p-3 border border-gray-300">Price</th>
                            <th class="p-3 border border-gray-300">Image</th>
                            <th class="p-3 border border-gray-300">Category</th>
                            <th class="p-3 border border-gray-300">Action</th>
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
                                    <td class="p-3 border border-gray-300"><?php echo htmlspecialchars($product['category']); ?></td>
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
                                <td colspan="6" class="p-3 text-center border border-gray-300">No products found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
