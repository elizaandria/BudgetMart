<?php
session_start(); 

// Redirect if admin is not logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: account.php");
    exit;
}

// Database connection
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
    <title>Admin Dashboard</title>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
        google.charts.load('current', {'packages':['corechart']});
        
        google.charts.setOnLoadCallback(drawProductChart);
        google.charts.setOnLoadCallback(drawUserChart);

        function drawProductChart() {
            var data = google.visualization.arrayToDataTable([
                ['Category', 'Count'],
                <?php
                // Product category data for pie chart
                $sql = "SELECT category, COUNT(*) as count FROM products GROUP BY category;";
                $fire = mysqli_query($con, $sql);
                if (!$fire) {
                    die("Query failed: " . mysqli_error($con));
                }
                while ($result = mysqli_fetch_assoc($fire)) {
                    echo "['" . $result['category'] . "', " . $result['count'] . "],";
                }
                ?>
            ]);

            var options = {
                title: 'Products by Categories',
                pieHole: 0.4, // For a donut chart
                colors: ['#1E3A8A', '#2563EB', '#3B82F6', '#60A5FA', '#93C5FD', '#BFDBFE']
            };

            var chart = new google.visualization.PieChart(document.getElementById('piechart'));
            chart.draw(data, options);
        }

        function drawUserChart() {
            var data = google.visualization.arrayToDataTable([
                ['Creation Month', 'Number of Users'],
                <?php
                // User creation data for column chart
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
    <!-- Main Content -->
    <div class="w-3/4 p-6">
        <h1 class="text-4xl font-bold mb-6 text-center">Dashboard</h1>

        <!-- Dashboard Sections -->
        <div class="grid grid-cols-2 gap-6">
            <!-- View Users Section -->
            <div class="bg-gray-200 p-4 rounded-lg">
                <h2 class="text-xl font-bold mb-4">Users Created Per Month</h2>
                <div id="userchart" style="width: 100%; height: 300px;"></div>
            </div>

            <!-- Manage Products Section -->
            <div class="bg-gray-200 p-4 rounded-lg">
                <h2 class="text-xl font-bold mb-4">Products by Categories</h2>
                <div id="piechart" style="width: 100%; height: 300px;"></div>
            </div>

            <!-- Budget Plans Section -->
            <div class="bg-gray-200 p-4 rounded-lg col-span-2">
                <h2 class="text-xl font-bold mb-4">Budget Plans</h2>
                <p class="text-gray-600">Pre-done financial budget plans.</p>
            </div>
        </div>

        <!-- Monthly Report -->
        <div class="bg-gray-800 text-white p-6 rounded-lg mt-6">
            <h2 class="text-xl font-bold mb-4">Monthly Report</h2>
            <p>Summary of your monthly financial report in a graph.</p>
        </div>
    </div>
</body>
</html>
