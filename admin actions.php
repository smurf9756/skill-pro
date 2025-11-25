<?php
session_start();
include 'db.php';

header('Content-Type: application/json');

// Ensure admin access
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Admin only']);
    exit;
}

$action = $_REQUEST['action'] ?? '';

try {
    if ($action === 'listSkills') {
        $stmt = $pdo->query("SELECT skill_id, skill_name, trainer, category, price, image FROM skills ORDER BY created_at DESC");
        $skills = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['success' => true, 'skills' => $skills]);
        exit;
    }

    if ($action === 'deleteSkill') {
        $id = intval($_POST['id'] ?? 0);
        if (!$id) throw new Exception('Missing id');

        // Delete image if exists
        $s = $pdo->prepare("SELECT image FROM skills WHERE skill_id = ?");
        $s->execute([$id]);
        $row = $s->fetch(PDO::FETCH_ASSOC);
        if ($row && $row['image']) {
            $file = __DIR__ . '/../uploads/' . $row['image'];
            if (is_file($file)) @unlink($file);
        }

        $del = $pdo->prepare("DELETE FROM skills WHERE skill_id = ?");
        $del->execute([$id]);

        echo json_encode(['success' => true, 'message' => 'Skill deleted successfully']);
        exit;
    }

    if ($action === 'listUsers') {
        $stmt = $pdo->query("SELECT id, fullname, email, phone, role, created_at FROM users ORDER BY created_at DESC");
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['success' => true, 'users' => $users]);
        exit;
    }

    if ($action === 'deleteUser') {
        $id = intval($_POST['id'] ?? 0);
        if (!$id) throw new Exception('Missing id');

        $del = $pdo->prepare("DELETE FROM users WHERE id = ?");
        $del->execute([$id]);

        echo json_encode(['success' => true, 'message' => 'User deleted successfully']);
        exit;
    }

    echo json_encode(['success' => false, 'message' => 'Unknown action']);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>
