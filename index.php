<?php
session_start();
include 'config/database.php';
//  CEK AUTO LOGIN DARI COOKIE 
if (!isset($_SESSION['is_login']) && isset($_COOKIE['runtut_uid']) && isset($_COOKIE['runtut_token'])) {
    $cookie_uid = mysqli_real_escape_string($conn, $_COOKIE['runtut_uid']);
    $cookie_token = mysqli_real_escape_string($conn, $_COOKIE['runtut_token']);

    // Cari user berdasarkan ID dan Token
    $query = mysqli_query($conn, "SELECT * FROM users WHERE id = '$cookie_uid' AND remember_token = '$cookie_token'");

    if (mysqli_num_rows($query) === 1) {
        $user = mysqli_fetch_assoc($query);

        // Token valid, buat ulang sesi login
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['full_name'] = $user['full_name'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['is_login'] = true;

        // Langsung lempar ke dashboard tanpa perlu login
        header("Location: tasks.php");
        exit;
    }
}


// Jika user sudah login, langsung lempar ke Dashboard (Tasks)
if (isset($_SESSION['is_login']) && $_SESSION['is_login'] === true) {
    header("Location: tasks.php");
    exit;
}

// Routing sederhana untuk Login / Register
$page = $_GET['page'] ?? 'login';

if ($page == 'register') {
    include 'views/auth/register_view.php';
} else {
    include 'views/auth/login_view.php';
}
