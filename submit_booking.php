<?php
session_start();
include "db.php";

if (!isset($_SESSION['user_id'])) {
    die("User not logged in");
}

$user_id = $_SESSION['user_id'];   // Student making the booking
$skill_id = intval($_POST['skill_id']);
$trainer_id = intval($_POST['trainer_id']);
$session_date = $_POST['session_date'];
$session_time = $_POST['session_time'];
$message = $_POST['message'] ?? null;


// INSERT booking
$stmt = $pdo->prepare("
    INSERT INTO bookings 
        (user_id, skill_id, requester_id, trainer_id, session_date, session_time, message, status)
    VALUES 
        (?, ?, ?, ?, ?, ?, ?, 'pending')
");

$stmt->execute([
    $user_id,        // must exist in users.id
    $skill_id,
    $user_id,        // requester_id = student
    $trainer_id,
    $session_date,
    $session_time,
    $message
]);

header("Location: Admin-bookings.php?submitted=1");
exit;
