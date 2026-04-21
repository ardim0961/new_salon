<?php
session_start();
require_once "config/db.php";

if(!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin') {
    header("Location: login.php");
    exit;
}

$user = $_SESSION['user'];
$today = date('Y-m-d');

/* ======================
   THEME
====================== */
$theme = $conn->query("SELECT * FROM theme LIMIT 1")->fetch_assoc();

/* ======================
   STAFF ONLINE (FIX)
====================== */
$staff_today = $conn->query("
    SELECT users.id, users.name, users.role_job, attendance.time 
    FROM attendance
    JOIN users ON users.id = attendance.user_id
    WHERE attendance.date='$today'
    AND attendance.end_time IS NULL
");
?>

<!DOCTYPE html>
<html>
<head>
<title>Admin Dashboard</title>
<link rel="stylesheet" href="style.php">

<style>
.toast {
    position: fixed;
    top: 20px;
    left: 50%;
    transform: translate(-50%, -20px);
    background: #111;
    color: white;
    padding: 15px 25px;
    border-radius: 10px;
    opacity: 0;
    transition: 0.3s;
    z-index: 999;
}
.toast.show {
    opacity: 1;
    transform: translate(-50%, 0);
}

/* STATUS BADGE */
.badge {
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 12px;
    color: white;
}

.badge-online {
    background: green;
}

.badge-job {
    background: #ff7a00;
}
</style>

</head>
<body>

<div class="wrapper">

<!-- SIDEBAR -->
<div class="sidebar">
    <h3>ADMIN</h3>
    <a href="admin_dashboard.php">Dashboard</a>
    <a href="admin_services.php">Layanan</a>
    <a href="admin_products.php">Produk</a>
    <a href="admin_staff.php">Staff</a>
</div>

<!-- CONTENT -->
<div class="content">

<div class="topbar">
    <div><?= $theme['dashboard_admin'] ?></div>
    <div><?= $user['name'] ?> | <a href="logout.php" style="color:white;">Logout</a></div>
</div>

<div class="main">

<!-- STAFF ONLINE -->
<div class="card">
<h3>🟢 Staff Sedang Bekerja</h3>

<ul id="staffList">

<?php while($row = $staff_today->fetch_assoc()): ?>
<li>
    <b><?= $row['name'] ?></b>

    <span class="badge badge-job">
        <?= $row['role_job'] ?? 'Belum diatur' ?>
    </span>

    <span class="badge badge-online">
        Online
    </span>

    (<?= substr($row['time'],0,5) ?>)
</li>
<?php endwhile; ?>

</ul>

</div>

<!-- MENU -->
<div class="grid">

<div class="card">
<h3>Layanan</h3>
<a href="admin_services.php" class="button">Kelola</a>
</div>

<div class="card">
<h3>Staff</h3>
<a href="admin_staff.php" class="button">Monitoring</a>
</div>

<div class="card">
<h3>Produk</h3>
<a href="admin_products.php" class="button">Kelola</a>
</div>

</div>

</div>
</div>
</div>

<!-- TOAST -->
<div id="toast" class="toast"></div>

<script>
let lastData = [];

function showToast(msg){
    let t = document.getElementById("toast");
    t.innerText = msg;
    t.classList.add("show");
    setTimeout(()=>t.classList.remove("show"),3000);
}

/* ======================
   REALTIME
====================== */
setInterval(()=>{

    fetch("check_staff.php")
    .then(res=>res.json())
    .then(data=>{

        let list = document.getElementById("staffList");
        list.innerHTML = "";

        data.forEach(item=>{
            list.innerHTML += `
                <li>
                    <b>${item.name}</b>
                    <span class="badge badge-job">${item.role_job ?? '-'}</span>
                    <span class="badge badge-online">Online</span>
                    (${item.time})
                </li>
            `;
        });

        let newStaff = data.filter(x => 
            !lastData.some(old => old.id === x.id)
        );

        newStaff.forEach(item=>{
            showToast(`🟢 ${item.name} (${item.role_job}) mulai kerja`);
        });

        lastData = data;

    });

},3000);
</script>

</body>
</html>