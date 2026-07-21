<?php
// Session validation
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

// Get membership ID from URL
$membership_id = $_GET['id'];

// Include database connection
require_once '../backend/db.php';

// Query to get membership details
$query = "SELECT * FROM memberships WHERE id = '$membership_id'";
$result = mysqli_query($conn, $query);
$membership = mysqli_fetch_assoc($result);

// Close database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Membership</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <div class="max-w-md mx-auto mt-10 p-4 bg-gray-200 rounded-lg shadow-md">
        <h2 class="text-lg font-bold text-orange-500">Edit Membership</h2>
        <form id="edit-membership-form">
            <div class="mt-4">
                <label for="name" class="block text-gray-700">Name</label>
                <input type="text" id="name" name="name" value="<?php echo $membership['name']; ?>" class="block w-full p-2 mt-1 text-gray-700 border border-gray-200 rounded-lg focus:outline-none focus:ring-orange-500 focus:border-orange-500">
            </div>
            <div class="mt-4">
                <label for="description" class="block text-gray-700">Description</label>
                <textarea id="description" name="description" class="block w-full p-2 mt-1 text-gray-700 border border-gray-200 rounded-lg focus:outline-none focus:ring-orange-500 focus:border-orange-500"><?php echo $membership['description']; ?></textarea>
            </div>
            <div class="mt-4">
                <label for="price" class="block text-gray-700">Price</label>
                <input type="number" id="price" name="price" value="<?php echo $membership['price']; ?>" class="block w-full p-2 mt-1 text-gray-700 border border-gray-200 rounded-lg focus:outline-none focus:ring-orange-500 focus:border-orange-500">
            </div>
            <button type="submit" class="mt-4 py-2 px-4 bg-orange-500 text-white rounded-lg hover:bg-orange-700 focus:outline-none focus:ring-orange-500 focus:border-orange-500">Update Membership</button>
        </form>
    </div>

    <script>
        const form = document.getElementById('edit-membership-form');
        form.addEventListener('submit', (e) => {
            e.preventDefault();
            const formData = new FormData(form);
            fetch('../backend/memberships.php', {
                method: 'PUT',
                body: formData,
            })
            .then((response) => response.json())
            .then((data) => {
                if (data.success) {
                    window.location.href = 'list_memberships.php';
                } else {
                    console.error(data.error);
                }
            })
            .catch((error) => console.error(error));
        });
    </script>
</body>
</html>