<?php
session_start();
require_once __DIR__ . "/../../config/koneksi.php";

header("Content-Type: application/json");

if (!isset($_SESSION['user_id'])) {
  http_response_code(401);
  echo json_encode(["success" => false, "message" => "Unauthorized"]);
  exit;
}

$user_id = $_SESSION['user_id'];

$selected_book_id = $_POST['book_id'] ?? null;
$title  = trim($_POST['judul'] ?? '');
$author = trim($_POST['penulis'] ?? '');
$status = trim($_POST['status'] ?? '');

if ($status === '') {
  $status = 'to_read';
}

if ($selected_book_id) {

  $stmt = $conn->prepare("SELECT id FROM books WHERE id = ?");
  $stmt->bind_param("i", $selected_book_id);
  $stmt->execute();
  $res = $stmt->get_result();

  if (!$row = $res->fetch_assoc()) {
    echo json_encode(["success" => false, "message" => "Invalid book"]);
    exit;
  }

  $book_id = (int)$row['id'];

} else {

  if ($title === '' || $author === '') {
    echo json_encode(["success" => false, "message" => "Title & author required"]);
    exit;
  }

  // Cek di master books
  $stmt = $conn->prepare("
    SELECT id FROM books 
    WHERE title = ? AND author = ?
    LIMIT 1
  ");
  $stmt->bind_param("ss", $title, $author);
  $stmt->execute();
  $res = $stmt->get_result();

  if ($row = $res->fetch_assoc()) {
    $book_id = $row['id'];
  } else {
    // // Insert buku baru
    // $stmt = $conn->prepare("
    //   INSERT INTO books (title, author)
    //   VALUES (?, ?)
    // ");
    // $stmt->bind_param("ss", $title, $author);
    // $stmt->execute();
    // $book_id = $stmt->insert_id;

    echo json_encode(["success" => false, "message" => "Book not available in library"]);
exit;
  }
}

$stmt = $conn->prepare("
  SELECT id FROM reading_lists
  WHERE user_id = ? AND book_id = ?
");
$stmt->bind_param("ii", $user_id, $book_id);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
  echo json_encode([
    "success" => false,
    "message" => "Book already in your library"
  ]);
  exit;
}

$stmt = $conn->prepare("
  INSERT INTO reading_lists (user_id, book_id, status)
  VALUES (?, ?, ?)
");
$stmt->bind_param("iis", $user_id, $book_id, $status);
$stmt->execute();

echo json_encode(["success" => true]);