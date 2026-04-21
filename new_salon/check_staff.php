<?php
require_once "config/db.php";

header('Content-Type: application/json');

$today = date('Y-m-d');

$result = [];

$query = $conn->query("
    SELECT users.name, attendance.time, attendance.id
    FROM attendance
    JOIN users ON users.id = attendance.user_id
    WHERE attendance.date='$today'
    AND attendance.end_time IS NULL
");

while($row = $query->fetch_assoc()){
    $result[] = [
        'id' => $row['id'], // 🔥 penting
        'name' => $row['name'],
        'time' => substr($row['time'],0,5)
    ];
}

echo json_encode($result);