<?php
include 'db.php';

$data = json_decode(file_get_contents("php://input"), true);

$skill = $data['skill_id'];
$name = $data['name'];
$rating = $data['rating'];
$review = $data['review'];

$stmt = $pdo->prepare("INSERT INTO reviews (skill_id, name, rating, review) VALUES (?, ?, ?, ?)");
$stmt->execute([$skill, $name, $rating, $review]);

echo json_encode(["success" => true]);
