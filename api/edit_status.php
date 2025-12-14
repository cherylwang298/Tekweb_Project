<?php
session_start();
require_once "db.php"; // koneksi ke database
header('Content-Type: application/json');

// Pastikan request POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
    exit;
}

// Ambil data POST
$id = $_POST['id'] ?? null;       // ini id pada tabel reading_list
$status = $_POST['status'] ?? null;

if (!$id || !$status) {
    echo json_encode(['success' => false, 'message' => 'All fields are required']);
    exit;
}

// Ambil user_id dari session
$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit;
}

try {
    // Update status
    $stmt = $conn->prepare("UPDATE reading_lists SET status = ? WHERE id = ? AND user_id = ?");
    $stmt->bind_param("sii", $status, $id, $user_id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Status updated successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update status']);
    }

    $stmt->close();
    $conn->close();
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
