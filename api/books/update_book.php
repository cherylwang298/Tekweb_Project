<?php
require_once "../../config/koneksi.php";

$id = $_POST['id'];
$title = $_POST['title'];
$author = $_POST['author'];
$synopsis = $_POST['synopsis'];
$book_cover = $_POST['book_cover'];

$stmt = $conn->prepare(
    "UPDATE books SET title=?, author=?, synopsis=?, book_cover=? WHERE id=?"
);
$stmt->bind_param("ssssi", $title, $author, $synopsis, $book_cover, $id);
$stmt->execute();

echo json_encode(["success" => true]);
?>