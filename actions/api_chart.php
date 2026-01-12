<?php
session_start();
require '../config/database.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode([]);
    exit;
}

$user_id = $_SESSION['user_id'];

// --- 1. DATA PIE CHART (Berdasarkan Kategori) ---
// Menghitung jumlah tugas per kategori
$query_cat = "SELECT category, COUNT(*) as total FROM tasks WHERE user_id = '$user_id' GROUP BY category";
$res_cat = mysqli_query($conn, $query_cat);

$data_pie = [
    'labels' => [],
    'data'   => []
];

while ($row = mysqli_fetch_assoc($res_cat)) {
    $data_pie['labels'][] = $row['category']; // Work, Personal, dll
    $data_pie['data'][]   = $row['total'];    // 5, 3, dll
}

// --- 2. DATA BAR CHART (Aktivitas Mingguan) ---
// Menghitung tugas yang DISELESAIKAN dalam 7 hari terakhir
$query_week = "SELECT DATE(completed_at) as tgl, COUNT(*) as total 
               FROM tasks 
               WHERE user_id = '$user_id' 
               AND status = 'completed' 
               AND completed_at >= DATE(NOW() - INTERVAL 6 DAY)
               GROUP BY DATE(completed_at)";
$res_week = mysqli_query($conn, $query_week);

// Siapkan array kosong untuk 7 hari terakhir (Senin-Minggu)
$week_data = [];
for ($i = 6; $i >= 0; $i--) {
    $date = date('Y-m-d', strtotime("-$i days"));
    $week_data[$date] = 0; // Default 0
}

// Isi array dengan data dari database
while ($row = mysqli_fetch_assoc($res_week)) {
    $week_data[$row['tgl']] = $row['total'];
}

// Format ulang agar bisa dibaca Chart.js
$data_bar = [
    'labels' => [], // "Mon", "Tue"
    'data'   => []  // 2, 0, 5
];

foreach ($week_data as $date => $total) {
    $data_bar['labels'][] = date('D', strtotime($date)); // Ubah '2026-01-10' jadi 'Sat'
    $data_bar['data'][]   = $total;
}

// Kirim Semua Data
echo json_encode([
    'pie' => $data_pie,
    'bar' => $data_bar
]);
?>