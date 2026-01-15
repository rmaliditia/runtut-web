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
// Coba ambil satu data
$db_host = getenv('DB_HOST');
var_dump($db_host);