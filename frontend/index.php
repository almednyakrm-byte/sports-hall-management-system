<?php
// Session check
session_start();
if (!isset($_SESSION['authenticated'])) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>نظام إدارة صالات رياضية</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="h-screen bg-gray-200">
    <header class="bg-orange-500 text-white p-4 text-center">
        <h1 class="text-3xl font-bold">نظام إدارة صالات رياضية</h1>
    </header>
    <main class="container mx-auto p-4 pt-6 md:p-6 lg:p-12 xl:p-24">
        <div class="flex justify-end mb-4">
            <button class="bg-orange-500 hover:bg-orange-700 text-white font-bold py-2 px-4 rounded" onclick="window.location.href='logout.php'">تسجيل خروج</button>
        </div>
        <h2 class="text-2xl font-bold mb-4">مرحباً <?php echo $_SESSION['username']; ?></h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <div class="bg-white rounded shadow-md p-4 glassmorphism">
                <h3 class="text-xl font-bold mb-2">إجمالي الاشتراكات</h3>
                <p id="total-memberships" class="text-3xl font-bold"></p>
            </div>
            <div class="bg-white rounded shadow-md p-4 glassmorphism">
                <h3 class="text-xl font-bold mb-2">إجمالي الحجوزات</h3>
                <p id="total-bookings" class="text-3xl font-bold"></p>
            </div>
            <div class="bg-white rounded shadow-md p-4 glassmorphism">
                <h3 class="text-xl font-bold mb-2">إجمالي المدفوعات</h3>
                <p id="total-payments" class="text-3xl font-bold"></p>
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mt-4">
            <div class="bg-white rounded shadow-md p-4 glassmorphism">
                <h3 class="text-xl font-bold mb-2">إدارة الاشتراكات</h3>
                <button class="bg-orange-500 hover:bg-orange-700 text-white font-bold py-2 px-4 rounded" onclick="window.location.href='memberships.php'">إدارة الاشتراكات</button>
            </div>
            <div class="bg-white rounded shadow-md p-4 glassmorphism">
                <h3 class="text-xl font-bold mb-2">إدارة الحجوزات</h3>
                <button class="bg-orange-500 hover:bg-orange-700 text-white font-bold py-2 px-4 rounded" onclick="window.location.href='bookings.php'">إدارة الحجوزات</button>
            </div>
            <div class="bg-white rounded shadow-md p-4 glassmorphism">
                <h3 class="text-xl font-bold mb-2">إدارة المدفوعات</h3>
                <button class="bg-orange-500 hover:bg-orange-700 text-white font-bold py-2 px-4 rounded" onclick="window.location.href='payments.php'">إدارة المدفوعات</button>
            </div>
        </div>
    </main>

    <script>
        // Fetch stats dynamically via Javascript API calls from the backend files
        fetch('api/stats.php')
            .then(response => response.json())
            .then(data => {
                document.getElementById('total-memberships').innerText = data.totalMemberships;
                document.getElementById('total-bookings').innerText = data.totalBookings;
                document.getElementById('total-payments').innerText = data.totalPayments;
            });
    </script>

    <style>
        .glassmorphism {
            background: rgba(255, 255, 255, 0.1);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(5px);
            -webkit-backdrop-filter: blur(5px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
    </style>
</body>
</html>