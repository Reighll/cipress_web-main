<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Registration - N.J Cipress</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .form-input { background-color: #E5E7EB; border: 1px solid #D1D5DB; color: #1F2937; padding: 0.75rem 1rem; border-radius: 0.5rem; width: 100%; }
        .form-input::placeholder { color: #6B7280; }
        .error-message { color: #EF4444; font-size: 0.875rem; margin-top: 0.25rem; }
    </style>
</head>
<body class="bg-gray-200 flex items-center justify-center min-h-screen">
<div class="container mx-auto p-4 flex flex-col items-center justify-center">
    <!-- Logo and Brand Name -->
    <div class="text-center mb-8">
        <div class="w-24 h-24 mb-4 mx-auto">
            <svg class="w-full h-full text-gray-800" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M14.6312 3.32194C15.8288 2.49346 17.4033 2.62683 18.4594 3.68292L19.5828 4.8063C20.6389 5.86239 20.7723 7.43687 19.9438 8.63445L14.401 17.2023C13.9398 17.8824 13.2948 18.4287 12.553 18.7749L10.3533 19.8747C9.29314 20.3956 8.05607 20.1305 7.22431 19.2987L3.9541 16.0285C3.12234 15.1968 2.85721 13.9597 3.37811 12.8995L4.47787 10.7C4.8241 9.95822 5.37043 9.31321 6.05055 8.85202L14.6312 3.32194Z" stroke="currentColor" stroke-width="1.5"/><path d="M19.0002 15.5C20.933 15.5 22.5002 17.067 22.5002 19C22.5002 20.933 20.933 22.5 19.0002 22.5C17.7642 22.5 16.6632 21.883 16.0002 21M19.0002 15.5C17.0672 15.5 15.5002 13.933 15.5002 12C15.5002 10.067 17.0672 8.5 19.0002 8.5C20.2362 8.5 21.3372 9.11701 22.0002 10" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
        </div>
        <h1 class="text-xl font-bold text-gray-800">N.J CIPRESS</h1>
        <p class="text-sm text-gray-600">GENERAL MERCHANDISE</p>
    </div>
    <div class="w-full max-w-lg">
        <h2 class="text-4xl font-bold text-gray-800 text-center mb-8">CREATE STAFF ACCOUNT</h2>
        <div class="bg-gray-800 rounded-2xl shadow-lg p-8 md:p-12 w-full">
            <!-- Notification Area -->
            <div id="notification" class="mb-4"></div>

            <!-- The form needs an ID for the JavaScript to find it -->
            <form id="registration-form" method="post">
                <div class="space-y-4">
                    <div>
                        <input type="text" name="firstname" placeholder="Enter First Name" class="form-input">
                        <div id="error-firstname" class="error-message"></div>
                    </div>
                    <div>
                        <input type="text" name="lastname" placeholder="Enter Last Name" class="form-input">
                        <div id="error-lastname" class="error-message"></div>
                    </div>
                    <div>
                        <input type="text" name="username" placeholder="Enter Username" class="form-input">
                        <div id="error-username" class="error-message"></div>
                    </div>
                    <div>
                        <input type="password" name="password" id="password" placeholder="Enter Password" class="form-input">
                        <div id="error-password" class="error-message"></div>
                    </div>
                    <div>
                        <input type="password" name="pass_confirm" id="pass_confirm" placeholder="Confirm Password" class="form-input">
                        <div id="error-pass_confirm" class="error-message"></div>
                    </div>
                    <div class="flex items-center">
                        <input type="checkbox" id="show_password" class="mr-2">
                        <label for="show_password" class="text-gray-400">Show Password</label>
                    </div>
                </div>
                <div class="flex justify-between items-center mt-8">
                    <a href="/register" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-8 rounded-lg">Back</a>
                    <!-- The button must be type="submit" to trigger the form's submit event -->
                    <button type="submit" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-8 rounded-lg">Register</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- This script handles the form submission -->
<script>
    document.getElementById('show_password').addEventListener('change', function() {
        const type = this.checked ? 'text' : 'password';
        document.getElementById('password').type = type;
        document.getElementById('pass_confirm').type = type;
    });

    document.getElementById('registration-form').addEventListener('submit', async function(e) {
        e.preventDefault();

        document.querySelectorAll('.error-message').forEach(el => el.innerHTML = '');
        document.getElementById('notification').innerHTML = '';

        const formData = new FormData(this);
        const submitButton = this.querySelector('button[type="submit"]');
        submitButton.disabled = true;
        submitButton.innerHTML = 'Registering...';

        try {
            const response = await fetch('/register/staff', {
                method: 'POST',
                headers: {'X-Requested-With': 'XMLHttpRequest'},
                body: formData
            });
            const result = await response.json();

            if (!response.ok) {
                if (result.messages) {
                    for (const key in result.messages) {
                        const errorDiv = document.getElementById(`error-${key}`);
                        if (errorDiv) {
                            errorDiv.innerHTML = result.messages[key];
                        }
                    }
                } else {
                    document.getElementById('notification').innerHTML = `<div class="p-4 bg-red-500 text-white rounded-lg">An unknown error occurred.</div>`;
                }
            } else {
                document.getElementById('notification').innerHTML = `<div class="p-4 bg-green-500 text-white rounded-lg">${result.messages.success}</div>`;
                this.reset();
                setTimeout(() => window.location.href = '/login/staff', 2000);
            }
        } catch (error) {
            console.error('Error:', error);
            document.getElementById('notification').innerHTML = `<div class="p-4 bg-red-500 text-white rounded-lg">A network error occurred.</div>`;
        } finally {
            submitButton.disabled = false;
            submitButton.innerHTML = 'Register';
        }
    });
</script>
</body>
</html>
