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
        <div class="w-1/4 bg-gray-800 text-white h-screen-fixed p-6">
            <div class="flex flex-col items-center">

                <!-- Profile Picture -->
                <?php
                // Assuming you have `profile_picture` and `username` stored in the session
                $profile_picture = !empty($_SESSION['profile_picture']) ? $_SESSION['profile_picture'] : 'default-profile.png';
                $username = !empty($_SESSION['username']) ? $_SESSION['username'] : 'User';
                ?>
                <div class="w-20 h-20 rounded-full overflow-hidden bg-gray-500 mb-4">
                    <img src="uploads/<?php echo htmlspecialchars($profile_picture); ?>" alt="Profile Picture" class="w-full h-full object-cover">
                </div>
                <h2 class="text-lg font-bold"><?php echo htmlspecialchars($username); ?></h2>
            </div>

            <!-- Sidebar Links -->
            <nav class="mt-10">
                <ul>
                    <li class="mb-4">
                        <a href="index.php" class="text-gray-300 hover:text-white">Home</a>
                    </li>
                    <li class="mb-4">
                        <a href="userdashboard.php" class="text-gray-300 hover:text-white">Dashboard</a>
                    </li>
                    <li class="mb-4">
                        <a href="budgetplan.php" class="text-gray-300 hover:text-white">Budget Plan</a>
                    </li>
                    <li class="mb-4">
                        <a href="expenses.php" class="text-gray-300 hover:text-white">Expenses</a>
                    </li>
                    <li class="mb-4">
                        <a href="add.php" class="text-gray-300 hover:text-white">Add Product</a>
                    </li>
                    <li class="mb-4">
                        <a href="add.php?logout=true" class="text-red-500 hover:text-red-700">Logout</a>
                    </li>
                </ul>
            </nav>
        </div>
    
