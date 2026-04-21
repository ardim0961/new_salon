<?php
require_once "midtrans/config.php";

// contoh data
$booking_id = 1;
$amount = 50000;

$params = [
    'transaction_details' => [
        'order_id' => $booking_id,
        'gross_amount' => $amount,
    ]
];

$snapToken = \Midtrans\Snap::getSnapToken($params);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Booking Berhasil</title>
</head>
<body>

<h2>Booking Berhasil</h2>

<button onclick="bayar('<?= $snapToken ?>')">Bayar Sekarang</button>

<script src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="Mid-client-H04U4gGu40JR-V7-"></script>

<script>
function bayar(token) {
    snap.pay(token);
}
</script>

</body>
</html>