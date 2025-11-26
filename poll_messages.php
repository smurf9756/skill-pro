<?php
require "db.php";
session_start();

$my_id = $_SESSION['user_id'];
$chat_with = $_GET['chat_with'];
$last_id = $_GET['last_id'];

$stmt = $pdo->prepare("
    SELECT m.*, u.profile_image
FROM messages m
JOIN users u ON m.sender_id = u.id
WHERE
        (
            sender_id = ? AND receiver_id = ?
        ) OR (
            sender_id = ? AND receiver_id = ?
        )
        AND id > ?
    ORDER BY id ASC
");
$stmt->execute([$my_id, $chat_with, $chat_with, $my_id, $last_id]);

echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));