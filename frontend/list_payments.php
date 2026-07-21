<?php
// Session validation
session_start();
if (!isset($_SESSION['authenticated'])) {
    header('Location: login.php');
    exit;
}

// Current user info
$current_user = $_SESSION['username'];

// Logout
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payments List</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <header class="bg-orange-500 text-white p-4">
        <nav class="container mx-auto flex justify-between">
            <a href="index.php" class="text-lg font-bold">Back to Index</a>
            <span class="text-lg font-bold">Welcome, <?php echo $current_user; ?></span>
            <a href="?logout" class="text-lg font-bold">Logout</a>
        </nav>
    </header>
    <main class="container mx-auto p-4 pt-6 md:p-6 lg:p-12 xl:p-24">
        <h1 class="text-3xl font-bold mb-4">Payments List</h1>
        <div class="flex justify-between mb-4">
            <button class="bg-orange-500 hover:bg-orange-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='create_payments.php'">Add New Item</button>
            <input type="search" id="search" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold py-2 px-4 rounded" placeholder="Search...">
        </div>
        <table id="payments-table" class="w-full table-auto border-collapse border border-gray-200">
            <thead class="bg-gray-200">
                <tr>
                    <th class="border border-gray-200 p-2">ID</th>
                    <th class="border border-gray-200 p-2">Name</th>
                    <th class="border border-gray-200 p-2">Amount</th>
                    <th class="border border-gray-200 p-2">Actions</th>
                </tr>
            </thead>
            <tbody id="payments-tbody">
                <!-- Table content will be populated by JavaScript -->
            </tbody>
        </table>
    </main>

    <script>
        // Fetch API to get payments list
        fetch('../backend/payments.php')
            .then(response => response.json())
            .then(data => {
                const tbody = document.getElementById('payments-tbody');
                data.forEach(payment => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td class="border border-gray-200 p-2">${payment.id}</td>
                        <td class="border border-gray-200 p-2">${payment.name}</td>
                        <td class="border border-gray-200 p-2">${payment.amount}</td>
                        <td class="border border-gray-200 p-2">
                            <a href="edit_payments.php?id=${payment.id}" class="text-orange-500 hover:text-orange-700">Edit</a>
                            <button class="text-red-500 hover:text-red-700" onclick="deletePayment(${payment.id})">Delete</button>
                        </td>
                    `;
                    tbody.appendChild(row);
                });
            });

        // Delete payment using AJAX
        function deletePayment(id) {
            fetch('../backend/payments.php', {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ id: id })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Remove the deleted row from the table
                    const rows = document.getElementById('payments-tbody').children;
                    for (let i = 0; i < rows.length; i++) {
                        if (rows[i].children[0].textContent == id) {
                            rows[i].remove();
                            break;
                        }
                    }
                } else {
                    console.error('Error deleting payment:', data.error);
                }
            });
        }

        // Search bar filtering
        const searchInput = document.getElementById('search');
        searchInput.addEventListener('input', () => {
            const filter = searchInput.value.toLowerCase();
            const rows = document.getElementById('payments-tbody').children;
            for (let i = 0; i < rows.length; i++) {
                const row = rows[i];
                const text = row.textContent.toLowerCase();
                if (text.includes(filter)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            }
        });
    </script>
</body>
</html>