<?php
// 🔥 MATIKAN WARNING MIDTRANS (BUG SDK)
error_reporting(E_ALL & ~E_WARNING);

session_start();
require_once "config/db.php";
require_once "midtrans/config.php";

// =========================
// VALIDASI LOGIN
// =========================
if(!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$user = $_SESSION['user'];

// =========================
// AMBIL DATA
// =========================
$payment_id = (int) $_GET['id'];
$amount = (int) $_GET['amount'];

// =========================
// VALIDASI
// =========================
if($payment_id <= 0){
    die("ID tidak valid");
}

if($amount < 1000){
    die("Minimum pembayaran Rp1000");
}

// =========================
// ORDER ID UNIK
// =========================
$order_id = 'PROD-' . $payment_id . '-' . time();

// =========================
// PARAM MIDTRANS (WAJIB LENGKAP)
// =========================
$params = [
    'transaction_details' => [
        'order_id' => $order_id,
        'gross_amount' => $amount,
    ],
    'item_details' => [
        [
            'id' => $payment_id,
            'price' => $amount,
            'quantity' => 1,
            'name' => 'Pembelian Produk'
        ]
    ],
    'customer_details' => [
        'first_name' => $user['name'],
        'email' => $user['email'] ?? 'user@email.com'
    ]
];

// =========================
// GENERATE TOKEN
// =========================
try {
    $snapToken = \Midtrans\Snap::getSnapToken($params);
} catch (Exception $e) {
    die("Error Midtrans: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Pembayaran Produk</title>
    <link rel="stylesheet" href="style.php">
</head>
<body>

<!-- HEADER -->
<div class="header">
    <?= $user['name'] ?>
    | <a href="logout.php" style="color:white;">Logout</a>
</div>

<!-- MAIN -->
<div class="main" style="text-align:center;">

<h2>Pembayaran Produk</h2>

<p>Total: <b>Rp<?= number_format($amount) ?></b></p>

<br>

<button onclick="bayar('<?= $snapToken ?>')" class="button">
    Bayar Sekarang
</button>

</div>

<!-- FOOTER -->
<div class="footer">
    <p>SK Hair Salon</p>
</div>

<!-- MIDTRANS SNAP -->
<script src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="Mid-client-H04U4gGu40JR-V7-"></script>

<script>
function bayar(token){
    snap.pay(token, {

        onSuccess: function(result){
            alert("Pembayaran berhasil");
            window.location.href = "my_orders.php";
        },

        onPending: function(result){
            alert("Menunggu pembayaran");
            window.location.href = "my_orders.php";
        },

        onError: function(result){
            alert("Pembayaran gagal");
        }

    });
}
</script>

</body>
</html>