<?php
session_start();
require_once "config/db.php";

if(!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin') {
    header("Location: login.php");
    exit;
}

$id = (int)$_GET['id'];

/* ======================
   DATA STAFF
====================== */
$user = $conn->query("SELECT * FROM users WHERE id=$id")->fetch_assoc();

/* ======================
   RIWAYAT
====================== */
$data = $conn->query("
    SELECT * FROM attendance 
    WHERE user_id=$id 
    ORDER BY date DESC, id DESC
");

$total = 0;
?>

<!DOCTYPE html>
<html>
<head>
<title>Detail Staff</title>
<link rel="stylesheet" href="style.php">
</head>
<body>

<div class="header">
    <div><?= $user['name'] ?> (<?= $user['role_job'] ?? '-' ?>)</div>
    <div><a href="admin_staff.php" style="color:white;">← Kembali</a></div>
</div>

<div class="main">

<h2>Riwayat Kerja</h2>

<table width="100%" cellpadding="10" border="1" style="border-collapse: collapse;">
<tr>
    <th>Tanggal</th>
    <th>Masuk</th>
    <th>Keluar</th>
    <th>Durasi</th>
</tr>

<?php while($row = $data->fetch_assoc()): 

    $start = $row['time'];
    $end   = $row['end_time'];
    $durasi = "-";

    if($start && $end){
        $startSec = strtotime($start);
        $endSec   = strtotime($end);

        $diff = $endSec - $startSec;

        $total += $diff;

        $jam = floor($diff / 3600);
        $menit = floor(($diff % 3600) / 60);

        $durasi = $jam . "j " . $menit . "m";
    }

?>

<tr>
    <td><?= $row['date'] ?></td>
    <td><?= $start ? substr($start,0,5) : '-' ?></td>
    <td><?= $end ? substr($end,0,5) : '-' ?></td>
    <td><?= $durasi ?></td>
</tr>

<?php endwhile; ?>

</table>

<!-- TOTAL JAM (DI LUAR TABLE) -->
<h3 style="margin-top:20px;">
    Total Jam Kerja: <?= round($total / 3600, 2) ?> jam
</h3>

</div>

<div class="footer">Admin Panel</div>

</body>
</html>