// Access global configuration
const baseUrl = window.appConfig.storageUrl;
let authToken = window.appConfig.authToken;

if (authToken) {
    localStorage.setItem('authToken', authToken);
    console.log('Token stored successfully:', authToken);
}

// Product fetching and display
async function fetchProducts() {
    try {
        const response = await fetch('/api/products',{
            headers: {
                'Accept': 'application/json'
            }}
        );
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        const products = await response.json();

        const productContainer = document.querySelector('#product-container');
        productContainer.innerHTML = '';

        products.forEach(product => {
            const imageUrl = `${baseUrl}/${product.image}`;
            const productCard = `
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <img src="${imageUrl}" alt="${product.name}" class="w-full h-48 object-cover"/>
                    <div class="p-6">
                        <h3 class="text-xl font-semibold text-gray-800 mb-2">${product.name}</h3>
                        <p class="text-gray-600 mb-4">${product.description}</p>
                        <div class="flex justify-between items-center">
                            <span class="text-xl font-bold text-blue-600">$${product.price}</span>
                            <button class="add-to-cart bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition" 
                                    data-product-id="${product.id}">
                                Add to Cart
                            </button>
                        </div>
                    </div>
                </div>
            `;
            productContainer.innerHTML += productCard;
        });
    } catch (error) {
        console.error('Error fetching products:', error);
        const productContainer = document.querySelector('#product-container');
        productContainer.innerHTML = '<p class="text-center text-red-500">Error loading products. Please try again later.</p>';
    }
}

// Cart operations
async function addToCart(productId) {
    try {
        // Get token from either localStorage or session
        const authToken = localStorage.getItem('authToken') || window.appConfig.authToken;
        console.log('Auth token: ', authToken);
        if (!authToken) {
            // Store current URL in session through a backend endpoint
            const currentUrl = window.location.href;
            window.location.href = `/login?redirectTo=${encodeURIComponent(currentUrl)}`;
            return;
        }

        const response = await fetch('/api/cart/add', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${authToken}`,
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
            },
            body: JSON.stringify({ product_id: productId }),
        });

        if (response.status === 401) {
            // If unauthorized, redirect to login
            const currentUrl = window.location.href;
            window.location.href = `/login?redirectTo=${encodeURIComponent(currentUrl)}`;
            return;
        }

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const result = await response.json();
        updateCartCount();
        alert(result.message);
    } catch (error) {
        console.error('Error adding to cart:', error);
        alert('Failed to add item to cart. Please try again later.');
    }
}
async function updateCartCount() {
    try {
        const authToken = localStorage.getItem('authToken');
        if (!authToken) {
            document.querySelector('#cart-count').textContent = '0';
            return;
        }

        const response = await fetch('/api/cart/view', {
            headers: {
                'Authorization': `Bearer ${authToken}`,
                'Accept': 'application/json'
            },
        });

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const cart = await response.json();
        const count = Object.values(cart).reduce((sum, item) => sum + item.quantity, 0);
        document.querySelector('#cart-count').textContent = count;
    } catch (error) {
        console.error('Error updating cart count:', error);
        document.querySelector('#cart-count').textContent = '0';
    }
}

async function loadCartItems() {
    try {
        const authToken = localStorage.getItem('authToken');
        if (!authToken) {
            document.querySelector('#cart-items').innerHTML = '<p class="text-center text-gray-500">Please log in to view your cart.</p>';
            return;
        }

        const response = await fetch('/api/cart/view', {
            headers: {
                'Authorization': `Bearer ${authToken}`,
            },
        });

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const cart = await response.json();
        const cartItemsContainer = document.querySelector('#cart-items');

        if (Object.keys(cart).length > 0) {
            cartItemsContainer.innerHTML = Object.values(cart).map(item => `
                <div class="bg-gray-100 p-4 rounded flex justify-between items-center">
                    <div class="flex items-center space-x-4">
                        <img src="${baseUrl}/${item.image || ''}" alt="${item.name}" class="w-16 h-16 rounded object-cover" />
                        <div>
                            <h2 class="text-lg font-semibold">${item.name}</h2>
                            <p class="text-sm text-gray-600">$${item.price} x ${item.quantity}</p>
                        </div>
                    </div>
                    <span class="text-lg font-bold text-blue-600">$${(item.price * item.quantity).toFixed(2)}</span>
                    <button class="remove-from-cart bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 transition"
                        data-product-id="${item.product_id}">
                        Remove
                    </button>
                </div>
            `).join('');
        } else {
            cartItemsContainer.innerHTML = '<p class="text-center text-gray-500">Your cart is empty.</p>';
        }
    } catch (error) {
        console.error('Error loading cart items:', error);
        document.querySelector('#cart-items').innerHTML = '<p class="text-center text-red-500">Error loading cart items. Please try again later.</p>';
    }
}

async function removeFromCart(productId) {
    try {
        const authToken = localStorage.getItem('authToken');
        if (!authToken) {
            alert('You must be logged in to modify your cart.');
            return;
        }

        const response = await fetch('/api/cart/view', {
            headers: {
                'Authorization': `Bearer ${authToken}`,
            },
        });

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const cart = await response.json();
        const item = Object.values(cart).find(item => item.product_id === parseInt(productId));

        if (!item) {
            console.error('Item not found in cart');
            return;
        }

        const currentQuantity = item.quantity;

        if (currentQuantity === 1) {
            const removeResponse = await fetch(`/api/cart/remove/${productId}`, {
                method: 'DELETE',
                headers: {
                    'Authorization': `Bearer ${authToken}`,
                },
            });

            if (!removeResponse.ok) {
                throw new Error(`HTTP error! status: ${removeResponse.status}`);
            }

            await removeResponse.json();
            updateCartCount();
            loadCartItems();
        } else {
            const updateResponse = await fetch(`/api/cart/update/${productId}`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${authToken}`,
                },
                body: JSON.stringify({
                    quantity: currentQuantity - 1
                }),
            });

            if (!updateResponse.ok) {
                throw new Error(`HTTP error! status: ${updateResponse.status}`);
            }

            await updateResponse.json();
            updateCartCount();
            loadCartItems();
        }
    } catch (error) {
        console.error('Error removing from cart:', error);
        alert('Failed to remove item from cart. Please try again later.');
    }
}

