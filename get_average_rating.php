<?php
header("Content-Type: application/json");
include "db.php";

if (!isset($_GET["skill_name"])) {
    echo json_encode(["avg_rating" => 0, "total_reviews" => 0]);
    exit;
}

$skill = $_GET["skill_name"];

$stmt = $pdo->prepare("SELECT AVG(rating) AS avg_rating, COUNT(*) AS total_reviews FROM reviews WHERE skill_name = ?");
$stmt->execute([$skill]);
$data = $stmt->fetch(PDO::FETCH_ASSOC);

echo json_encode([
    "avg_rating" => round($data["avg_rating"], 1),
    "total_reviews" => intval($data["total_reviews"])
]);
