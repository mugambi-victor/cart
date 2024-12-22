<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 flex justify-center items-center h-screen">
    <div class="bg-white p-8 rounded shadow-md w-full max-w-md">
        <h2 class="text-2xl font-bold mb-6 text-center">Login</h2>

        <!-- Login Form -->
        <form id="login-form" class="space-y-4">
            <!-- Email Field -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" id="email" name="email" required
                    class="mt-1 p-2 w-full border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500" />
            </div>

            <!-- Password Field -->
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <input type="password" id="password" name="password" required
                    class="mt-1 p-2 w-full border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500" />
            </div>

            <!-- Redirect Field (hidden) -->
            <input type="hidden" name="redirectTo" id="redirectTo" value="{{ request('redirectTo', route('index')) }}">

            <!-- Submit Button -->
            <button type="submit" id="login-button"
                class="w-full bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700 transition">
                Login
            </button>
        </form>

        <!-- Error Message -->
        <div id="error-message" class="text-red-500 mt-4 text-center hidden">Invalid credentials. Please try again.
        </div>
    </div>

    <script>
    // Function to handle the login form submission via API
    document.getElementById('login-form').addEventListener('submit', async function(event) {
        event.preventDefault();

        // Get user inputs
        const email = document.getElementById('email').value;
        const password = document.getElementById('password').value;

        // Prepare the payload
        const payload = {
            email: email,
            password: password
        };

        try {
            const response = await fetch('/api/login', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(payload)
            });

            const data = await response.json();

            if (response.ok) {
                // Storing the token in localStorage for subsequent api calls
                localStorage.setItem('authToken', data.token);
                const redirectTo = document.getElementById('redirectTo').value;
                window.location.href = redirectTo;
            } else {
                // Show error message if login fails
                document.getElementById('error-message').classList.remove('hidden');
            }
        } catch (error) {
            // Handle any other errors
            console.error('Error during login:', error);
            document.getElementById('error-message').classList.remove('hidden');
        }
    });
    </script>
</body>

</html>