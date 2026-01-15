<?php

// --- FUNGSI PEMBACA .ENV MANUAL ---
function loadEnv($path) {
    if (!file_exists($path)) {
        return false;
    }
    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) {
            continue; // Lewati komentar
        }
        list($name, $value) = explode('=', $line, 2);
        $name = trim($name);
        $value = trim($value);
        
        // Hapus tanda kutip jika ada
        $value = str_replace('"', '', $value);
        $value = str_replace("'", '', $value);

        if (!array_key_exists($name, $_SERVER) && !array_key_exists($name, $_ENV)) {
            putenv(sprintf('%s=%s', $name, $value));
            $_ENV[$name] = $value;
            $_SERVER[$name] = $value;
        }
    }
}

// Panggil fungsinya (Pastikan file .env ada di folder yg sama)
loadEnv(__DIR__ . '/.env');

$host = getenv('DB_HOST');
$username = getenv('DB_USERNAME');
$password = getenv('DB_PASSWORD');
$db = getenv('DB_DATABASE');

$conn = mysqli_connect($host, $username, $password, $db);

if (!$conn) {
    die("Koneksi Gagal: " . mysqli_connect_error());
}
