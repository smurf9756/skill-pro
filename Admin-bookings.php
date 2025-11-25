<?php
include "db.php";

// APPROVE BOOKING
if (isset($_GET["approve"])) {
    $id = intval($_GET["approve"]);
    $pdo->prepare("UPDATE bookings SET status='approved' WHERE booking_id=?")->execute([$id]);
    header("Location: Admin-bookings.php");
    exit();
}

// CANCEL BOOKING
if (isset($_GET["cancel"])) {
    $id = intval($_GET["cancel"]);
    $pdo->prepare("UPDATE bookings SET status='cancelled' WHERE booking_id=?")->execute([$id]);
    header("Location: Admin-bookings.php");
    exit();
}

// Fetch all bookings (latest first)
$stmt = $pdo->query("
    SELECT b.*, 
           u.name AS student_name, 
           t.name AS trainer_name, 
           s.skill_name
    FROM bookings b
    JOIN users u ON b.user_id = u.id
    JOIN users t ON b.trainer_id = t.id
    JOIN skills s ON b.skill_id = s.skill_id
    ORDER BY b.booking_id DESC
");
$bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>

<head>
    <title>Manage Bookings</title>
    <link rel="stylesheet" href="com.css">
</head>

<body>
    <?php include "admin-dashboard.php"; ?>

    <div class="content">
        <h1>Manage Bookings</h1>

        <table border="1" cellpadding="10" width="100%">
            <thead>
                <tr>
                    <th>Student</th>
                    <th>Trainer</th>
                    <th>Skill</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Message</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($bookings as $b): ?>
                    <tr>
                        <td><?= htmlspecialchars($b["student_name"]) ?></td>
                        <td><?= htmlspecialchars($b["trainer_name"]) ?></td>
                        <td><?= htmlspecialchars($b["skill_name"]) ?></td>
                        <td><?= htmlspecialchars($b["session_date"]) ?></td>
                        <td><?= htmlspecialchars($b["session_time"]) ?></td>
                        <td><?= htmlspecialchars($b["message"]) ?></td>
                        <td><strong><?= $b["status"] ?></strong></td>
                        <td>
                            <?php if ($b["status"] === "pending"): ?>
                                <a href="?approve=<?= $b["booking_id"] ?>">✔ Approve</a> |
                                <a href="?cancel=<?= $b["booking_id"] ?>">❌ Cancel</a>
                            <?php else: ?>
                                action completed
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>

</html>