<?php
/** @var mysqli $conn */
session_start();
require '../config/database.php';

// Set header agar browser tahu ini adalah data JSON
header('Content-Type: application/json');

// Cek Login: Jika belum login, kirim array kosong (keamanan)
if (!isset($_SESSION['user_id'])) {
    echo json_encode([]);
    exit;
}

$user_id = $_SESSION['user_id'];

// Ambil task milik user yang memiliki tanggal (due_date tidak NULL)
// FullCalendar otomatis mengirim param 'start' & 'end' jika ingin filter per bulan,
// tapi untuk tahap awal kita ambil semua task agar mudah.
$query = "SELECT title, due_date, category, status FROM tasks WHERE user_id = '$user_id' AND due_date IS NOT NULL";
$result = mysqli_query($conn, $query);

$events = [];

while ($row = mysqli_fetch_assoc($result)) {
    // 1. Logika Warna Berdasarkan Kategori (Sesuai Desain Dummy)
    $color = '#6c5ce7'; // Default (Ungu)

    switch ($row['category']) {
        case 'Work':
            $color = '#4379EE'; // Biru Primary
            break;
        case 'Personal':
            $color = '#FF9F43'; // Orange
            break;
        case 'Study':
            $color = '#00b894'; // Hijau Teal
            break;
        case 'Health':
            $color = '#ff7675'; // Merah Muda
            break;
        case 'None':
            $color = '#6c757d';
            break;
    }

    // 2. Format Data Sesuai Standar FullCalendar
    // Kita ambil tanggal dari 'due_date'
    $events[] = [
        'title' => $row['title'],
        'start' => $row['due_date'], // MySQL format (YYYY-MM-DD) sudah otomatis terbaca
        'color' => $color,           // Warna background event
        'textColor' => '#ffffff',    // Warna teks putih

        // Data tambahan (opsional, berguna jika nanti mau buat fitur klik event)
        'extendedProps' => [
            'status'   => $row['status'],
            'category' => $row['category']
        ]
    ];
}

// Kirim data ke Javascript
echo json_encode($events);
