<?php
session_start();
require_once __DIR__ . "/../../config/koneksi.php";

header("Content-Type: application/json");

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["in_list" => false]);
    exit;
}

$user_id = $_SESSION['user_id'];
$book_id = $_GET['book_id'] ?? 0;

if (!$book_id) {
    echo json_encode(["in_list" => false]);
    exit;
}

$stmt = $conn->prepare("
    SELECT id 
    FROM reading_lists 
    WHERE user_id = ? AND book_id = ?
    LIMIT 1
");
$stmt->bind_param("ii", $user_id, $book_id);
$stmt->execute();
$stmt->store_result();

echo json_encode([
    "in_list" => $stmt->num_rows > 0
]);