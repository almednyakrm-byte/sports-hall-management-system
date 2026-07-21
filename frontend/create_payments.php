<?php
// Start session
session_start();

// Session validation
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Include database connection
include '../backend/db.php';

// Module slug
$mod_slug = 'payments';

// Page title
$page_title = 'Create Payment';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <div class="max-w-5xl mx-auto p-4 sm:p-6 md:p-8">
        <h1 class="text-3xl text-orange-500 font-bold mb-4"><?php echo $page_title; ?></h1>
        <form id="create-payment-form">
            <div class="mb-4">
                <label for="payment_method" class="block text-gray-200 text-sm font-medium mb-2">Payment Method:</label>
                <select id="payment_method" name="payment_method" class="block w-full p-2 pl-10 text-gray-200 bg-gray-200 border border-gray-200 rounded-md focus:outline-none focus:ring-orange-500 focus:border-orange-500 sm:text-sm">
                    <option value="">Select Payment Method</option>
                    <option value="cash">Cash</option>
                    <option value="credit_card">Credit Card</option>
                    <option value="bank_transfer">Bank Transfer</option>
                </select>
            </div>
            <div class="mb-4">
                <label for="payment_date" class="block text-gray-200 text-sm font-medium mb-2">Payment Date:</label>
                <input type="date" id="payment_date" name="payment_date" class="block w-full p-2 pl-10 text-gray-200 bg-gray-200 border border-gray-200 rounded-md focus:outline-none focus:ring-orange-500 focus:border-orange-500 sm:text-sm">
            </div>
            <div class="mb-4">
                <label for="amount" class="block text-gray-200 text-sm font-medium mb-2">Amount:</label>
                <input type="number" id="amount" name="amount" class="block w-full p-2 pl-10 text-gray-200 bg-gray-200 border border-gray-200 rounded-md focus:outline-none focus:ring-orange-500 focus:border-orange-500 sm:text-sm">
            </div>
            <div class="mb-4">
                <label for="description" class="block text-gray-200 text-sm font-medium mb-2">Description:</label>
                <textarea id="description" name="description" class="block w-full p-2 pl-10 text-gray-200 bg-gray-200 border border-gray-200 rounded-md focus:outline-none focus:ring-orange-500 focus:border-orange-500 sm:text-sm"></textarea>
            </div>
            <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-orange-500 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">Create Payment</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#create-payment-form').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    type: 'POST',
                    url: '../backend/payments.php',
                    data: $(this).serialize(),
                    success: function(response) {
                        window.location.href = 'list_<?php echo $mod_slug; ?>.php';
                    }
                });
            });
        });
    </script>
</body>
</html>