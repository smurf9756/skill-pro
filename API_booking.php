<?php
// php/API_booking.php
session_start();
require_once __DIR__ . '/db.php';
header('Content-Type: application/json');

// Standardize session key
if (!isset($_SESSION['user']) || !isset($_SESSION['user']['id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$user_id = intval($_SESSION['user']['id']);
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $skill_id = intval($data['skill_id'] ?? 0);
    $scheduled_at = $data['scheduled_at'] ?? null; // expect YYYY-MM-DD
    $notes = trim($data['notes'] ?? '');

    if (!$skill_id || !$scheduled_at) {
        echo json_encode(['success' => false, 'message' => 'skill_id and scheduled_at required']);
        exit;
    }

    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $scheduled_at)) {
        echo json_encode(['success' => false, 'message' => 'Invalid date format']);
        exit;
    }

    $stmt = $pdo->prepare("SELECT id, user_id FROM skills WHERE id = ?");
    $stmt->execute([$skill_id]);
    $skill = $stmt->fetch();
    if (!$skill) {
        echo json_encode(['success' => false, 'message' => 'Skill not found']);
        exit;
    }
    $provider_id = $skill['user_id'] ?? null;

    $stmt = $pdo->prepare("INSERT INTO bookings (requester_id, provider_id, skill_id, booking_date, notes) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$user_id, $provider_id, $skill_id, $scheduled_at, $notes]);

    echo json_encode(['success' => true, 'id' => $pdo->lastInsertId()]);
    exit;
}

if ($method === 'GET') {
    $stmt = $pdo->prepare("SELECT b.*, s.title as skill_title, u.fullname as requester_name, p.fullname as provider_name
                           FROM bookings b
                           JOIN skills s ON b.skill_id = s.id
                           LEFT JOIN users u ON b.requester_id = u.id
                           LEFT JOIN users p ON b.provider_id = p.id
                           WHERE b.requester_id = ? OR b.provider_id = ?
                           ORDER BY b.created_at DESC");
    $stmt->execute([$user_id, $user_id]);
    $rows = $stmt->fetchAll();
    echo json_encode(['success' => true, 'data' => $rows]);
    exit;
}

http_response_code(405);
echo json_encode(['success' => false, 'message' => 'Method not allowed']);
exit;
