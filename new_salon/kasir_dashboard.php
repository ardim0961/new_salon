<?php
session_start();
require_once "config/db.php";

if(!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'kasir') {
    header("Location: login.php");
    exit;
}

$user = $_SESSION['user'];
$theme = $conn->query("SELECT * FROM theme LIMIT 1")->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Kasir</title>
    <link rel="stylesheet" href="style.php">
</head>
<body>

<div class="header">
    <div style="display:flex; justify-content:space-between;">
        <strong><?= $user['name'] ?> (<?= $user['role'] ?>)</strong>
        <a href="logout.php" style="background:#000;color:#fff;padding:6px 12px;">Logout</a>
    </div>
</div>

<div class="main" style="text-align:center;">

<h2><?= $theme['dashboard_kasir'] ?></h2>

<div class="card">
    <a href="#" class="button">Transaksi Produk</a>
</div>

<div class="card">
    <a href="#" class="button">Validasi Pembayaran</a>
</div>

</div>

<div class="footer">
    <p>Kasir Panel</p>
</div>

</body>
</html>