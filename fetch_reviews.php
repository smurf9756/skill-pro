<?php
include "db.php";
header("Content-Type: application/json");

if (!isset($_GET["skill_name"])) {
    echo json_encode([]);
    exit;
}

$skill_name = $_GET["skill_name"];

$stmt = $pdo->prepare("
    SELECT username, rating, review, timestamp
    FROM skill_reviews
    WHERE skill_name = ?
    ORDER BY id DESC
");
$stmt->execute([$skill_name]);

echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
