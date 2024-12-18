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

        <!-- Main Content -->
        <div class="w-3/4 p-6">
            <h1 class="text-2xl font-bold mb-6">Dashboard</h1>

            <!-- Expenses and Budget Plan -->
            <div class="grid grid-cols-2 gap-6">
                <div class="bg-gray-200 p-4 rounded-lg">
                    <h2 class="text-xl font-bold mb-2">Expenses</h2>
                    <p class="text-gray-600">Details about your expenses go here (Cart items).</p>
                </div>
                <div class="bg-gray-200 p-4 rounded-lg">
                    <h2 class="text-xl font-bold mb-2">Budget Plan</h2>
                    <p class="text-gray-600">A detailed budget plan will go here according to the items they have bought previously.</p>
                </div>
            </div>

            <!-- Monthly Report -->
            <div class="bg-gray-800 text-white p-6 rounded-lg mt-6">
                <h2 class="text-xl font-bold mb-4">Monthly Report</h2>
                <p>Summary of your monthly financial report goes here.</p>
            </div>
        </div>
    </div>
</body>
</html>
