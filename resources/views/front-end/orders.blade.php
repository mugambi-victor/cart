<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Orders</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50 min-h-screen">
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
                <a href="{{ route('orders') }}" id="orders-link"
                    class="text-lg text-gray-700 hover:text-blue-600">Orders</a>




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
            </div>

        </div>
    </header>
    <div class="container mx-auto px-4 py-12">
        <!-- Header -->
        <header class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Your Orders</h1>
            <p class="text-gray-600 mt-2">View and manage your order history</p>
        </header>

        <!-- Orders Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @php
            $orders = app(\App\Http\Controllers\OrderController::class)->viewOrders();
            @endphp
            @if($orders->isEmpty())
            <div class="col-span-full">
                <div class="bg-white rounded-xl shadow-md p-8 text-center">
                    <div class="mb-4">
                        <svg class="w-16 h-16 mx-auto text-gray-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                    </div>
                    <p class="text-gray-500 text-lg mb-4">You have no orders yet.</p>
                    <a href="/"
                        class="inline-block bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">
                        Start Shopping
                    </a>
                </div>
            </div>
            @else
            @foreach($orders as $order)
            <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition duration-300">
                <!-- Order Header -->
                <div class="bg-gray-50 px-4 py-3 border-b">
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg font-semibold text-gray-800">Order #{{ $order->id }}</h3>
                        <span class="px-3 py-1 rounded-full text-sm
                            @if($order->status == 'completed') bg-green-100 text-green-800
                            @elseif($order->status == 'pending') bg-yellow-100 text-yellow-800
                            @elseif($order->status == 'cancelled') bg-red-100 text-red-800
                            @else bg-gray-100 text-gray-800
                            @endif">
                            {{ ucfirst($order->status) }}
                        </span>
                    </div>
                </div>

                <!-- Order Details -->
                <div class="p-4">
                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Date</span>
                            <span class="text-gray-800">
                                {{ \Carbon\Carbon::parse($order->created_at)->format('M d, Y') }}
                            </span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Total</span>
                            <span class="text-gray-800 font-medium">
                                ${{ number_format($order->total_amount, 2) }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Order Footer -->
                <div class="px-4 py-3 bg-gray-50 border-t">
                    <a href="/order/{{ $order->id }}" class="block w-full text-center px-4 py-2 bg-blue-50 text-blue-600 rounded-lg 
                              hover:bg-blue-100 transition duration-300">
                        View Details
                    </a>
                </div>
            </div>
            @endforeach
            @endif
        </div>
    </div>
</body>

</html>