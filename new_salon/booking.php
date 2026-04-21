<?php
session_start();
require_once "config/db.php";

date_default_timezone_set('Asia/Jakarta');

if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user']['id'];
$service_id = $_GET['service_id'];

$service = $conn->query("SELECT * FROM services WHERE id=$service_id")->fetch_assoc();

$date = $_POST['date'] ?? date('Y-m-d');
$selected_time = $_POST['time'] ?? '';

$error = "";

/* ======================
   GENERATE SLOT
====================== */
function generateSlots($start="09:00", $end="18:00", $interval=30){
    $slots = [];
    $current = strtotime($start);
    $endTime = strtotime($end);

    while($current < $endTime){
        $slots[] = date("H:i", $current);
        $current = strtotime("+$interval minutes", $current);
    }
    return $slots;
}

$slots = generateSlots();

/* ======================
   HANDLE BOOKING
====================== */
if(isset($_POST['booking'])){

    if(empty($_POST['time'])){
        $error = "Pilih jam terlebih dahulu";
    } else {

        $time = $_POST['time'];

        // cari staff yang AVAILABLE (tidak bentrok)
        $staff = $conn->query("
            SELECT ss.user_id
            FROM service_staff ss
            JOIN attendance a ON a.user_id = ss.user_id
            WHERE ss.service_id=$service_id
            AND a.end_time IS NULL
            AND ss.user_id NOT IN (

                SELECT b.staff_id
                FROM bookings b
                JOIN services s ON s.id = b.service_id
                WHERE b.date = '$date'
                AND (
                    TIME(b.time) < ADDTIME('$time:00', SEC_TO_TIME($service[duration]*60))
                    AND ADDTIME(b.time, SEC_TO_TIME(s.duration * 60)) > '$time:00'
                )

            )
            LIMIT 1
        ")->fetch_assoc();

        if(!$staff){
            $error = "Semua staff sudah penuh di jam ini";
        } else {

            $staff_id = $staff['user_id'];

            $conn->query("
                INSERT INTO bookings (user_id, service_id, staff_id, date, time)
                VALUES ($user_id, $service_id, $staff_id, '$date', '$time')
            ");

            header("Location: booking_success.php");
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Booking</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

<div class="max-w-xl mx-auto mt-12 bg-white p-6 rounded-xl shadow">

<h2 class="text-xl font-bold mb-4">Booking Layanan</h2>

<div class="mb-4">
    <p class="font-semibold"><?= $service['name'] ?></p>
    <p class="text-orange-500 font-bold">
        Rp<?= number_format($service['price'],0,',','.') ?>
    </p>
    <p class="text-sm text-gray-500">
        Durasi: <?= $service['duration'] ?> menit
    </p>
</div>

<?php if($error): ?>
<div class="bg-red-100 text-red-600 p-3 rounded mb-3">
    <?= $error ?>
</div>
<?php endif; ?>

<form method="POST">

<label class="text-sm">Tanggal</label>
<input type="date" name="date" value="<?= $date ?>" 
    class="w-full border p-2 rounded mb-4"
    min="<?= date('Y-m-d') ?>"
    onchange="this.form.submit()">

<label class="text-sm">Pilih Jam</label>

<div class="grid grid-cols-3 gap-2 mt-2">

<?php
$current_time = time(); // waktu real sekarang

// total staff aktif
$total_staff = $conn->query("
    SELECT COUNT(*) as total
    FROM service_staff ss
    JOIN attendance a ON a.user_id = ss.user_id
    WHERE ss.service_id = $service_id
    AND a.end_time IS NULL
")->fetch_assoc()['total'];

foreach($slots as $slot):

    $disabled = false;

    // 🔥 REALTIME CHECK (FIX UTAMA)
    if($date == date('Y-m-d')){
        $slot_time = strtotime($date . ' ' . $slot);
        if($slot_time <= $current_time){
            $disabled = true;
        }
    }

    // jumlah booking di slot
    $booked = $conn->query("
        SELECT COUNT(*) as total
        FROM bookings
        WHERE date='$date'
        AND TIME(time) = '$slot:00'
    ")->fetch_assoc()['total'];

    $remaining = $total_staff - $booked;

    if($remaining <= 0){
        $disabled = true;
    }
?>

<button type="button"
    onclick="selectTime('<?= $slot ?>', this)"
    class="slot-btn p-2 rounded border text-center
    <?= $disabled ? 'bg-gray-200 text-gray-400 cursor-not-allowed' : 'bg-white hover:bg-orange-500 hover:text-white' ?>"
    <?= $disabled ? 'disabled' : '' ?>>

    <div><?= $slot ?></div>

    <?php if(!$disabled): ?>
        <div class="text-xs text-green-600">
            <?= $remaining ?> tersedia
        </div>
    <?php else: ?>
        <div class="text-xs text-red-500">
            tidak tersedia
        </div>
    <?php endif; ?>

</button>

<?php endforeach; ?>

</div>

<input type="hidden" name="time" id="selected_time" value="<?= $selected_time ?>">

<button name="booking"
    class="w-full mt-5 bg-orange-500 text-white py-2 rounded-lg">
    Konfirmasi Booking
</button>

</form>

</div>

<script>
function selectTime(time, el){

    document.getElementById('selected_time').value = time;

    document.querySelectorAll('.slot-btn').forEach(btn=>{
        btn.classList.remove('bg-orange-500','text-white');
    });

    el.classList.add('bg-orange-500','text-white');
}
</script>

</body>
</html>