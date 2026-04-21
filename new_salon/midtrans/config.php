<?php

require_once __DIR__ . '/../vendor/autoload.php';

// =========================
// MIDTRANS CONFIG
// =========================

// 🔑 SERVER KEY (SANDBOX)
\Midtrans\Config::$serverKey = 'Mid-server-lKCC_169KojQ4TvW9CIFUqpn';

// MODE
\Midtrans\Config::$isProduction = false;

// SECURITY (WAJIB AKTIFKAN)
\Midtrans\Config::$isSanitized = true;
\Midtrans\Config::$is3ds = true;

// OPTIONAL (biar lebih stabil)
\Midtrans\Config::$curlOptions = [
    CURLOPT_CONNECTTIMEOUT => 10,
    CURLOPT_TIMEOUT => 10
];