<?php
session_start();
require_once "config/db.php";

if(!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin') {
    header("Location: login.php");
    exit;
}

$user = $_SESSION['user'];

/* =========================
   EDIT DATA
========================= */
$edit_data = null;

if(isset($_GET['edit'])) {
    $id = (int) $_GET['edit'];
    $edit_data = $conn->query("SELECT * FROM products WHERE id=$id")->fetch_assoc();
}

/* =========================
   TAMBAH PRODUK
========================= */
if(isset($_POST['tambah'])) {

    $name = $_POST['name'];
    $price = (int) $_POST['price'];
    $stock = (int) $_POST['stock'];

    $image = $_FILES['image']['name'];

    if($image){
        move_uploaded_file($_FILES['image']['tmp_name'], "uploads/".$image);
    }

    $conn->query("INSERT INTO products (name,price,stock,image)
                  VALUES ('$name','$price','$stock','$image')");

    header("Location: admin_products.php");
    exit;
}

/* =========================
   UPDATE PRODUK
========================= */
if(isset($_POST['update'])) {

    $id = (int) $_POST['id'];
    $name = $_POST['name'];
    $price = (int) $_POST['price'];
    $stock = (int) $_POST['stock'];

    if($_FILES['image']['name']) {
        $image = $_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'], "uploads/".$image);

        $conn->query("UPDATE products SET 
            name='$name',
            price='$price',
            stock='$stock',
            image='$image'
            WHERE id=$id");
    } else {
        $conn->query("UPDATE products SET 
            name='$name',
            price='$price',
            stock='$stock'
            WHERE id=$id");
    }

    header("Location: admin_products.php");
    exit;
}

/* =========================
   TAMBAH STOK CEPAT
========================= */
if(isset($_POST['add_stock'])) {
    $id = (int) $_POST['id'];
    $stock = (int) $_POST['stock'];

    $conn->query("UPDATE products 
                  SET stock = stock + $stock 
                  WHERE id=$id");

    header("Location: admin_products.php");
    exit;
}

/* =========================
   HAPUS
========================= */
if(isset($_GET['hapus'])) {
    $id = (int) $_GET['hapus'];
    $conn->query("DELETE FROM products WHERE id=$id");

    header("Location: admin_products.php");
    exit;
}

$products = $conn->query("SELECT * FROM products");
?>

<!DOCTYPE html>
<html>
<head>
<title>Admin Produk</title>
<link rel="stylesheet" href="style.php">
</head>
<body>

<div class="header">
    <div>Admin Produk</div>
    <div><?= $user['name'] ?> | <a href="logout.php" style="color:white;">Logout</a></div>
</div>

<div class="main">

<h2>Kelola Produk</h2>

<!-- ================= FORM ================= -->
<div class="card">

<h3><?= $edit_data ? "Edit Produk" : "Tambah Produk" ?></h3>

<form method="POST" enctype="multipart/form-data">

<input type="hidden" name="id" value="<?= $edit_data['id'] ?? '' ?>">

<input type="text" name="name" placeholder="Nama Produk"
value="<?= $edit_data['name'] ?? '' ?>" required>

<input type="number" name="price" placeholder="Harga"
value="<?= $edit_data['price'] ?? '' ?>" required>

<input type="number" name="stock" placeholder="Stok"
value="<?= $edit_data['stock'] ?? '' ?>" required>

<input type="file" name="image">

<?php if($edit_data && $edit_data['image']): ?>
<br><br>
<img src="uploads/<?= $edit_data['image'] ?>" width="120">
<?php endif; ?>

<br><br>

<?php if($edit_data): ?>
<button name="update" class="button">Update</button>
<a href="admin_products.php" class="button">Batal</a>
<?php else: ?>
<button name="tambah" class="button">Tambah</button>
<?php endif; ?>

</form>

</div>

<!-- ================= LIST ================= -->
<h3>Daftar Produk</h3>

<div class="grid">

<?php while($row = $products->fetch_assoc()): ?>
<div class="card">

<?php if($row['image']): ?>
<img src="uploads/<?= $row['image'] ?>" style="width:100%; border-radius:10px;">
<?php endif; ?>

<h3><?= $row['name'] ?></h3>

<p>Rp<?= number_format($row['price']) ?></p>
<p>Stok: <b><?= $row['stock'] ?></b></p>

<hr>

<!-- TAMBAH STOK -->
<form method="POST">
    <input type="hidden" name="id" value="<?= $row['id'] ?>">
    <input type="number" name="stock" placeholder="Tambah stok" required>
    <button name="add_stock" class="button">+ Stok</button>
</form>

<br>

<a href="?edit=<?= $row['id'] ?>" class="button">Edit</a>
<a href="?hapus=<?= $row['id'] ?>" 
   class="button"
   onclick="return confirm('Hapus produk?')">Hapus</a>

</div>
<?php endwhile; ?>

</div>

</div>

<div class="footer">Admin Panel</div>

</body>
</html>