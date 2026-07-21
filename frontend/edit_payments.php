<?php
// edit_payments.php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if (!isset($_GET['id'])) {
    header('Location: list_payments.php');
    exit;
}

$id = $_GET['id'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Payment</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <div class="max-w-md mx-auto mt-10 p-4 bg-gray-200 rounded-lg shadow-md">
        <h2 class="text-2xl font-bold text-orange-500 mb-4">Edit Payment</h2>
        <form id="edit-payment-form">
            <div class="mb-4">
                <label for="payment_date" class="block text-gray-700 text-sm font-bold mb-2">Payment Date</label>
                <input type="date" id="payment_date" name="payment_date" class="block w-full p-2 bg-gray-200 border border-gray-200 rounded-lg focus:outline-none focus:ring-orange-500 focus:border-orange-500">
            </div>
            <div class="mb-4">
                <label for="amount" class="block text-gray-700 text-sm font-bold mb-2">Amount</label>
                <input type="number" id="amount" name="amount" class="block w-full p-2 bg-gray-200 border border-gray-200 rounded-lg focus:outline-none focus:ring-orange-500 focus:border-orange-500">
            </div>
            <div class="mb-4">
                <label for="description" class="block text-gray-700 text-sm font-bold mb-2">Description</label>
                <textarea id="description" name="description" class="block w-full p-2 bg-gray-200 border border-gray-200 rounded-lg focus:outline-none focus:ring-orange-500 focus:border-orange-500"></textarea>
            </div>
            <button type="submit" class="bg-orange-500 hover:bg-orange-700 text-white font-bold py-2 px-4 rounded-lg">Update Payment</button>
        </form>
    </div>

    <script>
        const form = document.getElementById('edit-payment-form');
        const id = <?php echo $id; ?>;

        // Fetch existing record details
        fetch(`../backend/payments.php?id=${id}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('payment_date').value = data.payment_date;
                document.getElementById('amount').value = data.amount;
                document.getElementById('description').value = data.description;
            });

        // Submit form with AJAX PUT request
        form.addEventListener('submit', (e) => {
            e.preventDefault();
            const formData = new FormData(form);
            fetch('../backend/payments.php', {
                method: 'PUT',
                body: JSON.stringify({
                    id: id,
                    payment_date: formData.get('payment_date'),
                    amount: formData.get('amount'),
                    description: formData.get('description')
                }),
                headers: {
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = 'list_payments.php';
                } else {
                    console.error(data.error);
                }
            })
            .catch(error => console.error(error));
        });
    </script>
</body>
</html>