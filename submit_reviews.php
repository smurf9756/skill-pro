<?php
header('Content-Type: application/json');
include "db.php";

if (
  !isset($_POST["skill_name"]) ||
  !isset($_POST["username"]) ||
  !isset($_POST["rating"]) ||
  !isset($_POST["review"])
) {
  echo json_encode(["success" => false, "message" => "Missing fields"]);
  exit;
}

$skill_name = trim($_POST["skill_name"]);
$username = trim($_POST["username"]);
$rating = intval($_POST["rating"]);
$review = trim($_POST["review"]);

if ($skill_name === "" || $username === "" || $rating <= 0 || $review === "") {
  echo json_encode(["success" => false, "message" => "Invalid input"]);
  exit;
}

$stmt = $pdo->prepare("
    INSERT INTO reviews (skill_name, username, rating, review)
    VALUES (?, ?, ?, ?)
");

$done = $stmt->execute([$skill_name, $username, $rating, $review]);

if ($done) {
  echo json_encode(["success" => true]);
} else {
  echo json_encode(["success" => false, "message" => "DB error"]);
}
