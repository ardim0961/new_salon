<?php
require_once "config/db.php";

$error = "";
$success = "";

if(isset($_POST['register'])){

    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // VALIDASI
    if(!$name || !$email || !$password){
        $error = "Semua field wajib diisi";
    } else {

        // CEK EMAIL SUDAH ADA
        $check = $conn->query("SELECT * FROM users WHERE email='$email'");

        if($check->num_rows > 0){
            $error = "Email sudah digunakan";
        } else {

            // SIMPAN USER
            $conn->query("
                INSERT INTO users (name, email, password, role)
                VALUES ('$name', '$email', '$password', 'customer')
            ");

            $success = "Pendaftaran berhasil! Silakan login.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Daftar</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">

<div class="bg-white p-8 rounded-xl shadow-lg w-full max-w-md">

<h2 class="text-2xl font-bold mb-6 text-center">Daftar Akun</h2>

<?php if($error): ?>
<div class="bg-red-100 text-red-600 p-3 rounded mb-4 text-sm">
    <?= $error ?>
</div>
<?php endif; ?>

<?php if($success): ?>
<div class="bg-green-100 text-green-600 p-3 rounded mb-4 text-sm">
    <?= $success ?>
</div>
<?php endif; ?>

<form method="POST" class="space-y-4">

    <div>
        <label class="text-sm">Nama Pengguna</label>
        <input type="text" name="name" required
            class="w-full border p-2 rounded-lg focus:ring-2 focus:ring-orange-400">
    </div>

    <div>
        <label class="text-sm">Email</label>
        <input type="email" name="email" required
            class="w-full border p-2 rounded-lg focus:ring-2 focus:ring-orange-400">
    </div>

    <div>
        <label class="text-sm">Password</label>
        <input type="password" name="password" required
            class="w-full border p-2 rounded-lg focus:ring-2 focus:ring-orange-400">
    </div>

    <button name="register"
        class="w-full bg-orange-500 text-white py-2 rounded-lg hover:bg-orange-600 transition">
        Daftar
    </button>

</form>

<p class="text-center text-sm mt-4">
    Sudah punya akun?
    <a href="login.php" class="text-orange-500 font-semibold">Login</a>
</p>

</div>

</body>
</html>