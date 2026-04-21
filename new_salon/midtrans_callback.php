<?php
require_once "config/db.php";
require_once "midtrans/config.php";

$json = file_get_contents("php://input");
$data = json_decode($json);

$order_id = $data->order_id;
$status = $data->transaction_status;

// ambil ID asli (karena kita pakai ORDER-xxx)
$parts = explode('-', $order_id);
$order_real_id = $parts[1];

// mapping status
if($status == 'settlement' || $status == 'capture'){
    $conn->query("UPDATE orders SET status='success' WHERE id=$order_real_id");

    // 🔥 kurangi stok
    $order = $conn->query("SELECT * FROM orders WHERE id=$order_real_id")->fetch_assoc();
    $conn->query("UPDATE products SET stock = stock - ".$order['qty']." WHERE id=".$order['product_id']);
}
else if($status == 'pending'){
    $conn->query("UPDATE orders SET status='pending' WHERE id=$order_real_id");
}
else if($status == 'cancel' || $status == 'expire'){
    $conn->query("UPDATE orders SET status='failed' WHERE id=$order_real_id");
}