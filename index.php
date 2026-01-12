<?php
session_start();

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
