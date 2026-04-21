<?php
session_start();
require_once "config/db.php";

if(isset($_SESSION['user'])) {
    $id = $_SESSION['user']['id'];
    $conn->query("UPDATE users SET status='offline' WHERE id=$id");
}

session_destroy();
header("Location: login.php");
exit;