<?php
$host = 'localhost';
$db_user = 'tekweb';
$db_pass = 'tekweb123';
$db_name = 'tekweb_project';

// Membuat koneksi
$conn = new mysqli($host, $db_user, $db_pass, $db_name);

// Cek koneksi
if ($conn->connect_error) {
    http_response_code(500);
    die(json_encode(["error" => "Database connection failed"]));
}

$conn->set_charset("utf8mb4");
?>