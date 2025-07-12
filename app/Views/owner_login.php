<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Owner Login - N.J Cipress</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        .form-input {
            background-color: #E5E7EB; /* gray-200 */
            border: 1px solid #D1D5DB; /* gray-300 */
            color: #1F2937; /* gray-800 */
            padding: 0.75rem 1rem;
            border-radius: 0.5rem;
            width: 100%;
        }
        .form-input::placeholder {
            color: #6B7280; /* gray-500 */
        }
    </style>
</head>
<body class="bg-gray-200 flex items-center justify-center min-h-screen">

<div class="container mx-auto p-4">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
        <!-- Logo and Brand Name -->
        <div class="flex flex-col items-center justify-center text-center">
            <div class="w-40 h-40 mb-4">
                <svg class="w-full h-full text-gray-800" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M14.6312 3.32194C15.8288 2.49346 17.4033 2.62683 18.4594 3.68292L19.5828 4.8063C20.6389 5.86239 20.7723 7.43687 19.9438 8.63445L14.401 17.2023C13.9398 17.8824 13.2948 18.4287 12.553 18.7749L10.3533 19.8747C9.29314 20.3956 8.05607 20.1305 7.22431 19.2987L3.9541 16.0285C3.12234 15.1968 2.85721 13.9597 3.37811 12.8995L4.47787 10.7C4.8241 9.95822 5.37043 9.31321 6.05055 8.85202L14.6312 3.32194Z" stroke="currentColor" stroke-width="1.5"/>
                    <path d="M19.0002 15.5C20.933 15.5 22.5002 17.067 22.5002 19C22.5002 20.933 20.933 22.5 19.0002 22.5C17.7642 22.5 16.6632 21.883 16.0002 21M19.0002 15.5C17.0672 15.5 15.5002 13.933 15.5002 12C15.5002 10.067 17.0672 8.5 19.0002 8.5C20.2362 8.5 21.3372 9.11701 22.0002 10" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                </svg>
            </div>
            <h1 class="text-3xl font-bold text-gray-800">N.J CIPRESS</h1>
            <p class="text-gray-600">GENERAL MERCHANDISE</p>
        </div>

        <!-- Login Form -->
        <div class="bg-gray-800 rounded-2xl shadow-lg p-8 md:p-12 max-w-md mx-auto w-full">
            <h2 class="text-3xl font-bold text-white text-center mb-8">WELCOME</h2>

            <!-- Notification Area -->
            <div id="notification" class="mb-4"></div>

            <form id="login-form" method="post">
                <div class="space-y-6">
                    <input type="text" name="username" placeholder="Enter Username" class="form-input">
                    <input type="password" name="password" id="password" placeholder="Enter Password" class="form-input">
                    <div class="flex items-center">
                        <input type="checkbox" id="show_password" class="mr-2">
                        <label for="show_password" class="text-gray-400">Show Password</label>
                    </div>
                </div>
                <div class="flex justify-between items-center mt-8">
                    <a href="/" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-8 rounded-lg transition duration-300 inline-block">
                        Back
                    </a>
                    <button type="submit" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-8 rounded-lg transition duration-300">
                        Log in
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // --- Show/Hide Password ---
    const showPasswordCheckbox = document.getElementById('show_password');
    const passwordInput = document.getElementById('password');

    showPasswordCheckbox.addEventListener('change', function() {
        const type = this.checked ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
    });

    // --- AJAX Login Form Submission ---
    const form = document.getElementById('login-form');
    const notificationArea = document.getElementById('notification');

    form.addEventListener('submit', async function(event) {
        event.preventDefault(); // Stop the default page reload

        notificationArea.innerHTML = ''; // Clear previous notifications
        const formData = new FormData(form);
        const submitButton = form.querySelector('button[type="submit"]');
        submitButton.disabled = true;
        submitButton.innerHTML = 'Logging in...';

        try {
            const response = await fetch('/login/owner', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            });

            const result = await response.json();

            if (!response.ok) {
                // Handle login failure
                let errorMessage = result.messages.error || 'An unknown error occurred.';
                notificationArea.innerHTML = `<div class="p-4 bg-red-500 text-white rounded-lg">${errorMessage}</div>`;
            } else {
                // Handle success
                notificationArea.innerHTML = `<div class="p-4 bg-green-500 text-white rounded-lg">${result.messages.success}</div>`;
                // Redirect to a dashboard after a short delay
                setTimeout(() => {
                    window.location.href = '/owner/dashboard'; // We will create this page next
                }, 1500);
            }

        } catch (error) {
            console.error('Error:', error);
            notificationArea.innerHTML = `<div class="p-4 bg-red-500 text-white rounded-lg">A network error occurred. Please try again.</div>`;
        } finally {
            submitButton.disabled = false;
            submitButton.innerHTML = 'Log in';
        }
    });
</script>

</body>
</html>
