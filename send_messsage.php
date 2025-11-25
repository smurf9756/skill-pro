<?php
// send_message.php
require 'db.php';
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Not authenticated']);
    exit;
}

$sender = (int) $_SESSION['user_id'];
$receiver = (int) ($_POST['receiver_id'] ?? 0);
$message = trim($_POST['message'] ?? '');

if ($receiver <= 0 || $message === '') {
    echo json_encode(['success' => false, 'message' => 'Missing data']);
    exit;
}

$stmt = $pdo->prepare("INSERT INTO messages (sender_id, receiver_id, message) VALUES (?, ?, ?)");
$ok = $stmt->execute([$sender, $receiver, $message]);

if ($ok) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'DB error']);
}