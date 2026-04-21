<?php
date_default_timezone_set('Asia/Jakarta');

session_start();
require_once "config/db.php";

if(!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'staff') {
    header("Location: login.php");
    exit;
}

$user = $_SESSION['user'];
$theme = $conn->query("SELECT * FROM theme LIMIT 1")->fetch_assoc();

$today = date('Y-m-d');
$message = "";

/* ======================
   CEK SESSION AKTIF
====================== */
$active = $conn->query("
    SELECT * FROM attendance 
    WHERE user_id=".$user['id']." 
    AND date='$today'
    AND end_time IS NULL
    ORDER BY id DESC
    LIMIT 1
")->fetch_assoc();

/* ======================
   ACTION
====================== */
if(isset($_POST['status'])){

    $status = $_POST['status'];
    $id = (int)$user['id'];
    $now = date('H:i:s');

    if($status == 'hadir'){

        if(!$active){
            $conn->query("
                INSERT INTO attendance (user_id, date, status, time)
                VALUES ($id, '$today', 'hadir', '$now')
            ");
            $message = "✅ Mulai kerja (jam: $now)";
        } else {
            $message = "⚠️ Sudah sedang bekerja";
        }

    } else {

        if($active){
            $conn->query("
                UPDATE attendance 
                SET status='tidak_kerja', end_time='$now'
                WHERE id=".$active['id']."
            ");
            $message = "⛔ Selesai kerja (jam: $now)";
        } else {
            $message = "⚠️ Belum mulai kerja";
        }

    }

    // refresh
    $active = $conn->query("
        SELECT * FROM attendance 
        WHERE user_id=".$user['id']." 
        AND date='$today'
        AND end_time IS NULL
        ORDER BY id DESC
        LIMIT 1
    ")->fetch_assoc();
}

/* ======================
   STATUS
====================== */
$current_status = $active ? 'Online' : 'Offline';
$current_time   = $active ? substr($active['time'],0,5) : '--:--';
?>

<!DOCTYPE html>
<html>
<head>
<title>Staff Dashboard</title>
<link rel="stylesheet" href="style.php">

<style>
.status-online { color: #00ff88; font-weight: bold; }
.status-offline { color: #ff4d4d; font-weight: bold; }

.alert {
    margin-top: 15px;
    padding: 12px;
    background: #222;
    color: #fff;
    border-radius: 8px;
}

/* BUTTON */
.btn {
    padding: 8px 14px;
    border-radius: 6px;
    border: none;
    cursor: pointer;
    font-weight: bold;
}

.btn-online { background: green; color: white; }
.btn-offline { background: red; color: white; }
.btn-disabled { background: #777; cursor: not-allowed; }

/* HEADER FLEX */
.header-flex {
    display: flex;
    justify-content: space-between;
    align-items: center;
}
</style>

</head>
<body>

<!-- HEADER -->
<div class="header">
    <div class="header-flex">

        <!-- LEFT -->
        <div><?= $user['name'] ?> (Staff)</div>

        <!-- RIGHT -->
        <div style="display:flex; gap:15px; align-items:center;">

            <!-- STATUS -->
            <div>
                Status:
                <span class="<?= $active ? 'status-online' : 'status-offline' ?>">
                    <?= $current_status ?>
                </span>
            </div>

            <!-- BUTTON -->
            <form method="POST" style="display:flex; gap:10px; margin:0;">

            <?php if(!$active): ?>

                <button name="status" value="hadir" class="btn btn-online">
                    🟢 Online
                </button>

                <button class="btn btn-offline btn-disabled" disabled>
                    🔴 Offline
                </button>

            <?php else: ?>

                <button class="btn btn-online btn-disabled" disabled>
                    🟢 Online
                </button>

                <button name="status" value="tidak_kerja" class="btn btn-offline">
                    🔴 Offline
                </button>

            <?php endif; ?>

            </form>

            <!-- LOGOUT -->
            <a href="logout.php" style="color:white;">Logout</a>

        </div>

    </div>
</div>

<!-- MAIN -->
<div class="main" style="text-align:center;">

<h2><?= $theme['dashboard_staff'] ?></h2>

<div class="container">

<h3>Informasi Kerja</h3>

<?php if($message): ?>
<div class="alert"><?= $message ?></div>
<?php endif; ?>

<br>

<p>Mulai Kerja: <b><?= $current_time ?></b></p>
<p>Jam Sekarang: <b id="liveClock"></b></p>

</div>

</div>

<div class="footer">Staff Panel</div>

<script>
function updateClock(){
    const now = new Date();

    let h = String(now.getHours()).padStart(2,'0');
    let m = String(now.getMinutes()).padStart(2,'0');
    let s = String(now.getSeconds()).padStart(2,'0');

    document.getElementById("liveClock").innerText = h + ":" + m + ":" + s;
}
setInterval(updateClock, 1000);
updateClock();
</script>

</body>
</html>