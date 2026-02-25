<?php
// Donor Registration Page
require_once __DIR__ . '/../includes/functions.php';

// Redirect if already logged in
if (isDonorLoggedIn()) {
    header("Location: donor_dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CareBox - Register</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css?family=Inter:wght@100..900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f8fafc; }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md bg-white rounded-xl shadow-2xl overflow-hidden">
        <!-- Header -->
        <div class="bg-gradient-to-r from-green-600 to-green-800 p-6 text-center">
            <h1 class="text-3xl font-bold text-white">CareBox</h1>
            <p class="text-green-100 mt-1">Join our community of donors</p>
        </div>

        <!-- Registration Form -->
        <div class="p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Create Donor Account</h2>
            <form id="registerForm" onsubmit="return handleRegister(event)">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                    <input type="text" name="name" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                        placeholder="Enter your full name">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                        placeholder="Enter your email">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Phone Number (10 digits)</label>
                    <input type="tel" name="phone" required maxlength="10" pattern="[0-9]{10}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                        placeholder="Enter 10-digit phone number">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Password (min 6 characters)</label>
                    <input type="password" name="password" required minlength="6"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                        placeholder="Create a password">
                </div>
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                    <input type="password" name="confirm_password" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                        placeholder="Confirm your password">
                </div>
                <button type="submit" id="submitBtn"
                    class="w-full bg-green-600 text-white font-semibold py-3 rounded-lg hover:bg-green-700 transition">
                    Create Account
                </button>
            </form>
            <p class="text-center mt-4 text-sm text-gray-600">
                Already have an account? 
                <a href="login.php" class="text-blue-600 hover:text-blue-800 font-medium">Login here</a>
            </p>
        </div>

        <!-- Success Message -->
        <div id="successMessage" class="hidden mx-6 mb-4 p-3 rounded-lg bg-green-100 text-green-700">
            Registration successful! Redirecting to login...
        </div>

        <!-- Error Message -->
        <div id="errorMessage" class="hidden mx-6 mb-4 p-3 rounded-lg bg-red-100 text-red-700"></div>
    </div>

    <script>
        // Show error message
        function showError(message) {
            const errorDiv = document.getElementById('errorMessage');
            errorDiv.textContent = message;
            errorDiv.classList.remove('hidden');
            document.getElementById('successMessage').classList.add('hidden');
        }

        // Show success message
        function showSuccess(message) {
            const successDiv = document.getElementById('successMessage');
            successDiv.textContent = message;
            successDiv.classList.remove('hidden');
            document.getElementById('errorMessage').classList.add('hidden');
        }

        // Handle registration
        async function handleRegister(event) {
            event.preventDefault();

            const form = event.target;
            const submitBtn = document.getElementById('submitBtn');

            // Check if passwords match
            if (form.password.value !== form.confirm_password.value) {
                showError('Passwords do not match');
                return false;
            }

            submitBtn.disabled = true;
            submitBtn.textContent = 'Creating account...';

            const formData = new FormData(form);

            try {
                const response = await fetch('../api/donor_register.php', {
                    method: 'POST',
                    body: formData
                });

                const data = await response.json();

                if (data.success) {
                    showSuccess('Registration successful! Redirecting to login...');
                    setTimeout(() => {
                        window.location.href = 'login.php';
                    }, 2000);
                } else {
                    showError(data.message);
                    submitBtn.disabled = false;
                    submitBtn.textContent = 'Create Account';
                }
            } catch (error) {
                showError('An error occurred. Please try again.');
                submitBtn.disabled = false;
                submitBtn.textContent = 'Create Account';
            }

            return false;
        }
    </script>
</body>
</html>
