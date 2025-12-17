<?php
session_start();
require_once "db.php";

// check user login
if (!isset($_SESSION['user_id'])) {
    echo json_encode([
        "success" => false,
        "message" => "User not logged in"
    ]);
    exit;
}

// Ambil JSON dari body request
$data = json_decode(file_get_contents("php://input"), true);

// Validasi input
if (!isset($data['id']) || empty($data['id'])) {
    echo json_encode([
        "success" => false,
        "message" => "Missing book ID"
    ]);
    exit;
}

$readingId = (int)$data['id'];
$userId = (int)$_SESSION['user_id'];
// Cek status buku sebelum delete
$stmt = $conn->prepare("SELECT status FROM reading_lists WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $readingId, $userId);
$stmt->execute();
$res = $stmt->get_result();

if (!$row = $res->fetch_assoc()) {
    echo json_encode([
        "success" => false,
        "message" => "Book not found"
    ]);
    exit;
}

if ($row['status'] !== 'to_read' && $row['status'] !== 'reading') {
    echo json_encode([
        "success" => false,
        "message" => "Cannot delete books with status 'finished'"
    ]);
    exit;
}

// Hapus buku sesuai user_id dan id
$stmt = $conn->prepare("DELETE FROM reading_lists WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $readingId, $userId);



if ($stmt->execute()) {
    echo json_encode([
        "success" => true,
        "message" => "Book deleted successfully"
    ]);
} else {
    echo json_encode([
        "success" => false,
        "message" => "Failed to delete book"
    ]);
}

$stmt->close();
$conn->close();
