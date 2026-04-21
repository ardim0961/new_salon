<?php
session_start();
require_once "config/db.php";

$error = "";

if(isset($_POST['login'])){
    $email = $_POST['email'];
    $password = $_POST['password'];

    $user = $conn->query("
        SELECT * FROM users 
        WHERE email='$email' AND password='$password'
    ")->fetch_assoc();

    if($user){
        $_SESSION['user'] = $user;

        if($user['role'] == 'admin'){
            header("Location: admin_dashboard.php");
        } elseif($user['role'] == 'staff'){
            header("Location: staff_dashboard.php");
        } else {
            header("Location: index.php");
        }
        exit;
    } else {
        $error = "Email atau password salah";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Login</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">

<div class="bg-white p-8 rounded-xl shadow-lg w-full max-w-md">

<h2 class="text-2xl font-bold mb-6 text-center">Login</h2>

<?php if($error): ?>
<div class="bg-red-100 text-red-600 p-3 rounded mb-4 text-sm">
    <?= $error ?>
</div>
<?php endif; ?>

<form method="POST" class="space-y-4">

    <div>
        <label class="text-sm">Email</label>
        <input type="email" name="email" required
            class="w-full border p-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-400">
    </div>

    <div>
        <label class="text-sm">Password</label>
        <input type="password" name="password" required
            class="w-full border p-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-400">
    </div>

    <button name="login"
        class="w-full bg-orange-500 text-white py-2 rounded-lg hover:bg-orange-600 transition">
        Login
    </button>

</form>

<p class="text-center text-sm mt-4">
    Belum punya akun?
    <a href="register.php" class="text-orange-500 font-semibold">Daftar</a>
</p>

</div>

</body>
</html>