<?php
// Initialize session
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="h-screen bg-gray-200 flex justify-center items-center">
    <div class="bg-white p-8 rounded shadow-md w-1/2">
        <h2 class="text-orange-500 text-3xl font-bold mb-4">Register</h2>
        <form id="register-form">
            <div class="mb-4">
                <label for="username" class="block text-gray-700 font-bold mb-2">Username</label>
                <input type="text" id="username" name="username" required pattern="[A-Za-z\u0600-\u06FF0-9\s]+" class="block w-full p-2 border border-gray-400 rounded">
                <div class="text-red-500 text-xs" id="username-error"></div>
            </div>
            <div class="mb-4">
                <label for="email" class="block text-gray-700 font-bold mb-2">Email</label>
                <input type="email" id="email" name="email" required class="block w-full p-2 border border-gray-400 rounded">
                <div class="text-red-500 text-xs" id="email-error"></div>
            </div>
            <div class="mb-4">
                <label for="password" class="block text-gray-700 font-bold mb-2">Password</label>
                <input type="password" id="password" name="password" required pattern="[A-Za-z\u0600-\u06FF0-9\s]+" class="block w-full p-2 border border-gray-400 rounded">
                <div class="text-red-500 text-xs" id="password-error"></div>
            </div>
            <button type="submit" class="bg-orange-500 hover:bg-orange-700 text-white font-bold py-2 px-4 rounded">Register</button>
        </form>
        <div class="text-green-500 text-xs" id="success-message"></div>
    </div>

    <script>
        const registerForm = document.getElementById('register-form');
        registerForm.addEventListener('submit', (e) => {
            e.preventDefault();
            const username = document.getElementById('username').value;
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;

            // Clear error messages
            document.getElementById('username-error').innerText = '';
            document.getElementById('email-error').innerText = '';
            document.getElementById('password-error').innerText = '';

            // Validate form fields
            if (username.length < 3) {
                document.getElementById('username-error').innerText = 'Username must be at least 3 characters long';
                return;
            }
            if (!email.includes('@')) {
                document.getElementById('email-error').innerText = 'Invalid email address';
                return;
            }
            if (password.length < 8) {
                document.getElementById('password-error').innerText = 'Password must be at least 8 characters long';
                return;
            }

            // Send AJAX request
            fetch('../backend/auth.php?action=register', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    username: username,
                    email: email,
                    password: password
                })
            })
            .then((response) => response.json())
            .then((data) => {
                if (data.success) {
                    document.getElementById('success-message').innerText = 'Registration successful!';
                } else {
                    document.getElementById('username-error').innerText = data.error;
                }
            })
            .catch((error) => {
                console.error(error);
            });
        });
    </script>
</body>
</html>