// Event Listeners
document.addEventListener('DOMContentLoaded', () => {
    fetchProducts();
    updateCartCount();
});

document.addEventListener('click', (event) => {
    if (event.target.classList.contains('add-to-cart')) {
        const productId = event.target.getAttribute('data-product-id');
        addToCart(productId);
    }

    if (event.target.classList.contains('remove-from-cart')) {
        const productId = event.target.getAttribute('data-product-id');
        removeFromCart(productId);
    }
});

// Cart Modal Events
document.querySelector('a[href="/cart/view"]').addEventListener('click', (event) => {
    event.preventDefault();
    const modal = document.getElementById('cart-modal');
    modal.classList.remove('hidden');
    loadCartItems();
});

document.getElementById('close-cart-modal').addEventListener('click', () => {
    document.getElementById('cart-modal').classList.add('hidden');
});

document.getElementById('close-cart').addEventListener('click', () => {
    document.getElementById('cart-modal').classList.add('hidden');
});

document.getElementById("checkout-button").addEventListener("click", async function () {
    try {
        const authToken = localStorage.getItem('authToken');
        if (!authToken) {
            alert('You must be logged in to checkout.');
            return;
        }
        const response = await fetch('/api/orders/place', {
            
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'Authorization': `Bearer ${authToken}`,
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });

        if (!response.ok) {
            const errorData = await response.json();
            alert(errorData.error || "Something went wrong!");
            return;
        }

        const data = await response.json();
        alert("Order placed successfully!");
        console.log("Order Details:", data);

        // Optionally, redirect to the orders page or reset the cart UI
        window.location.href = '/';
    } catch (error) {
        console.error("Error placing order:", error);
        alert("Failed to place the order. Please try again.");
    }
});







// Function to handle logout
function logoutUser(event) {
    event.preventDefault(); // Prevent the default link action (to the logout route)

    // Retrieve the token before deletion
   
    console.log('Token at logout is:', authToken);

    if (!authToken) {
        console.error('No auth token found!');
        return; // Optionally handle the case when there's no token.
    }

    // Optionally, make an API call to your logout endpoint
    fetch('/api/logout', {
        method: 'POST',
        headers: {
            'Authorization': `Bearer ${authToken}`, // Use the token here before deleting it
            'Content-Type': 'application/json',
        }
    })
    .then(response => {
        if (response.ok) {
            // Remove token from localStorage
            localStorage.removeItem('authToken');
            window.appConfig.authToken = null; // Reset the global authToken
            console.log('Token removed from localStorage');
           authToken = localStorage.getItem('authToken');
            console.log(authToken);
            // Redirect to the login page or show a success message after successful logout
            // window.location.href = ''; // Adjust this URL as needed
        } else {
            console.error('Failed to log out');
        }
    })
    .catch(error => {
        console.error('Logout error:', error);
        // Handle error if needed
    });
}
// Logout button event listener
document.getElementById('logout-button').addEventListener('click', async () => {
    try {
        const authToken = localStorage.getItem('authToken');
        
        if (!authToken) {
            console.log('No auth token found');
            window.location.href = '/login';
            return;
        }

        const response = await fetch('/api/logout', {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${authToken}`,
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
            }
        });

        if (response.ok) {
            // Clear authentication data
            localStorage.removeItem('authToken');
            window.appConfig.authToken = null;
            
            // Reset cart count
            document.querySelector('#cart-count').textContent = '0';
            
            // Redirect to home page or login page
            window.location.href = '/';
        } else {
            throw new Error('Logout failed');
        }
    } catch (error) {
        console.error('Logout error:', error);
        alert('Failed to logout. Please try again.');
    }
});

