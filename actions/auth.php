<?php

/** @var mysqli $conn */
session_start();
include '../config/database.php';

// Ambil parameter action dari URL
$action = $_GET['action'] ?? '';

//  1. PROSES REGISTER 
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

    // PREPARED STATEMENTS UNTUK INSERT
    $stmt = $conn->prepare("INSERT INTO users (full_name, username, password) VALUES (?, ?, ?)");

    // "sss" = ketiga datanya adalah String
    $stmt->bind_param("sss", $full_name, $username, $hashed_pass);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Pendaftaran berhasil! Silakan login.";
        header("Location: ../index.php");
    } else {
        $_SESSION['error'] = "Gagal mendaftar: " . $stmt->error;
        header("Location: ../index.php?page=register");
    }

    $stmt->close();
}
//  2. PROSES LOGIN 
elseif ($action == 'login' && $_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // PREPARED STATEMENTS UNTUK SELECT
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username); // "s" untuk string
    $stmt->execute();
    $result = $stmt->get_result();

    // Cek Username
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // Cek Password (Verify Hash)
        if (password_verify($password, $user['password'])) {
            // Set Session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['full_name'] = $user['full_name'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['is_login'] = true;

            $_SESSION['play_welcome'] = true;

            header("Location: ../tasks.php");
            exit;
        }
    }

    $stmt->close();

    $_SESSION['error'] = "Username atau password salah!";
    header("Location: ../index.php");
}

//  3. PROSES LOGOUT 
elseif ($action == 'logout') {

    // Hapus token di database jika ada user yang sedang login
    if (isset($_SESSION['user_id'])) {
        $userId = $_SESSION['user_id'];
        mysqli_query($conn, "UPDATE users SET remember_token = NULL WHERE id = '$userId'");
    }

    session_destroy();

    // Hancurkan Cookie dengan cara mengatur waktu kadaluarsanya ke masa lalu
    setcookie('runtut_uid', '', time() - 3600, "/");
    setcookie('runtut_token', '', time() - 3600, "/");

    header("Location: ../index.php");
    exit;
}
