<?php
require_once "config/db.php";

$services = $conn->query("
    SELECT 
        s.id,
        s.name,
        s.price,
        s.duration,
        s.image,
        COUNT(DISTINCT a.user_id) as total_staff
    FROM services s
    LEFT JOIN service_staff ss ON ss.service_id = s.id
    LEFT JOIN attendance a 
        ON a.user_id = ss.user_id 
        AND a.end_time IS NULL
    GROUP BY s.id
");
?>

<!DOCTYPE html>
<html>
<head>
<title>Salon Booking</title>

<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Inter', sans-serif;
    background: #f8fafc;
}

/* HEADER */
.header {
    background: white;
    padding: 20px 40px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 1px solid #eee;
}

.logo {
    font-size: 20px;
    font-weight: bold;
}

.nav a {
    margin-left: 20px;
    text-decoration: none;
    color: #333;
}

/* HERO */
.hero {
    padding: 40px;
}

.hero h1 {
    font-size: 32px;
    margin-bottom: 10px;
}

.hero p {
    color: #666;
}

/* GRID */
.grid {
    padding: 40px;
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 25px;
}

/* CARD */
.card {
    background: white;
    border-radius: 16px;
    overflow: hidden;
    transition: 0.3s;
    box-shadow: 0 4px 15px rgba(0,0,0,0.05);
}

.card:hover {
    transform: translateY(-6px);
}

/* IMAGE */
.card img {
    width: 100%;
    height: 180px;
    object-fit: cover;
}

/* CONTENT */
.card-body {
    padding: 20px;
}

.service-name {
    font-size: 18px;
    font-weight: 600;
}

.price {
    margin-top: 5px;
    font-weight: bold;
    color: #ff7a00;
}

.duration {
    font-size: 13px;
    color: #777;
}

/* STAFF */
.staff {
    margin-top: 12px;
    font-size: 14px;
}

/* BADGE */
.badge {
    display: inline-block;
    margin-top: 10px;
    padding: 5px 10px;
    border-radius: 20px;
    font-size: 12px;
}

.available {
    background: #e6f9f0;
    color: #00a86b;
}

.unavailable {
    background: #fdecea;
    color: #e74c3c;
}

/* BUTTON */
.button {
    margin-top: 15px;
    width: 100%;
    padding: 12px;
    border-radius: 10px;
    border: none;
    background: black;
    color: white;
    cursor: pointer;
    font-weight: 500;
}

.button:hover {
    opacity: 0.9;
}

.disabled {
    background: #ccc !important;
    cursor: not-allowed;
}
</style>

</head>
<body>

<!-- HEADER -->
<div class="header">
    <div class="logo">💇 Salon</div>
    <div class="nav">
        <a href="customer_dashboard.php">Dashboard</a>
        <a href="products.php">Produk</a>
        <a href="logout.php">Logout</a>
    </div>
</div>

<!-- HERO -->
<div class="hero">
    <h1>Pilih Layanan</h1>
    <p>Pilih layanan terbaik sesuai kebutuhan kamu</p>
</div>

<!-- SERVICES -->
<div class="grid">

<?php while($row = $services->fetch_assoc()): ?>

<div class="card">

<?php if($row['image']): ?>
<img src="uploads/<?= $row['image'] ?>">
<?php else: ?>
<img src="https://via.placeholder.com/300x180?text=Salon">
<?php endif; ?>

<div class="card-body">

<div class="service-name"><?= htmlspecialchars($row['name']) ?></div>

<div class="price">
Rp<?= number_format($row['price'],0,',','.') ?>
</div>

<div class="duration">
⏱ <?= $row['duration'] ?> menit
</div>

<div class="staff">
👨‍💼 <?= $row['total_staff'] ?> staff tersedia
</div>

<?php if($row['total_staff'] > 0): ?>

<span class="badge available">Tersedia</span>

<a href="booking.php?service_id=<?= $row['id'] ?>">
<button class="button">Booking</button>
</a>

<?php else: ?>

<span class="badge unavailable">Tidak tersedia</span>

<button class="button disabled">Tidak tersedia</button>

<?php endif; ?>

</div>
</div>

<?php endwhile; ?>

</div>

</body>
</html>