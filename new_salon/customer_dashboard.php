<?php
session_start();

if(!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'customer') {
    header("Location: login.php");
    exit;
}

$user = $_SESSION['user'];
?>

<!DOCTYPE html>
<html>
<head>
<title>Dashboard Customer</title>
<link rel="stylesheet" href="style.php">
</head>
<body>

<div class="header">
    <div>Customer Panel</div>
    <div><?= $user['name'] ?> | <a href="logout.php" style="color:white;">Logout</a></div>
</div>

<div class="main">

<h2>Dashboard Customer</h2>

<div class="grid">

<div class="card">
<h3>Layanan</h3>
<p>Booking layanan salon</p>
<a href="services.php" class="button">Booking</a>
</div>

<div class="card">
<h3>Produk</h3>
<p>Beli produk salon</p>
<a href="products.php" class="button">Lihat Produk</a>
</div>

<div class="card">
<h3>Booking Saya</h3>
<p>Riwayat layanan</p>
<a href="my_booking.php" class="button">Lihat</a>
</div>

<div class="card">
<h3>Pembelian</h3>
<p>Riwayat produk</p>
<a href="my_orders.php" class="button">Lihat</a>
</div>

</div>

</div>

<div class="footer">
    SK Hair Salon
</div>

</body>
</html>