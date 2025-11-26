<?php
// read_messages.php
require 'db.php';
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'messages' => []]);
    exit;
}

$me = (int) $_SESSION['user_id'];
$other = (int) ($_GET['user'] ?? 0);
if ($other <= 0) {
    echo json_encode(['success' => false, 'messages' => []]);
    exit;
}

$stmt = $pdo->prepare("
    SELECT m.id, m.sender_id, m.receiver_id, m.message, m.sent_at,
           u.profile_image AS sender_image, u.fullname AS sender_name
    FROM messages m
    JOIN users u ON u.id = m.sender_id
    WHERE (m.sender_id = ? AND m.receiver_id = ?)
       OR (m.sender_id = ? AND m.receiver_id = ?)
    ORDER BY m.id ASC
");
$stmt->execute([$me, $other, $other, $me]);
$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode(['success' => true, 'messages' => $messages]);
