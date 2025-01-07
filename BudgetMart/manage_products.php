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

<?php include('templates/adminpanel.php'); ?>

<!-- Add Google Charts -->
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
    google.charts.load('current', {'packages':['corechart']});
    google.charts.setOnLoadCallback(drawProductChart);

    function drawProductChart() {
        var data = google.visualization.arrayToDataTable([
            ['Category', 'Count'],
            <?php
            // Product category data for pie chart
            $sql = "SELECT category, COUNT(*) as count FROM products GROUP BY category;";
            $fire = mysqli_query($conn, $sql);
            if (!$fire) {
                die("Query failed: " . mysqli_error($conn));
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
</script>

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

    <!-- Product Category Pie Chart -->
    <div class="bg-gray-200 p-4 rounded-lg mb-6">
        <h2 class="text-xl font-bold mb-4">Products by Categories</h2>
        <div id="piechart" style="width: 100%; height: 300px;"></div>
    </div>

    <!-- Product Table -->
    <div class="overflow-x-auto bg-white shadow-md rounded-lg">
        <table class="min-w-full table-auto border-collapse border border-gray-200">
            <thead>
                <tr class="bg-gray-800 text-white">
                    <th class="py-3 px-6 text-left border border-gray-300">Name</th>
                    <th class="py-3 px-6 text-left border border-gray-300">Description</th>
                    <th class="py-3 px-6 text-left border border-gray-300">Price</th>
                    <th class="py-3 px-6 text-left border border-gray-300">Category</th> <!-- Added Category -->
                    <th class="py-3 px-6 text-left border border-gray-300">Image</th>
                    <th class="py-3 px-6 text-left border border-gray-300">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT id, name, description, price, category, image FROM products";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $imagePath = "uploads/" . htmlspecialchars($row['image']);
                        echo "<tr class='hover:bg-gray-50'>
                                <td class='py-3 px-6 border border-gray-300'>" . htmlspecialchars($row['name']) . "</td>
                                <td class='py-3 px-6 border border-gray-300'>" . htmlspecialchars($row['description']) . "</td>
                                <td class='py-3 px-6 border border-gray-300'>Rs. " . htmlspecialchars($row['price']) . "</td>
                                <td class='py-3 px-6 border border-gray-300'>" . htmlspecialchars($row['category']) . "</td> <!-- Added Category -->
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
                    echo "<tr><td colspan='6' class='text-center text-gray-500 py-4'>No products found</td></tr>"; // Changed colspan to 6
                }

                // Close connection
                $conn->close();
                ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>
