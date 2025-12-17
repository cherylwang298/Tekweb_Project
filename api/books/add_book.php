<?php
require_once "../../config/koneksi.php";

// Input
$title = $_POST['title'];
$author = $_POST['author'];
$synopsis = $_POST['synopsis'];
$book_cover = $_POST['book_cover']; // URL

// Cek duplikasi
$check = $conn->prepare("SELECT id FROM books WHERE title=? AND author=?");
$check->bind_param("ss", $title, $author);
$check->execute();
$check->store_result();

if ($check->num_rows > 0) {
    echo json_encode(["success" => false, "message" => "Book already exists"]);
    exit;
}

$stmt = $conn->prepare("INSERT INTO books (title, author, synopsis, book_cover) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $title, $author, $synopsis, $book_cover);
$stmt->execute();

echo json_encode(["success" => true, "id" => $stmt->insert_id]);