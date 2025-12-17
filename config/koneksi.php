<?php
$host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'tekweb_project';

// Membuat koneksi
$conn = new mysqli($host, $db_user, $db_pass, $db_name);

// Cek koneksi
if ($conn->connect_error) {
    http_response_code(500);
    die(json_encode(["error" => "Database connection failed"]));
}
?>