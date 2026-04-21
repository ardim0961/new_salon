<?php
session_start();
require_once "config/db.php";

if(!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'customer') {
    header("Location: login.php");
    exit;
}

$user = $_SESSION['user'];
$products = $conn->query("SELECT * FROM products");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Produk</title>
    <link rel="stylesheet" href="style.php">
</head>
<body>

<!-- HEADER -->
<div class="header">
    <?= $user['name'] ?> (<?= $user['role'] ?>)
    | <a href="logout.php" style="color:white;">Logout</a>
</div>

<!-- MAIN -->
<div class="main">

<h2>Daftar Produk</h2>

<?php while($row = $products->fetch_assoc()): ?>
    <div class="card">
        
        <h3><?= $row['name'] ?></h3>

        <?php if($row['image']): ?>
            <img src="uploads/<?= $row['image'] ?>" width="120"><br><br>
        <?php endif; ?>

        <p>Harga: Rp<?= $row['price'] ?></p>
        <p>Stok: <?= $row['stock'] ?></p>

        <form action="buy_process.php" method="POST">
            <input type="hidden" name="product_id" value="<?= $row['id'] ?>">
            <input type="number" name="qty" value="1" min="1"><br><br>
            <button type="submit">Beli</button>
        </form>

    </div>
<?php endwhile; ?>

</div>

<!-- FOOTER -->
<div class="footer">
    <p>SK Hair Salon</p>
</div>

</body>
</html>