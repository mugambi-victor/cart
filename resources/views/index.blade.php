<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Our Shop</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/heroicons@2.0.16/outline.js"></script>
    <!-- Heroicons for the cart icon -->
</head>

<body class="bg-gray-50">

    <!-- Header Section -->
    <header class="bg-white shadow-md py-2 px-4 fixed top-0 left-0 w-full z-20">
        <div class=" mx-4 flex items-center justify-between">
            <!-- Brand Name -->
            <div class="text-2xl font-bold text-gray-800">
                Flawless
            </div>


            <!-- Navigation Menu -->
            <div class="flex space-x-6">
                <!-- Orders Link -->
                <a href="{{route('orders')}}" class=" text-lg text-gray-700 hover:text-blue-600">Orders</a>



                <!-- Cart Link with Icon -->
                <a href="/cart/view" class="flex items-center text-lg text-gray-700 hover:text-blue-600 relative">
                    <!-- Heroicon for Cart -->
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 3h18l-1.34 7.26a2 2 0 0 1-1.98 1.74H7.32a2 2 0 0 1-1.98-1.74L3 3m2 0l1.34 7.26a2 2 0 0 0 1.98 1.74h9.36a2 2 0 0 0 1.98-1.74L19 3m-9 10a2 2 0 1 1 0-4 2 2 0 0 1 0 4zm5 0a2 2 0 1 1 0-4 2 2 0 0 1 0 4z">
                        </path>
                    </svg>
                    <!-- Cart Count Badge -->
                    <span id="cart-count"
                        class="absolute top-0 right-0 bg-red-600 text-white text-xs font-bold px-2 py-1 rounded-full">0</span>
                </a>
                <button id="logout-button" class="text-lg text-gray-700 hover:text-blue-600 cursor-pointer">
                    Logout
                </button>
            </div>

        </div>
    </header>

    <!-- Hero Section -->
    <div class="h-[75vh] bg-white relative">
        <div class="absolute inset-0 bg-cover bg-center"
            style="background-image: url('https://pixabay.com/get/geaec811f5ca6f1271afa79dd1160a734cfce05230c297a1ffeebae6ee9caccb9adf0b96178f77b874f1c22ad4ec9a688_1920.jpg'); background-color: #000; filter: grayscale(100%); background-size: object-fit;">
        </div>
        <div class="absolute inset-0 bg-black/50"></div>
        <div class="container mx-auto h-full relative">
            <div class="flex h-full items-center">
                <!-- Text Content -->
                <div class="w-1/2 px-8">
                    <h1 class="text-4xl font-bold text-white mb-4">Welcome to Our Shop</h1>
                    <p class="text-lg text-gray-100 mb-6">Discover our amazing collection of products crafted just for
                        you.</p>
                    <button class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">
                        Shop Now
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Products Section -->
    <div class="bg-gray-50 py-16">
        <div class="container mx-auto">
            <h2 class="text-3xl font-bold text-center text-gray-800 mb-12">Our Products</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 px-4" id="product-container">

            </div>
        </div>
    </div>

    <!-- Cart Modal Structure -->
    <div id="cart-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">
        <div class="bg-white w-full max-w-lg p-6 rounded-lg shadow-lg">
            <!-- Modal Header -->
            <div class="flex justify-between items-center border-b pb-3">
                <h2 class="text-2xl font-semibold">Your Cart</h2>
                <button id="close-cart-modal" class="text-gray-600 hover:text-gray-900">
                    &times;
                    <!-- Close button -->
                </button>
            </div>

            <!-- Cart Items -->
            <div id="cart-items" class="mt-4 space-y-4">
                <p class="text-center text-gray-500">Your cart is empty.</p>
            </div>

            <!-- Modal Footer -->
            <div class="flex justify-between items-center border-t pt-3 mt-4">
                <button id="close-cart" class="text-blue-600 hover:underline">Continue Shopping</button>
                <a id="checkout-button"
                    class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition cursor-pointer">
                    Checkout
                </a>

            </div>
        </div>
    </div>

    @include('scripts.variables')
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/heroicons@2.0.16/outline.js"></script>
    <script src="/js/main.js" defer></script>
</body>

</html>