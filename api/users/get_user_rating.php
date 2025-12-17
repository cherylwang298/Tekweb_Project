<?php
require_once "../../config/koneksi.php";

$user_id = $_GET["user_id"] ?? 0;
$book_id = $_GET["book_id"] ?? 0;

$sql = "SELECT rating, review FROM ratings WHERE user_id = ? AND book_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $user_id, $book_id);
$stmt->execute();
$result = $stmt->get_result()->fetch_assoc();

echo json_encode($result ?: ["rating" => null, "review" => null]);
?>