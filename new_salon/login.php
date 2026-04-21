<?php
session_start();
require_once "config/db.php";

if(isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $result = $conn->query("SELECT * FROM users WHERE email='$email' AND password='$password'");
    $user = $result->fetch_assoc();

    if($user) {
    $_SESSION['user'] = $user;
    $conn->query("UPDATE users SET status='online' WHERE id=".$user['id']);

    if($user['role'] == 'admin') {
        header("Location: admin_dashboard.php");
    } elseif($user['role'] == 'kasir') {
        header("Location: kasir_dashboard.php");
    } elseif($user['role'] == 'staff') {
        header("Location: staff_dashboard.php");
    } else {
        header("Location: customer_dashboard.php");
    }

    } else {
        echo "Login gagal";
    }
}
?>

<h2>Login</h2>

<form method="POST">
    Email: <input type="email" name="email"><br><br>
    Password: <input type="password" name="password"><br><br>

    <button name="login">Login</button>
</form>