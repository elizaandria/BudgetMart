<?php
session_start();

// Check if cart exists
if (!isset($_SESSION['cart']) || count($_SESSION['cart']) == 0) {
    echo "Your cart is empty.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart</title>
    <!-- Include TailwindCSS -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<?php include('templates/header.php'); ?>

<body class="bg-gray-100 text-gray-800">
    <div class="container mx-auto py-8">
        <h1 class="text-2xl font-bold text-center mb-6">Your Cart</h1>
        
        <table class="min-w-full bg-white shadow-md rounded-lg overflow-hidden">
            <thead class="bg-gray-800 text-white">
                <tr>
                    <th class="py-3 px-6 text-left">Name</th>
                    <th class="py-3 px-6 text-left">Price</th>
                    <th class="py-3 px-6 text-left">Image</th>
                    <th class="py-3 px-6 text-left">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                <?php foreach ($_SESSION['cart'] as $index => $cart_item): ?>
                    <tr class="hover:bg-gray-100">
                        <td class="py-3 px-6"><?php echo htmlspecialchars($cart_item['name']); ?></td>
                        <td class="py-3 px-6">Rs. <?php echo htmlspecialchars($cart_item['price']); ?></td>
                        <td class="py-3 px-6">
                            <img src="uploads/<?php echo htmlspecialchars($cart_item['image']); ?>" alt="Product Image" class="w-20 h-auto rounded-lg cursor-pointer" onclick="openModal(<?php echo $index; ?>)">
                        </td>
                        <td class="py-3 px-6">
                            <a href="?remove=<?php echo $index; ?>" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">Remove</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        
        <!-- Checkout Button -->
        <div class="text-center mt-6">
            <a href="checkout.php" class="bg-green-500 text-white px-6 py-3 rounded hover:bg-green-600 transition duration-300">Proceed to Checkout</a>
        </div>
    </div>

    <!-- Product Detail Modal -->
    <div id="productModal" class="fixed inset-0 bg-gray-800 bg-opacity-50 flex justify-center items-center hidden">
        <div class="bg-white rounded-lg w-1/2 p-6">
            <h2 id="modalProductName" class="text-xl font-semibold mb-4"></h2>
            <p id="modalProductDescription" class="text-gray-700 mb-4"></p>
            <p id="modalProductPrice" class="text-lg font-bold mb-4"></p>
            <img id="modalProductImage" src="" alt="Product Image" class="w-full h-auto mb-4 rounded-lg">
            <form action="cart.php" method="POST">
                <input type="hidden" name="product_id" id="modalProductId">
                <button type="submit" class="bg-blue-500 text-white px-6 py-3 rounded hover:bg-blue-600">Add to Cart</button>
            </form>
            <button onclick="closeModal()" class="mt-4 bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">Close</button>
        </div>
    </div>
</body>

<?php include('templates/footer.php'); ?>

<script>
    // Open Modal with product details
    function openModal(index) {
        // Get product data from the session (in real implementation, you might fetch this from the database)
        var products = <?php echo json_encode($_SESSION['cart']); ?>;
        var product = products[index];

        document.getElementById('modalProductName').innerText = product.name;
        document.getElementById('modalProductDescription').innerText = product.description || 'No description available.';
        document.getElementById('modalProductPrice').innerText = 'Rs. ' + product.price;
        document.getElementById('modalProductImage').src = 'uploads/' + product.image;
        document.getElementById('modalProductId').value = product.id;

        // Show modal
        document.getElementById('productModal').classList.remove('hidden');
    }

    // Close the modal
    function closeModal() {
        document.getElementById('productModal').classList.add('hidden');
    }
</script>
</html>