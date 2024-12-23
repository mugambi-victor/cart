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
        <div class="mx-4 flex items-center justify-between">
            <div class="text-2xl font-bold text-gray-800">Flawless</div>
            <div class="flex space-x-6">
                <a href="{{ route('index') }}" class="text-lg text-gray-700 hover:text-blue-600">Home</a>
                <button id="fetch-orders" class="text-lg text-gray-700 hover:text-blue-600">Fetch Orders</button>
            </div>
        </div>
    </header>

    <div class="container mx-auto px-4 py-12">
        <!-- Orders Grid -->
        <div id="orders-container" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            <!-- Orders will be injected here via JS -->
        </div>
    </div>


    <script>
    // Function to fetch and display orders
    async function fetchOrders() {
        try {
            const token = localStorage.getItem('authToken'); // Get the stored token
            console.log("The token is", token);

            // Fetch orders from the API
            const response = await fetch('/api/orders', {
                method: 'GET',
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Content-Type': 'application/json',
                },
            });

            // Check if the response is successful
            if (!response.ok) {
                console.log(response);
                throw new Error('Failed to fetch orders');
            }

            // Parse the response data
            const orders = await response.json();
            console.log("Orders are:", orders);

            // Display orders if available
            const ordersContainer = document.getElementById('orders-container');
            ordersContainer.innerHTML = ''; // Clear any previous content

            if (orders.length > 0) {
                orders.forEach(order => {
                    const orderElement = document.createElement('div');
                    orderElement.classList.add(
                        'order-item',
                        'bg-white',
                        'rounded-xl',
                        'shadow-md',
                        'p-4',
                        'mb-4'
                    );

                    orderElement.innerHTML = `
    <h3 class="text-xl font-semibold text-gray-800">Order #${order.id}</h3>
    <p>Status: <span class="text-sm ${
                            order.status === 'completed'
                                ? 'text-green-500'
                                : order.status === 'pending'
                                ? 'text-yellow-500'
                                : 'text-red-500'
                        }">${order.status}</span></p>
    <p>Date: ${new Date(order.created_at).toLocaleDateString()}</p>
    <p>Total: $${order.total_amount}</p>
    <a href="/order/${order.id}" class="text-blue-600 hover:underline">View Details</a>
    `;

                    ordersContainer.appendChild(orderElement);
                });
            } else {
                ordersContainer.innerHTML = '<p>No orders found.</p>';
            }
        } catch (error) {
            console.error('Error fetching orders:', error);
            document.getElementById('orders-container').innerHTML =
                '<p>Error fetching orders. Please try again later.</p>';
        }
    }

    // Automatically fetch orders when the page loads
    document.addEventListener('DOMContentLoaded', fetchOrders);

    // Fetch orders manually when the button is clicked
    document.getElementById('fetch-orders').addEventListener('click', fetchOrders);
    </script>

</body>

</html>