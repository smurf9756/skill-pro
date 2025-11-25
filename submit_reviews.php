<?php
session_start();
include "db.php";

header("Content-Type: application/json");

// Validate fields
if (!isset($_POST["skill_name"], $_POST["username"], $_POST["rating"], $_POST["review"])) {
  echo json_encode(["success" => false, "message" => "Missing fields"]);
  exit;
}

$skill_name = trim($_POST["skill_name"]);
$username = trim($_POST["username"]);
$rating = intval($_POST["rating"]);
$review = trim($_POST["review"]);

$stmt = $pdo->prepare("
    INSERT INTO skill_reviews (skill_name, username, rating, review)
    VALUES (?, ?, ?, ?)
");
$stmt->execute([$skill_name, $username, $rating, $review]);

echo json_encode(["success" => true]);
