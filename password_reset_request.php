<?php
// php/password_reset_request.php
session_start();
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/functions.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'POST only']);
    exit;
}

$email = strtolower(trim($_POST['email'] ?? ''));
if (!$email) {
    echo json_encode(['success' => false, 'message' => 'Email required']);
    exit;
}

$stmt = $pdo->prepare("SELECT id, fullname FROM users WHERE email = ?");
$stmt->execute([$email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    // don't reveal existence of email
    echo json_encode(['success' => true, 'message' => 'If that email exists, a reset link was sent']);
    exit;
}

$token = bin2hex(random_bytes(32));
$expires = (new DateTime('+2 hours'))->format('Y-m-d H:i:s');

$stmt = $pdo->prepare("INSERT INTO password_resets (user_id, token, expires_at) VALUES (?, ?, ?)");
$stmt->execute([$user['id'], $token, $expires]);

$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$resetUrl = $protocol . '://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['REQUEST_URI']) . "/php/password_reset.php?token={$token}";
$body = "<p>Hi {$user['fullname']},</p><p>Reset your password: <a href=\"{$resetUrl}\">Reset password</a></p>";
send_mail($email, $user['fullname'], 'Password reset', $body);

echo json_encode(['success' => true, 'message' => 'If that email exists, a reset link was sent']);
exit;
