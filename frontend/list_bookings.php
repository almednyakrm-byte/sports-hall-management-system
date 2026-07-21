<?php
// Session validation
session_start();
if (!isset($_SESSION['authenticated'])) {
    header('Location: login.php');
    exit;
}

// Get current user info
$current_user = $_SESSION['user'];

// Include database connection
include '../backend/db.php';

// Get bookings data
$bookings = array();
if ($result = $mysqli->query("SELECT * FROM bookings")) {
    while ($row = $result->fetch_assoc()) {
        $bookings[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bookings</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <header class="bg-orange-500 text-white p-4">
        <nav class="container mx-auto flex justify-between">
            <a href="index.php" class="text-lg font-bold">Home</a>
            <span class="text-lg font-bold">Welcome, <?= $current_user['name'] ?></span>
            <a href="logout.php" class="text-lg font-bold">Logout</a>
        </nav>
    </header>
    <main class="container mx-auto p-4 pt-6 md:p-6 lg:p-12 xl:p-24">
        <h1 class="text-3xl font-bold mb-4">Bookings</h1>
        <div class="flex justify-between mb-4">
            <a href="create_bookings.php" class="bg-orange-500 hover:bg-orange-700 text-white font-bold py-2 px-4 rounded">Add New Item</a>
            <input type="text" id="search" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold py-2 px-4 rounded" placeholder="Search...">
        </div>
        <table id="bookings-table" class="w-full table-auto border-collapse border border-gray-200">
            <thead class="bg-gray-200">
                <tr>
                    <th class="border border-gray-200 p-2">ID</th>
                    <th class="border border-gray-200 p-2">Name</th>
                    <th class="border border-gray-200 p-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($bookings as $booking) { ?>
                <tr>
                    <td class="border border-gray-200 p-2"><?= $booking['id'] ?></td>
                    <td class="border border-gray-200 p-2"><?= $booking['name'] ?></td>
                    <td class="border border-gray-200 p-2">
                        <a href="edit_bookings.php?id=<?= $booking['id'] ?>" class="bg-orange-500 hover:bg-orange-700 text-white font-bold py-2 px-4 rounded">Edit</a>
                        <button class="bg-orange-500 hover:bg-orange-700 text-white font-bold py-2 px-4 rounded" onclick="deleteBooking(<?= $booking['id'] ?>)">Delete</button>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </main>

    <script>
        // Fetch bookings data
        async function fetchBookings() {
            const response = await fetch('../backend/bookings.php');
            const data = await response.json();
            return data;
        }

        // Delete booking
        async function deleteBooking(id) {
            const response = await fetch('../backend/bookings.php', {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ id: id })
            });
            const data = await response.json();
            if (data.success) {
                location.reload();
            } else {
                alert('Error deleting booking');
            }
        }

        // Search bookings
        const searchInput = document.getElementById('search');
        searchInput.addEventListener('input', async (e) => {
            const searchQuery = e.target.value.toLowerCase();
            const bookingsTable = document.getElementById('bookings-table');
            const rows = bookingsTable.getElementsByTagName('tr');
            for (let i = 0; i < rows.length; i++) {
                const row = rows[i];
                const cells = row.getElementsByTagName('td');
                let match = false;
                for (let j = 0; j < cells.length; j++) {
                    const cell = cells[j];
                    if (cell.textContent.toLowerCase().includes(searchQuery)) {
                        match = true;
                        break;
                    }
                }
                if (match) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            }
        });
    </script>
</body>
</html>