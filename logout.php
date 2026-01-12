<?php
session_start();

// 1. Kosongkan semua variabel session
$_SESSION = [];

// 2. Hancurkan session
session_destroy();

// 3. Redirect kembali ke halaman Login (index.php)
header("Location: index.php");
exit;
?>