<?php
session_start();
require_once "config/db.php";

$id = $_GET['id'];
$data = $conn->query("SELECT * FROM services WHERE id=$id")->fetch_assoc();

if(isset($_POST['update'])) {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $duration = $_POST['duration'];

    $conn->query("UPDATE services 
                  SET name='$name', price='$price', duration='$duration' 
                  WHERE id=$id");

    header("Location: admin_services.php");
    exit;
}
?>

<h2>Edit Layanan</h2>

<form method="POST">
    Nama: <input type="text" name="name" value="<?= $data['name'] ?>"><br><br>
    Harga: <input type="number" name="price" value="<?= $data['price'] ?>"><br><br>
    Durasi: <input type="number" name="duration" value="<?= $data['duration'] ?>"><br><br>

    <button name="update">Update</button>
</form>