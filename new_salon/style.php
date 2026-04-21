<?php
require_once "config/db.php";

$theme = $conn->query("SELECT * FROM theme LIMIT 1")->fetch_assoc();

$header = $theme['header_color'] ?? '#111827';
$main   = $theme['main_color'] ?? '#f9fafb';
$footer = $theme['footer_color'] ?? '#111827';
$font   = $theme['font_family'] ?? 'Inter, sans-serif';

header("Content-Type: text/css");
?>

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: <?= $font ?>;
    background: <?= $main ?>;
    color: #111;
}

/* ===== LAYOUT ===== */
.wrapper {
    display: flex;
}

/* ===== SIDEBAR ===== */
.sidebar {
    width: 240px;
    background: #111827;
    color: white;
    min-height: 100vh;
    padding: 20px;
}

.sidebar h3 {
    margin-bottom: 25px;
    font-size: 18px;
}

.sidebar a {
    display: block;
    color: #d1d5db;
    text-decoration: none;
    padding: 10px;
    border-radius: 8px;
    margin-bottom: 8px;
    transition: 0.2s;
}

.sidebar a:hover {
    background: <?= $header ?>;
    color: white;
}

/* ===== TOPBAR ===== */
.topbar {
    background: <?= $header ?>;
    color: white;
    padding: 15px 25px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

/* ===== MAIN ===== */
.main {
    padding: 40px;
    min-height: 100vh;
}

/* ===== GRID ===== */
.grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 25px;
}

/* ===== CARD ===== */
.card {
    background: white;
    border-radius: 16px;
    padding: 20px;
    box-shadow: 0 6px 18px rgba(0,0,0,0.06);
    transition: 0.2s;
}

.card:hover {
    transform: translateY(-4px);
}

/* ===== BUTTON ===== */
.button {
    padding: 10px 16px;
    background: <?= $header ?>;
    color: white;
    border-radius: 8px;
    border: none;
    cursor: pointer;
    font-weight: 500;
}

.button:hover {
    opacity: 0.9;
}

/* ===== BADGE ===== */
.badge {
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 12px;
}

.success {
    background: #dcfce7;
    color: #16a34a;
}

.danger {
    background: #fee2e2;
    color: #dc2626;
}

/* ===== FORM ===== */
input, select {
    padding: 10px;
    border-radius: 8px;
    border: 1px solid #ddd;
}

/* ===== FOOTER ===== */
.footer {
    text-align: center;
    padding: 20px;
    background: <?= $footer ?>;
    color: white;
    margin-top: 40px;
}