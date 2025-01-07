<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: account.php");
    exit;
}

//Default username if not set
$username = isset($_SESSION['username']) ? $_SESSION['username'] : 'Guest';
?>

<?php include('templates/userpanel.php'); ?>


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

<?php
// Logout logic
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    session_unset();
    session_destroy();
    header("Location: account.php");
    exit();
}
?>
