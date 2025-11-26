<?php
// php/password_reset.php
session_start();
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $token = $_GET['token'] ?? '';
    echo '<!doctype html><html><body><form method="POST">';
    echo '<input type="hidden" name="token" value="' . htmlspecialchars($token) . '">';
    echo '<input name="password" type="password" placeholder="New password" required>';
    echo '<button type="submit">Change password</button></form></body></html>';
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['token'] ?? '';
    $password = $_POST['password'] ?? '';
    if (!$token || !$password) {
        echo 'Missing';
        exit;
    }

    $stmt = $pdo->prepare("SELECT * FROM password_resets WHERE token = ? LIMIT 1");
    $stmt->execute([$token]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$row) {
        echo 'Invalid or expired token';
        exit;
    }
    if (new DateTime() > new DateTime($row['expires_at'])) {
        echo 'Expired token';
        exit;
    }

    $hashed = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
    $stmt->execute([$hashed, $row['user_id']]);

    // delete tokens
    $stmt = $pdo->prepare("DELETE FROM password_resets WHERE user_id = ?");
    $stmt->execute([$row['user_id']]);

    echo 'Password changed. You may <a href="/login.php">login</a>.';
    exit;
}
