<?php
session_start();
require_once __DIR__ . "/../../config/koneksi.php";

header("Content-Type: application/json");

if (!isset($_SESSION['user_id'])) {
  http_response_code(401);
  echo json_encode(["error" => "Unauthorized"]);
  exit;
}

$user_id = $_SESSION['user_id'];

$sql = "
SELECT
  rl.id AS reading_id,
  rl.book_id AS book_id,
  rl.status,
  rl.added_at,
  b.id AS book_id,
  b.title,
  b.author,
  b.synopsis,
  b.book_cover
FROM reading_lists rl
JOIN books b ON rl.book_id = b.id
WHERE rl.user_id = ?
ORDER BY rl.added_at DESC
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();

$result = $stmt->get_result();
$books = [];

while ($row = $result->fetch_assoc()) {
  $books[] = $row;
}

echo json_encode($books);