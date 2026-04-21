<?php
session_start();
require_once "config/db.php";

if(!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'customer') {
    header("Location: login.php");
    exit;
}

$user = $_SESSION['user'];

$services = $conn->query("SELECT * FROM services LIMIT 6");
$products = $conn->query("SELECT * FROM products LIMIT 6");
?>

<!DOCTYPE html>
<html>
<head>
<title>SK Hair Salon</title>

<script src="https://cdn.tailwindcss.com"></script>

</head>
<body class="bg-gray-100">

<!-- NAVBAR -->
<div class="bg-white shadow-sm">
    <div class="max-w-6xl mx-auto px-6 py-4 flex justify-between items-center">

        <div class="text-xl font-bold text-orange-500">
            ✂ SK Hair Salon
        </div>

        <div class="space-x-6 text-sm">
            <a href="#" class="text-orange-500 font-semibold">Home</a>
            <a href="customer_services.php" class="hover:text-orange-500">Services</a>
            <a href="#" class="hover:text-orange-500">Products</a>
        </div>

        <div class="text-sm">
            <?= $user['name'] ?> |
            <a href="logout.php" class="text-red-500">Logout</a>
        </div>

    </div>
</div>

<!-- HERO -->
<div class="max-w-6xl mx-auto mt-10 px-6">

    <div class="bg-gray-800 text-white rounded-xl p-10 text-center shadow-lg">

        <h1 class="text-3xl font-bold mb-3">
            Beautiful Hair Starts Here
        </h1>

        <p class="text-gray-300 mb-6">
            Professional salon services at your convenience
        </p>

        <a href="customer_services.php" class="bg-orange-500 px-6 py-3 rounded-full shadow">
            Booking Sekarang
        </a>

    </div>

</div>

<!-- ================= LAYANAN ================= -->
<div class="max-w-6xl mx-auto mt-14 px-6">

    <h2 class="text-xl font-semibold mb-6">Layanan</h2>

    <div class="grid md:grid-cols-3 gap-6">

        <?php while($row = $services->fetch_assoc()): ?>

        <div class="bg-white rounded-xl shadow p-5 hover:shadow-lg transition">

            <?php if(!empty($row['image'])): ?>
                <img src="uploads/<?= $row['image'] ?>" class="w-full h-40 object-cover rounded mb-3">
            <?php endif; ?>

            <h3 class="font-semibold"><?= $row['name'] ?></h3>

            <p class="text-orange-500 font-bold mt-2">
                Rp<?= number_format($row['price'],0,',','.') ?>
            </p>

            <a href="booking.php?service_id=<?= $row['id'] ?>">
                <button class="mt-4 w-full bg-black text-white py-2 rounded-lg">
                    Booking
                </button>
            </a>

        </div>

        <?php endwhile; ?>

    </div>

</div>

<!-- ================= PRODUK ================= -->
<div class="max-w-6xl mx-auto mt-14 px-6 mb-10">

    <h2 class="text-xl font-semibold mb-6">Produk</h2>

    <div class="grid md:grid-cols-3 gap-6">

        <?php while($row = $products->fetch_assoc()): ?>

        <div class="bg-white rounded-xl shadow p-5 hover:shadow-lg transition">

            <?php if(!empty($row['image'])): ?>
                <img src="uploads/<?= $row['image'] ?>" class="w-full h-40 object-cover rounded mb-3">
            <?php endif; ?>

            <h3 class="font-semibold"><?= $row['name'] ?></h3>

            <p class="text-orange-500 font-bold mt-2">
                Rp<?= number_format($row['price'],0,',','.') ?>
            </p>

            <p class="text-sm text-gray-500">
                Stok: <?= $row['stock'] ?>
            </p>

            <button class="mt-4 w-full bg-gray-300 text-gray-600 py-2 rounded-lg cursor-not-allowed">
                Coming Soon
            </button>

        </div>

        <?php endwhile; ?>

    </div>

</div>

</body>
</html>