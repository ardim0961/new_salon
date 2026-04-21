<?php
session_start();
require_once "config/db.php";

// =========================
// VALIDASI LOGIN
// =========================
if(!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$user_id = (int) $_SESSION['user']['id'];
$product_id = (int) $_POST['product_id'];
$qty = (int) $_POST['qty'];

// =========================
// VALIDASI INPUT
// =========================
if($product_id <= 0){
    die("Produk tidak valid");
}

if($qty <= 0){
    die("Jumlah tidak valid");
}

// =========================
// AMBIL DATA PRODUK
// =========================
$result = $conn->query("SELECT * FROM products WHERE id=$product_id");

if(!$result || $result->num_rows == 0){
    die("Produk tidak ditemukan");
}

$product = $result->fetch_assoc();

// =========================
// CEK STOK
// =========================
if($product['stock'] < $qty){
    die("Stok tidak cukup");
}

// =========================
// HITUNG TOTAL
// =========================
$total = (int)$product['price'] * $qty;

// 🔥 WAJIB: MINIMUM MIDTRANS
if($total < 1000){
    die("Minimum pembayaran Rp1000");
}

// =========================
// SIMPAN KE ORDERS
// =========================
$conn->query("INSERT INTO orders (user_id, product_id, qty, total, status)
              VALUES ($user_id, $product_id, $qty, $total, 'pending')");

$order_id = $conn->insert_id;

// =========================
// REDIRECT KE PAYMENT
// =========================
header("Location: product_payment.php?id=$order_id&amount=$total");
exit;