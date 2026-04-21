<?php
session_start();
require_once "config/db.php";

if(!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'customer') {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user']['id'];

/* ======================
   CANCEL ORDER
====================== */
if(isset($_GET['cancel'])) {
    $id = $_GET['cancel'];

    // hanya boleh cancel jika masih pending
    $conn->query("UPDATE orders 
                  SET status='cancelled' 
                  WHERE id=$id AND user_id=$user_id AND status='pending'");

    header("Location: my_orders.php");
    exit;
}

/* ======================
   AMBIL DATA
====================== */
$orders = $conn->query("
    SELECT orders.*, products.name 
    FROM orders 
    JOIN products ON orders.product_id = products.id
    WHERE user_id = $user_id
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Riwayat Pembelian</title>
    <link rel="stylesheet" href="style.php">
</head>
<body>

<div class="header">
    Riwayat Pembelian
</div>

<div class="main">

<h2>Riwayat Pembelian</h2>

<?php if($orders->num_rows == 0): ?>
    <p>Belum ada pembelian</p>
<?php endif; ?>

<?php while($row = $orders->fetch_assoc()): ?>
<div class="card">

    <h3><?= $row['name'] ?></h3>
    <p>Qty: <?= $row['qty'] ?></p>
    <p>Total: Rp<?= $row['total'] ?></p>
    <p>Status: <b><?= $row['status'] ?></b></p>

    <?php if($row['status'] == 'pending'): ?>
        <a href="?cancel=<?= $row['id'] ?>" 
           class="button"
           onclick="return confirm('Batalkan pembelian?')">
           Batalkan
        </a>
    <?php endif; ?>

</div>
<?php endwhile; ?>

</div>

<div class="footer">
    Customer Panel
</div>

</body>
</html>