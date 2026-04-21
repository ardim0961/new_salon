<?php
session_start();
require_once "config/db.php";

if(!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin') {
    header("Location: login.php");
    exit;
}

/* ======================
   SIMPAN LAYANAN STAFF
====================== */
if(isset($_POST['save_services'])){

    $user_id = (int)$_POST['user_id'];
    $services = $_POST['services'] ?? [];

    // hapus semua mapping lama
    $conn->query("DELETE FROM service_staff WHERE user_id=$user_id");

    // insert baru
    foreach($services as $service_id){
        $service_id = (int)$service_id;
        $conn->query("
            INSERT INTO service_staff (user_id, service_id)
            VALUES ($user_id, $service_id)
        ");
    }
}

/* ======================
   DATA STAFF
====================== */
$staff = $conn->query("SELECT * FROM users WHERE role='staff'");

/* ======================
   DATA SERVICES
====================== */
$services = $conn->query("SELECT * FROM services");
?>

<!DOCTYPE html>
<html>
<head>
<title>Kelola Staff</title>
<link rel="stylesheet" href="style.php">

<style>
.table-container {
    background: white;
    padding: 20px;
    border-radius: 12px;
}

.checkbox-group {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
}

.badge {
    background: #ff7a00;
    color: white;
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 12px;
    margin-right: 5px;
}

.btn-secondary {
    background: #333;
    color: white;
    padding: 8px 12px;
    border-radius: 6px;
    text-decoration: none;
}
</style>

</head>
<body>

<div class="main">

<h2>👨‍💼 Kelola Kemampuan Staff</h2>

<div class="table-container">

<table width="100%" cellpadding="10">

<tr>
<th>Nama</th>
<th>Email</th>
<th>Kemampuan Saat Ini</th>
<th>Atur Layanan</th>
<th>Aksi</th>
</tr>

<?php while($row = $staff->fetch_assoc()): ?>

<?php
// ambil layanan yg dimiliki staff
$owned = [];
$q = $conn->query("
    SELECT services.name 
    FROM service_staff
    JOIN services ON services.id = service_staff.service_id
    WHERE service_staff.user_id=".$row['id']
);

while($o = $q->fetch_assoc()){
    $owned[] = $o['name'];
}

// ambil ID yg dimiliki (untuk checkbox)
$owned_id = [];
$q2 = $conn->query("
    SELECT service_id 
    FROM service_staff 
    WHERE user_id=".$row['id']
);

while($o2 = $q2->fetch_assoc()){
    $owned_id[] = $o2['service_id'];
}
?>

<tr>

<td><b><?= $row['name'] ?></b></td>
<td><?= $row['email'] ?></td>

<td>
    <?php if($owned): ?>
        <?php foreach($owned as $o): ?>
            <span class="badge"><?= $o ?></span>
        <?php endforeach; ?>
    <?php else: ?>
        -
    <?php endif; ?>
</td>

<td>

<form method="POST">

<input type="hidden" name="user_id" value="<?= $row['id'] ?>">

<div class="checkbox-group">

<?php
$services->data_seek(0);
while($s = $services->fetch_assoc()):
?>

<label>
    <input type="checkbox" name="services[]" value="<?= $s['id'] ?>"
    <?= in_array($s['id'], $owned_id) ? 'checked' : '' ?>>
    <?= $s['name'] ?>
</label>

<?php endwhile; ?>

</div>

<br>

<button name="save_services" class="button">Simpan</button>

</form>

</td>

<td align="center">
    <a href="staff_detail.php?id=<?= $row['id'] ?>" class="btn-secondary">
        Riwayat
    </a>
</td>

</tr>

<?php endwhile; ?>

</table>

</div>

</div>

</body>
</html>