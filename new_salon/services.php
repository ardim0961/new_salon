<?php
session_start();
require_once "config/db.php";

$services = $conn->query("SELECT * FROM services");
?>

<!DOCTYPE html>
<html>
<head>
<title>Layanan</title>
<link rel="stylesheet" href="style.php">
</head>
<body>

<div class="header">
    <div>Layanan</div>
    <div><a href="customer_dashboard.php" style="color:white;">Kembali</a></div>
</div>

<div class="main">

<h2>Daftar Layanan</h2>

<div class="grid">

<?php while($row = $services->fetch_assoc()): ?>
<div class="card">

<h3><?= $row['name'] ?></h3>
<p>Rp<?= number_format($row['price']) ?></p>

<?php if($row['image']): ?>
<img src="uploads/<?= $row['image'] ?>" width="100%">
<?php endif; ?>

<br><br>
<a href="booking.php?service_id=<?= $row['id'] ?>" class="button">Booking</a>

</div>
<?php endwhile; ?>

</div>

</div>

<div class="footer">Salon</div>

</body>
</html>