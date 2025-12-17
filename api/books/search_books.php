<?php
require_once __DIR__ . "/../../config/koneksi.php";

$q = $_GET['q'] ?? '';

if (strlen($q) < 1) {
  echo json_encode([]);
  exit;
}

$stmt = $conn->prepare("
  SELECT id, title, author 
  FROM books 
  WHERE title LIKE ?
  ORDER BY title ASC
  LIMIT 10
");
$like = "%$q%";
$stmt->bind_param("s", $like);
$stmt->execute();

$result = $stmt->get_result();
$data = [];

while ($row = $result->fetch_assoc()) {
  $data[] = $row;
}

header("Content-Type: application/json");
echo json_encode($data);
