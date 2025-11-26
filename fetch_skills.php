<?php
header("Content-Type: application/json");

// Include DB connection
require_once __DIR__ . "/db.php";

try {
    // Fetch all approved skills with trainer information
    $stmt = $pdo->prepare("
        SELECT 
            ss.trainer_id,
            ss.skill_name,
            ss.platform,
            ss.description,
            u.fullname AS trainer_name,
            u.id AS trainer_id
        FROM shared_skills ss
        INNER JOIN users u ON ss.user_id = u.id
        WHERE ss.status = 'approved'
        ORDER BY ss.id DESC
    ");

    $stmt->execute();
    $skills = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        "success" => true,
        "skills" => $skills
    ]);
} catch (Exception $e) {
    // Return JSON error
    echo json_encode([
        "success" => false,
        "message" => "Error fetching skills: " . $e->getMessage()
    ]);
}