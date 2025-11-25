<?php
session_start();
require_once __DIR__ . '/db.php';
header('Content-Type: application/json');

// Must be logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'You must be logged in']);
    exit;
}

$user_id = intval($_SESSION['user_id']);
$skill_id = intval($_POST['skill_id'] ?? 0);
$date = $_POST['date'] ?? '';
$notes = trim($_POST['notes'] ?? '');

// Validate fields
if (!$skill_id || !$date) {
    echo json_encode(['success' => false, 'message' => 'Missing required fields']);
    exit;
}

if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
    echo json_encode(['success' => false, 'message' => 'Invalid date format']);
    exit;
}

// Fetch skill from shared_skills
$stmt = $pdo->prepare("SELECT id, skill_name, user_id FROM shared_skills WHERE id = ?");
$stmt->execute([$skill_id]);
$skill = $stmt->fetch();

if (!$skill) {
    echo json_encode(['success' => false, 'message' => 'Selected skill does not exist']);
    exit;
}

// Provider/trainer is the user who posted the skill
$provider_id = $skill['user_id'];

// Check double booking
$check = $pdo->prepare("SELECT id FROM bookings 
                        WHERE requester_id=? AND skill_id=? AND booking_date=? LIMIT 1");
$check->execute([$user_id, $skill_id, $date]);
if ($check->fetch()) {
    echo json_encode(['success' => false, 'message' => 'You already booked this skill on this date']);
    exit;
}

// Insert booking
$insert = $pdo->prepare("INSERT INTO bookings (requester_id, provider_id, skill_id, booking_date, notes) 
                         VALUES (?, ?, ?, ?, ?)");
$insert->execute([$user_id, $provider_id, $skill_id, $date, $notes]);

$booking_id = $pdo->lastInsertId();

echo json_encode([
    'success' => true,
    'message' => 'Booking created successfully',
    'booking_id' => $booking_id
]);