<?php
session_start();
require_once "db.php";

$user_id = $_SESSION['user_id'] ?? 0;
$book_id = $_POST['book_id'];
$rating  = $_POST['rating'];
$review  = $_POST['review'];

if (!$user_id || !$book_id) {
    echo json_encode(["success" => false, "message" => "Missing user_id or book_id"]);
    exit;
}

$sql = "
    INSERT INTO ratings (user_id, book_id, rating, review)
    VALUES (?, ?, ?, ?)
    ON DUPLICATE KEY UPDATE 
        rating = VALUES(rating),
        review = VALUES(review),
        created_at = CURRENT_TIMESTAMP
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("iids", $user_id, $book_id, $rating, $review);
$stmt->execute();

echo json_encode(["success" => true]);
?>