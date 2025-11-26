<?php
include 'db.php';
header('Content-Type: application/json');

// only admin allowed
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Admin only']);
    exit;
}

$name = trim($_POST['name'] ?? '');
$desc = trim($_POST['description'] ?? '');
$trainer = trim($_POST['trainer'] ?? '');
$category = trim($_POST['category'] ?? '');
$price = trim($_POST['price'] ?? '');

if (!$name) {
    echo json_encode(['success' => false, 'message' => 'Name is required']);
    exit;
}

// handle file
$imageName = null;
if (!empty($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {
    $file = $_FILES['image'];
    if ($file['error'] !== UPLOAD_ERR_OK) {
        echo json_encode(['success' => false, 'message' => 'Upload error']);
        exit;
    }

    $allowed = ['image/jpeg', 'image/png', 'image/webp'];
    if (!in_array($file['type'], $allowed)) {
        echo json_encode(['success' => false, 'message' => 'Only JPG/PNG/WEBP allowed']);
        exit;
    }

    // sanitize original name and create unique filename
    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
    $imageName = time() . '_' . bin2hex(random_bytes(6)) . '.' . $ext;
    $targetDir = __DIR__ . '/../uploads/';
    if (!is_dir($targetDir)) mkdir($targetDir, 0755, true);

    $target = $targetDir . $imageName;
    if (!move_uploaded_file($file['tmp_name'], $target)) {
        echo json_encode(['success' => false, 'message' => 'Failed to move uploaded file']);
        exit;
    }
    // optionally: you can add image resizing here
}

try {
    $stmt = $pdo->prepare("INSERT INTO skills (skill_name, description, trainer, category, price, image) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$name, $desc, $trainer, $category, $price, $imageName]);
    echo json_encode(['success' => true, 'message' => 'Skill created']);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'DB error: ' . $e->getMessage()]);
}