<?php
$host = 'localhost';
$user = 'root';
$pass = '';
$db   = 'dxiepro_db';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("KONEKSI GAGAL BANGSAT: " . $conn->connect_error);
}

session_start();
?>
