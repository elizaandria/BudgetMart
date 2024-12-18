<?php
session_start();

// Redirect if admin is not logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: account.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-800">
    <!-- Sidebar -->
    <div class="flex">
        <div class="w-1/4 bg-gray-800 text-white h-screen p-6">
            <div class="flex flex-col items-center">
                <!-- Admin Profile Picture -->
                <div class="w-20 h-20 rounded-full bg-gray-500 mb-4"></div>
                <h2 class="text-lg font-bold">Admin</h2>
            </div>

            <!-- Sidebar Links -->
            <nav class="mt-10">
                <ul>
                    <li class="mb-4">
                        <a href="admindashboard.php" class="text-gray-300 hover:text-white">Dashboard</a>
                    </li>
                    <li class="mb-4">
                        <a href="view_users.php" class="text-gray-300 hover:text-white">View Users</a>
                    </li>
                    <li class="mb-4">
                        <a href="manage_products.php" class="text-gray-300 hover:text-white">Manage Products</a>
                    </li>
                    <li class="mb-4">
                        <a href="admindashboard.php" class="text-gray-300 hover:text-white">View Transactions</a>
                    </li>
                    <li class="mb-4">
                        <a href="account.php" class="text-gray-300 hover:text-white">Logout</a>
                    </li>
                </ul>
            </nav>
        </div>

        <!-- View Users -->
        <div class="w-3/4 p-6">
            <h1 class="text-2xl font-bold mb-4">User List</h1>
            <table class="table-auto w-full bg-white rounded-lg shadow">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="px-4 py-2">Id</th>
                        <th class="px-4 py-2">Username</th>
                        <th class="px-4 py-2">Password</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Database connection
                    $conn = mysqli_connect("localhost", "root", "", "user_accounts");

                    if (!$conn) {
                        die("<tr><td colspan='2'>Connection failed: " . mysqli_connect_error() . "</td></tr>");
                    }

                    $sql = "SELECT id, username, password FROM users"; // SQL Query
                    $result = $conn->query($sql);

                    if ($result && $result->num_rows > 0) {
                        // Display user data
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr class='border-t'>
                                    <td class='px-4 py-2'>{$row['id']}</td>
                                    <td class='px-4 py-2'>{$row['username']}</td>
                                    <td class='px-4 py-2'>{$row['password']}</td>
                                  </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='2' class='text-center py-4'>No users found.</td></tr>";
                    }

                    $conn->close();
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
