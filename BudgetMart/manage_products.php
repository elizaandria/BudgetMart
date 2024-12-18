<?php
session_start();

// Database connection
$conn = new mysqli("localhost", "root", "", "user_accounts");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle deleting a product
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_id'])) {
    $delete_id = intval($_POST['delete_id']);
    $delete_sql = "DELETE FROM products WHERE id = $delete_id";
    if ($conn->query($delete_sql) === TRUE) {
        $_SESSION['message'] = "Product deleted successfully.";
    } else {
        $_SESSION['error'] = "Error deleting product: " . $conn->error;
    }
    header("Location: manage_products.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Products</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-800">
    <div class="flex">
        <!-- Sidebar -->
        <div class="w-1/4 bg-gray-800 text-white h-screen p-6">
            <div class="flex flex-col items-center">
                <div class="w-20 h-20 rounded-full bg-gray-500 mb-4"></div>
                <h2 class="text-lg font-bold">Admin</h2>
            </div>

            <!-- Sidebar Links -->
            <nav class="mt-10">
                <ul>
                    <li class="mb-4"><a href="admindashboard.php" class="text-gray-300 hover:text-white">Dashboard</a></li>
                    <li class="mb-4"><a href="view_users.php" class="text-gray-300 hover:text-white">View Users</a></li>
                    <li class="mb-4"><a href="manage_products.php" class="text-gray-300 hover:text-white">Manage Products</a></li>
                    <li class="mb-4"><a href="view_transactions.php" class="text-gray-300 hover:text-white">View Transactions</a></li>
                    <li class="mb-4"><a href="account.php" class="text-gray-300 hover:text-white">Logout</a></li>
                </ul>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1 p-6">
            <h1 class="text-3xl font-bold mb-6">Manage Products</h1>

            <!-- Notifications -->
            <?php if (isset($_SESSION['message'])): ?>
                <div class="mb-4 p-4 bg-green-100 text-green-700 rounded">
                    <?= $_SESSION['message']; unset($_SESSION['message']); ?>
                </div>
            <?php endif; ?>
            <?php if (isset($_SESSION['error'])): ?>
                <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">
                    <?= $_SESSION['error']; unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>

            <!-- Product Table -->
            <div class="overflow-x-auto bg-white shadow-md rounded-lg">
                <table class="min-w-full table-auto border-collapse border border-gray-200">
                    <thead>
                        <tr class="bg-gray-800 text-white">
                            <th class="py-3 px-6 text-left border border-gray-300">Name</th>
                            <th class="py-3 px-6 text-left border border-gray-300">Description</th>
                            <th class="py-3 px-6 text-left border border-gray-300">Price</th>
                            <th class="py-3 px-6 text-left border border-gray-300">Image</th>
                            <th class="py-3 px-6 text-left border border-gray-300">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT id, name, description, price, image FROM products";
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                $imagePath = "uploads/" . htmlspecialchars($row['image']);
                                echo "<tr class='hover:bg-gray-50'>
                                        <td class='py-3 px-6 border border-gray-300'>" . htmlspecialchars($row['name']) . "</td>
                                        <td class='py-3 px-6 border border-gray-300'>" . htmlspecialchars($row['description']) . "</td>
                                        <td class='py-3 px-6 border border-gray-300'>Rs. " . htmlspecialchars($row['price']) . "</td>
                                        <td class='py-3 px-6 border border-gray-300'>
                                            <img src='$imagePath' alt='Product Image' class='w-20 h-auto rounded-lg'>
                                        </td>
                                        <td class='py-3 px-6 border border-gray-300'>
                                            <form action='' method='POST' class='inline-block'>
                                                <input type='hidden' name='delete_id' value='" . $row['id'] . "'>
                                                <button type='submit' class='bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600'>
                                                    Delete
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
    </div>
</body>
</html>
