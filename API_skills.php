<?php
// php/api_skills.php
require_once __DIR__ . '/db.php';
header('Content-Type: application/json');

try {
    $stmt = $pdo->query("SELECT skill_id, skill_name FROM skills ORDER BY skill_name ASC");
    $skills = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($skills);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to load skills']);
}
