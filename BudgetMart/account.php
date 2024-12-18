<!DOCTYPE html>
<html lang="en">

<?php include('templates/header.php'); ?>

<body style="background-image: url('images/background_login1.jpeg'); background-size: cover; background-position: center; background-repeat: no-repeat; height: 100vh;">

    <!-- Account Page -->
    <div class="container mx-auto py-12 flex flex-col items-center justify-center min-h-screen">
        <!-- Form Section -->
        <div class="w-full md:w-1/2 bg-white bg-opacity-90 p-8 shadow-lg rounded-lg">
            <!-- Display Error Message -->
            <?php
            if (isset($_SESSION['error_message'])) {
                echo "<div class='text-red-500 text-center mb-4'>{$_SESSION['error_message']}</div>";
                unset($_SESSION['error_message']); // Clear the error message
            }
            ?>
            
            <!-- Tab Navigation -->
            <div class="flex justify-around mb-6 text-center">
                <button id="userTab" onclick="userLogin()" class="text-blue-600 font-semibold">User Login</button>
                <button id="adminTab" onclick="adminLogin()" class="text-gray-500">Admin Login</button>
                <button id="signupTab" onclick="signup()" class="text-gray-500">Sign Up</button>
            </div>
            <hr class="mb-6 border-gray-300">

            <!-- User Login Form -->
            <form id="UserLoginForm" action="login.php" method="POST" class="space-y-4">
                <input type="hidden" name="role" value="user">
                <input type="text" name="username" placeholder="Username" required class="w-full border border-gray-300 p-2 rounded">
                <input type="password" name="password" placeholder="Password" required class="w-full border border-gray-300 p-2 rounded">
                <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded">Login</button>
                <a href="#" class="text-sm text-blue-600 hover:underline">Forgot Password?</a>
            </form>

            <!-- Admin Login Form -->
            <form id="AdminLoginForm" action="login.php" method="POST" class="space-y-4 hidden">
                <input type="hidden" name="role" value="admin">
                <input type="text" name="username" placeholder="Admin Username" required class="w-full border border-gray-300 p-2 rounded">
                <input type="password" name="password" placeholder="Password" required class="w-full border border-gray-300 p-2 rounded">
                <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded">Admin Login</button>
            </form>

            <!-- Signup Form -->
            <form id="SignupForm" action="signup.php" method="POST" class="space-y-4 hidden">
                <input type="text" name="username" placeholder="Username" required class="w-full border border-gray-300 p-2 rounded">
                <input type="email" name="email" placeholder="Email" required class="w-full border border-gray-300 p-2 rounded">
                <input type="password" name="password" placeholder="Password" required class="w-full border border-gray-300 p-2 rounded">
                <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded">Sign Up</button>
            </form>
        </div>
    </div>

    <?php include('templates/footer.php'); ?>

    <!-- Toggle Form JS -->
    <script>
        const UserLoginForm = document.getElementById("UserLoginForm");
        const AdminLoginForm = document.getElementById("AdminLoginForm");
        const SignupForm = document.getElementById("SignupForm");

        const userTab = document.getElementById("userTab");
        const adminTab = document.getElementById("adminTab");
        const signupTab = document.getElementById("signupTab");

        function resetTabStyles() {
            userTab.classList.remove("text-blue-600", "font-semibold");
            userTab.classList.add("text-gray-500");

            adminTab.classList.remove("text-blue-600", "font-semibold");
            adminTab.classList.add("text-gray-500");

            signupTab.classList.remove("text-blue-600", "font-semibold");
            signupTab.classList.add("text-gray-500");
        }

        function userLogin() {
            resetTabStyles();
            userTab.classList.add("text-blue-600", "font-semibold");
            userTab.classList.remove("text-gray-500");

            UserLoginForm.classList.remove("hidden");
            AdminLoginForm.classList.add("hidden");
            SignupForm.classList.add("hidden");
        }

        function adminLogin() {
            resetTabStyles();
            adminTab.classList.add("text-blue-600", "font-semibold");
            adminTab.classList.remove("text-gray-500");

            UserLoginForm.classList.add("hidden");
            AdminLoginForm.classList.remove("hidden");
            SignupForm.classList.add("hidden");
        }

        function signup() {
            resetTabStyles();
            signupTab.classList.add("text-blue-600", "font-semibold");
            signupTab.classList.remove("text-gray-500");

            UserLoginForm.classList.add("hidden");
            AdminLoginForm.classList.add("hidden");
            SignupForm.classList.remove("hidden");
        }

        // Set the default active tab to User Login
        userLogin();
    </script>
</body>
</html>
