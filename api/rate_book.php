<?php
require_once "db.php";

$user_id = $_POST["user_id"] ?? 0;
$book_id = $_POST["book_id"] ?? 0;
$rating  = $_POST["rating"] ?? null;
$review  = $_POST["review"] ?? null;

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
$stmt->bind_param("iiis", $user_id, $book_id, $rating, $review);
$stmt->execute();

echo json_encode(["success" => true]);
?>