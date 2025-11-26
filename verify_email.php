<?php
// php/verify_email.php
session_start();
require_once __DIR__ . '/db.php';

$token = $_GET['token'] ?? '';
if (!$token) {
    echo 'Invalid token';
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM email_verifications WHERE token = ? LIMIT 1");
$stmt->execute([$token]);
$ev = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$ev) {
    echo 'Token not found or expired';
    exit;
}

if (new DateTime() > new DateTime($ev['expires_at'])) {
    echo 'Token expired';
    exit;
}

// mark user as verified
$stmt = $pdo->prepare("UPDATE users SET email_verified = 1 WHERE id = ?");
$stmt->execute([$ev['user_id']]);

// delete verification record
$stmt = $pdo->prepare("DELETE FROM email_verifications WHERE id = ?");
$stmt->execute([$ev['id']]);

// optional: auto-login user
$stmt = $pdo->prepare("SELECT id, fullname, email, role FROM users WHERE id = ?");
$stmt->execute([$ev['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
if ($user) {
    $_SESSION['user'] = ['id' => $user['id'], 'fullname' => $user['fullname'], 'email' => $user['email'], 'role' => $user['role']];
}

header('Location: /index.php');
exit;
