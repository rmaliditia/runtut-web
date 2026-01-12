<?php
session_start();
require '../config/database.php';

// Cek Login
if (!isset($_SESSION['is_login'])) {
    header("Location: ../index.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$action  = $_GET['action'] ?? '';

// --- 1. TAMBAH TUGAS BARU (UPDATED) ---
if ($action == 'add' && $_SERVER['REQUEST_METHOD'] == 'POST') {
    $title       = mysqli_real_escape_string($conn, $_POST['title']);

    // BAGIAN BARU: Ambil data deskripsi dari form
    $description = mysqli_real_escape_string($conn, $_POST['description']);

    $category    = $_POST['category'];
    $due_date    = !empty($_POST['due_date']) ? $_POST['due_date'] : NULL;

    // UPDATE QUERY: Tambahkan kolom 'description' ke dalam INSERT
    $query = "INSERT INTO tasks (user_id, title, description, category, status, due_date) 
              VALUES ('$user_id', '$title', '$description', '$category', 'pending', " . ($due_date ? "'$due_date'" : "NULL") . ")";

    if (mysqli_query($conn, $query)) {
        // Redirect kembali ke halaman asal
        header("Location: " . $_SERVER['HTTP_REFERER']);
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

// --- 2. HAPUS TUGAS ---
elseif ($action == 'delete' && isset($_GET['id'])) {
    $task_id = $_GET['id'];

    // Pastikan yang dihapus adalah task milik user yang login
    mysqli_query($conn, "DELETE FROM tasks WHERE id = '$task_id' AND user_id = '$user_id'");

    header("Location: " . $_SERVER['HTTP_REFERER']);
}

// --- 3. TANDAI SELESAI (COMPLETE) ---
elseif ($action == 'complete' && isset($_GET['id'])) {
    $task_id = $_GET['id'];
    $now = date('Y-m-d H:i:s');

    // Update status jadi completed & isi completed_at
    mysqli_query($conn, "UPDATE tasks SET status = 'completed', completed_at = '$now' WHERE id = '$task_id' AND user_id = '$user_id'");

    header("Location: " . $_SERVER['HTTP_REFERER']);
}

// --- 4. BATALKAN SELESAI (UNCOMPLETE) ---
elseif ($action == 'uncomplete' && isset($_GET['id'])) {
    $task_id = $_GET['id'];

    // Kembalikan status jadi 'pending' dan hapus completed_at
    mysqli_query($conn, "UPDATE tasks SET status = 'pending', completed_at = NULL WHERE id = '$task_id' AND user_id = '$user_id'");

    header("Location: " . $_SERVER['HTTP_REFERER']);
}

// --- 5. BERSIHKAN SEMUA HISTORY (CLEAR ALL) ---
elseif ($action == 'clear_history') {
    // Hapus semua task milik user yang statusnya 'completed'
    mysqli_query($conn, "DELETE FROM tasks WHERE user_id = '$user_id' AND status = 'completed'");
    
    // Kembali ke halaman history
    header("Location: ../history.php");
}