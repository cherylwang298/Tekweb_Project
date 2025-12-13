<?php
$host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'tekweb_project';

// Membuat koneksi
$conn = new mysqli($host, $db_user, $db_pass, $db_name);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>