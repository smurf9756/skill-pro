<?php
header("Content-Type: application/json");
include "db.php";

if (!isset($_GET["skill_name"])) {
    echo json_encode([]);
    exit;
}

$skill = $_GET["skill_name"];

$stmt = $pdo->prepare("SELECT * FROM reviews WHERE skill_name = ? ORDER BY id DESC");
$stmt->execute([$skill]);
$reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($reviews);
