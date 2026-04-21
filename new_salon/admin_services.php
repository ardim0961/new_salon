<?php
session_start();
require_once "config/db.php";

if(!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin') {
    header("Location: login.php");
    exit;
}

$user = $_SESSION['user'];

if(isset($_POST['tambah'])) {
    $image = $_FILES['image']['name'];
    move_uploaded_file($_FILES['image']['tmp_name'], "uploads/".$image);

    $conn->query("INSERT INTO services (name,price,duration,image)
                  VALUES ('".$_POST['name']."','".$_POST['price']."','".$_POST['duration']."','$image')");
}

if(isset($_GET['hapus'])) {
    $conn->query("DELETE FROM services WHERE id=".$_GET['hapus']);
}

$services = $conn->query("SELECT * FROM services");
?>

<!DOCTYPE html>
<html>
<head>
<title>Admin Services</title>
<link rel="stylesheet" href="style.php">
</head>
<body>

<div class="header">
<?= $user['name'] ?> | <a href="logout.php" style="color:white;">Logout</a>
</div>

<div class="main">

<h2>Kelola Layanan</h2>

<form method="POST" enctype="multipart/form-data">
Nama: <input type="text" name="name"><br><br>
Harga: <input type="number" name="price"><br><br>
Durasi: <input type="number" name="duration"><br><br>
Gambar: <input type="file" name="image"><br><br>

<button name="tambah">Tambah</button>
</form>

<hr>

<?php while($row = $services->fetch_assoc()): ?>
<div class="card">
<h3><?= $row['name'] ?></h3>

<?php if($row['image']): ?>
<img src="uploads/<?= $row['image'] ?>" width="100"><br><br>
<?php endif; ?>

<p>Rp<?= $row['price'] ?></p>

<a href="?hapus=<?= $row['id'] ?>" class="button">Hapus</a>
<a href="edit_service.php?id=<?= $row['id'] ?>" class="button">Edit</a>
</div>
<?php endwhile; ?>

</div>

<div class="footer">Admin</div>

</body>
</html>