<!DOCTYPE html>
<html lang="en">
    <?php include('templates/header.php'); ?>

    <div class="container mx-auto px-6 py-12">
        <!-- Hero Section -->
        <div class="flex flex-wrap items-center justify-between">
            <div class="w-full md:w-1/2">
                <h1 class="text-4xl font-bold text-gray-800">Manage your household finances with ease!</h1>
                <p class="mt-4 text-gray-600">Buy and sell pre-loved items on our platform.</p>
                <a href="#" class="mt-6 inline-block px-6 py-3 bg-blue-600 text-white rounded-lg shadow hover:bg-blue-700">
                    Explore Now →
                </a>
            </div>
            <div class="w-full md:w-1/2 mt-6 md:mt-0">
                <img src="Images/goods.png" alt="Goods" class="rounded-lg shadow">
            </div>
        </div>
    </div>

    <!-- Featured Categories -->
    <div class="py-12 bg-gray-100">
        <div class="container mx-auto px-6">
            <h2 class="text-2xl font-bold text-center text-gray-800">Featured Categories</h2>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-6 mt-8">
                <div class="text-center">
                    <img src="Images/electronics.png" alt="Electronics" class="h-32 mx-auto">
                    <h4 class="mt-4 text-gray-700 font-medium">Electronics</h4>
                </div>
                <div class="text-center">
                    <img src="Images/sports.png" alt="Sports" class="h-32 mx-auto">
                    <h4 class="mt-4 text-gray-700 font-medium">Sports</h4>
                </div>
                <div class="text-center">
                    <img src="Images/clothes.png" alt="Clothes" class="h-32 mx-auto">
                    <h4 class="mt-4 text-gray-700 font-medium">Clothes</h4>
                </div>
                <div class="text-center">
                    <img src="Images/toys.png" alt="Kids" class="h-32 mx-auto">
                    <h4 class="mt-4 text-gray-700 font-medium">Kids</h4>
                </div>
                <div class="text-center">
                    <img src="Images/furniture.png" alt="Furniture" class="h-32 mx-auto">
                    <h4 class="mt-4 text-gray-700 font-medium">Furniture</h4>
                </div>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-6 py-12">
        <div class="flex flex-wrap items-center justify-between">
            <div class="w-full md:w-1/2">
                <h1 class="text-4xl font-bold text-gray-800">Budget Meets Simplicity!</h1>
                <p class="mt-4 text-gray-600">The easy to user, zero-based budgeting app that helps you keep tabs on your money at a glance anytime.</p>
            </div>
            <div class="w-full md:w-1/2 mt-6 md:mt-0">
                <img src="Images/budget.png" alt="Goods" class="rounded-lg shadow">
            </div>
        </div>
    </div>

    <?php include('templates/footer.php'); ?>
</html>