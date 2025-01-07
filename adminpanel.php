<!DOCTYPE html>
<html lang="en">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-800">
    <!-- Sidebar -->
    <div class="flex">
        <div class="w-1/5 bg-gray-800 text-white h-screen-fixed p-6">
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
                        <a href="admindashboard.php" class="text-gray-300 hover:text-white">Budget Plans</a>
                    </li>
                    <li class="mb-4">
                        <a href="account.php" class="text-gray-300 hover:text-white">Logout</a>
                    </li>
                </ul>
            </nav>
        </div>