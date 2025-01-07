<?php
session_start();

// Redirect if admin is not logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: account.php");
    exit;
}

// Handle delete request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_user'])) {
    $userIdToDelete = $_POST['user_id'];

    // Database connection
    $conn = mysqli_connect("localhost", "root", "", "user_accounts");

    if ($conn) {
        $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
        $stmt->bind_param("i", $userIdToDelete);
        $stmt->execute();
        $stmt->close();
        $conn->close();
    }

    header("Location: view_users.php");
    exit;
}

// Database connection for chart data
$con = mysqli_connect("localhost", "root", "", "user_accounts");
if (!$con) {
    die("Database connection failed: " . mysqli_connect_error());
}
?>

<?php include('templates/adminpanel.php'); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Users</title>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
        google.charts.load('current', {'packages':['corechart']});
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {
            var data = google.visualization.arrayToDataTable([
                ['Creation Month', 'Number of Users'],
                <?php
                // Fetch data for the chart
                $chartQuery = "SELECT DATE_FORMAT(created_at, '%Y-%m') AS creation_month, COUNT(*) AS user_count 
                               FROM users 
                               GROUP BY creation_month;";
                $chartResult = mysqli_query($con, $chartQuery);

                if ($chartResult) {
                    while ($row = mysqli_fetch_assoc($chartResult)) {
                        echo "['" . $row['creation_month'] . "', " . $row['user_count'] . "],";
                    }
                } else {
                    echo "['No Data', 0],";
                }
                ?>
            ]);

            var options = {
                title: 'Users Created Per Month',
                hAxis: {title: 'Month'},
                vAxis: {title: 'Number of Users'},
                colors: ['#60A5FA']
            };

            var chart = new google.visualization.ColumnChart(document.getElementById('userchart'));
            chart.draw(data, options);
        }
    </script>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <div class="w-3/4 p-6">
        <h1 class="text-2xl font-bold mb-4 text-center">Users</h1>

        <!-- User Statistics Chart -->
        <div class="bg-gray-200 p-4 rounded-lg">
            <h2 class="text-xl font-bold mb-4">User Statistics</h2>
            <div id="userchart" style="width: 100%; height: 300px;"></div>
        </div>

        </br>

        <!-- User Details Table -->
        <div class="bg-gray-200 p-4 rounded-lg mb-6">
            <h2 class="text-xl font-bold mb-4">User Details</h2>
            <table class="table-auto w-full bg-white rounded-lg shadow">
                <thead>
                    <tr class="bg-gray-200 border-b border-gray-300">
                        <th class="px-4 py-2 text-center border border-gray-300">Id</th>
                        <th class="px-4 py-2 text-center border border-gray-300">Username</th>
                        <th class="px-4 py-2 text-center border border-gray-300">Password</th>
                        <th class="px-4 py-2 text-center border border-gray-300">Created Date</th>
                        <th class="px-4 py-2 text-center border border-gray-300">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Fetch user data for the table
                    $tableQuery = "SELECT id, username, password, created_at FROM users";
                    $tableResult = mysqli_query($con, $tableQuery);

                    if ($tableResult && mysqli_num_rows($tableResult) > 0) {
                        while ($row = mysqli_fetch_assoc($tableResult)) {
                            echo "<tr class='border-t'>
                                    <td class='px-4 py-2 text-center border border-gray-300'>{$row['id']}</td>
                                    <td class='px-4 py-2 text-center border border-gray-300'>{$row['username']}</td>
                                    <td class='px-4 py-2 text-center border border-gray-300'>{$row['password']}</td>
                                    <td class='px-4 py-2 text-center border border-gray-300'>{$row['created_at']}</td>
                                    <td class='px-4 py-2 text-center border border-gray-300'>
                                        <form method='POST' onsubmit='return confirm(\"Are you sure you want to delete this user?\");'>
                                            <input type='hidden' name='user_id' value='{$row['id']}'>
                                            <button type='submit' name='delete_user' class='bg-red-500 text-white px-4 py-1 rounded hover:bg-red-600'>
                                                Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='5' class='text-center py-4'>No users found.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

    </div>
</body>
</html>
