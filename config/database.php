<?php
date_default_timezone_set('Asia/Jakarta');
//  FUNGSI PEMBACA .ENV MANUAL 
function loadEnv($path)
{
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

// 1. Panggil dulu Environment-nya
loadEnv(dirname(__DIR__) . '/.env');

// 2. LOGIKA KEAMANAN BARU
// Cek apakah mode DEBUG aktif?
$debug_mode = getenv('APP_DEBUG');

if ($debug_mode === 'true') {
    // Kalo true: Tampilkan semua error (Mode Ngoding)
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
} else {
    // Kalo false: Sembunyikan error (Mode Production/Aman)
    ini_set('display_errors', 0);
    ini_set('display_startup_errors', 0);
    error_reporting(0);
}

$host = getenv('DB_HOST');
$username = getenv('DB_USERNAME');
$password = getenv('DB_PASSWORD');
$db = getenv('DB_DATABASE');

$conn = mysqli_connect($host, $username, $password, $db);

if (!$conn) {
    die("Koneksi Gagal: " . mysqli_connect_error());
}
