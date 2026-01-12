<?php
session_start();
include '../config/database.php';

// Ambil parameter action dari URL
$action = $_GET['action'] ?? '';

// --- 1. PROSES REGISTER ---
if ($action == 'register' && $_SERVER['REQUEST_METHOD'] == 'POST') {
    $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
    $username  = mysqli_real_escape_string($conn, $_POST['username']);
    $password  = $_POST['password'];

    // Cek apakah username sudah ada
    $check = mysqli_query($conn, "SELECT id FROM users WHERE username = '$username'");
    if (mysqli_num_rows($check) > 0) {
        $_SESSION['error'] = "Username sudah dipakai, coba yang lain.";
        header("Location: ../index.php?page=register");
        exit;
    }

    // Enkripsi Password
    $hashed_pass = password_hash($password, PASSWORD_DEFAULT);

    // Simpan ke Database
    $query = "INSERT INTO users (full_name, username, password) VALUES ('$full_name', '$username', '$hashed_pass')";

    if (mysqli_query($conn, $query)) {
        $_SESSION['success'] = "Pendaftaran berhasil! Silakan login.";
        header("Location: ../index.php");
    } else {
        $_SESSION['error'] = "Gagal mendaftar: " . mysqli_error($conn);
        header("Location: ../index.php?page=register");
    }
}

// --- 2. PROSES LOGIN ---
elseif ($action == 'login' && $_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    $query = mysqli_query($conn, "SELECT * FROM users WHERE username = '$username'");

    // Cek Username
    if (mysqli_num_rows($query) === 1) {
        $user = mysqli_fetch_assoc($query);

        // Cek Password (Verify Hash)
        if (password_verify($password, $user['password'])) {
            // Set Session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['full_name'] = $user['full_name'];
            $_SESSION['is_login'] = true;

            header("Location: ../tasks.php"); // Redirect ke Dashboard
            exit;
        }
    }

    $_SESSION['error'] = "Username atau password salah!";
    header("Location: ../index.php");
}

// --- 3. PROSES LOGOUT ---
elseif ($action == 'logout') {
    session_destroy();
    header("Location: ../index.php");
}
