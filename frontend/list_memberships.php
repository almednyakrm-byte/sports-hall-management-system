<?php
// Session validation
session_start();
if (!isset($_SESSION['authenticated'])) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Memberships</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <header class="bg-orange-500 text-white p-4">
        <nav class="flex justify-between">
            <a href="index.php" class="text-lg font-bold">Back to Index</a>
            <span class="text-lg font-bold">Welcome, <?php echo $_SESSION['username']; ?></span>
            <a href="logout.php" class="text-lg font-bold">Logout</a>
        </nav>
    </header>
    <main class="p-4">
        <h1 class="text-3xl font-bold mb-4">Memberships</h1>
        <div class="flex justify-between mb-4">
            <button class="bg-orange-500 hover:bg-orange-700 text-white font-bold py-2 px-4 rounded">
                <a href="create_memberships.php" class="text-white">Add New Item</a>
            </button>
            <input type="search" id="search" class="bg-gray-200 p-2 rounded" placeholder="Search...">
        </div>
        <table id="memberships-table" class="w-full table-auto border-collapse border border-gray-200">
            <thead class="bg-gray-200">
                <tr>
                    <th class="border border-gray-200 p-2">ID</th>
                    <th class="border border-gray-200 p-2">Name</th>
                    <th class="border border-gray-200 p-2">Actions</th>
                </tr>
            </thead>
            <tbody id="memberships-tbody">
                <!-- Table content will be populated via AJAX -->
            </tbody>
        </table>
    </main>

    <script>
        // Fetch API to get memberships data
        fetch('../backend/memberships.php')
            .then(response => response.json())
            .then(data => {
                const tbody = document.getElementById('memberships-tbody');
                data.forEach(membership => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td class="border border-gray-200 p-2">${membership.id}</td>
                        <td class="border border-gray-200 p-2">${membership.name}</td>
                        <td class="border border-gray-200 p-2">
                            <a href="edit_memberships.php?id=${membership.id}" class="text-orange-500 hover:text-orange-700">Edit</a>
                            <button class="text-red-500 hover:text-red-700" onclick="deleteMembership(${membership.id})">Delete</button>
                        </td>
                    `;
                    tbody.appendChild(row);
                });
            });

        // Delete membership via AJAX
        function deleteMembership(id) {
            fetch('../backend/memberships.php', {
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
                    const rows = document.getElementById('memberships-tbody').children;
                    for (let i = 0; i < rows.length; i++) {
                        if (rows[i].children[0].textContent == id) {
                            rows[i].remove();
                            break;
                        }
                    }
                } else {
                    console.error('Error deleting membership:', data.error);
                }
            });
        }

        // Search bar filtering
        const searchInput = document.getElementById('search');
        searchInput.addEventListener('input', () => {
            const filter = searchInput.value.toLowerCase();
            const rows = document.getElementById('memberships-tbody').children;
            for (let i = 0; i < rows.length; i++) {
                const row = rows[i];
                const nameCell = row.children[1];
                if (nameCell.textContent.toLowerCase().includes(filter)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            }
        });
    </script>
</body>
</html>