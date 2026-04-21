<?php
session_start();
require_once "config/db.php";

$services = $conn->query("SELECT * FROM services LIMIT 6");
?>

<!DOCTYPE html>
<html>
<head>
<title>SK Hair Salon</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50 text-gray-800">

<!-- NAVBAR -->
<nav class="bg-white shadow-sm">
    <div class="max-w-6xl mx-auto px-6 py-4 flex justify-between items-center">

        <div class="text-xl font-bold text-orange-500">
            ✂ SK HAIR SALON
        </div>

        <div class="space-x-6 text-sm hidden md:block">
            <a href="#" class="text-orange-500 font-semibold">Home</a>
            <a href="customer_services.php" class="hover:text-orange-500">Services</a>
            <a href="#" class="hover:text-orange-500">About</a>
        </div>

        <div class="space-x-3">

            <?php if(isset($_SESSION['user'])): ?>

                <span class="text-sm font-semibold">
                    <?= $_SESSION['user']['name'] ?>
                </span>

                <a href="logout.php" class="text-red-500 ml-3">Logout</a>

            <?php else: ?>

                <a href="login.php" class="text-sm">Login</a>

                <a href="register.php"
                   class="bg-orange-500 text-white px-4 py-2 rounded-lg text-sm">
                   Daftar
                </a>

            <?php endif; ?>

        </div>

    </div>
</nav>

<!-- HERO -->
<section class="relative h-[500px] flex items-center justify-center text-center">

    <div class="absolute inset-0 bg-black/60"></div>

    <img src="https://images.unsplash.com/photo-1522335789203-aabd1fc54bc9"
         class="absolute inset-0 w-full h-full object-cover">

    <div class="relative z-10 text-white px-6">

        <h1 class="text-4xl md:text-5xl font-bold mb-4">
            Beautiful Hair Starts Here
        </h1>

        <p class="text-gray-200 mb-6">
            Professional salon services at your convenience
        </p>

        <?php if(isset($_SESSION['user'])): ?>

            <a href="customer_services.php"
               class="bg-orange-500 px-6 py-3 rounded-full font-semibold shadow-lg">
                Booking Sekarang
            </a>

        <?php else: ?>

            <div class="flex justify-center gap-4">
                <a href="login.php"
                   class="bg-orange-500 px-6 py-3 rounded-full font-semibold">
                    Login
                </a>

                <a href="register.php"
                   class="bg-white text-black px-6 py-3 rounded-lg">
                    Daftar
                </a>
            </div>

        <?php endif; ?>

    </div>
</section>

<!-- SERVICES -->
<section class="max-w-6xl mx-auto px-6 py-16">

    <h2 class="text-center text-2xl font-bold mb-2">
        Our Services
    </h2>

    <div class="w-20 h-1 bg-orange-500 mx-auto mb-10"></div>

    <div class="grid md:grid-cols-3 gap-6">

        <?php while($row = $services->fetch_assoc()): ?>

        <div class="bg-white rounded-xl shadow hover:shadow-lg transition p-5">

            <h3 class="font-semibold text-lg">
                <?= $row['name'] ?>
            </h3>

            <p class="text-orange-500 font-bold mt-2">
                Rp<?= number_format($row['price'],0,',','.') ?>
            </p>

            <p class="text-sm text-gray-500">
                ⏱ <?= $row['duration'] ?> menit
            </p>

            <a href="customer_services.php">
                <button class="mt-4 w-full bg-black text-white py-2 rounded-lg">
                    Booking
                </button>
            </a>

        </div>

        <?php endwhile; ?>

    </div>

</section>

<footer class="bg-gray-900 text-white text-center py-6">
    <p>© <?= date('Y') ?> SK Hair Salon</p>