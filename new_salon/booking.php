<?php
require_once "config/db.php";

// ambil service_id dari index
$service_id = $_GET['service_id'] ?? 0;

// ambil data service dari database
$service = $conn->query("SELECT * FROM services WHERE id=$service_id")->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Booking</title>
</head>
<body>

<h2>Halaman Booking</h2>

<?php if($service): ?>
    <h3><?= $service['name'] ?></h3>
    <p>Harga: Rp<?= $service['price'] ?></p>
<?php else: ?>
    <p>Service tidak ditemukan</p>
<?php endif; ?>

<form action="booking_process.php" method="POST">

    <input type="hidden" name="service_id" value="<?= $service_id ?>">

    <label>Tanggal:</label><br>
    <input type="date" name="tanggal" required><br><br>

    <label>Jam:</label><br>
    <input type="time" name="jam" required><br><br>

    <button type="submit">Booking Sekarang</button>

</form>

</body>
</html>