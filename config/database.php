<?php

$host = getenv('DB_HOST');
$username = getenv('DB_USERNAME');
$password = getenv('DB_PASSWORD');
$db = getenv('DB_DATABASE');

$conn = mysqli_connect($host, $username, $password, $db);

if (!$conn) {
    die("Koneksi Gagal: " . mysqli_connect_error());
}
