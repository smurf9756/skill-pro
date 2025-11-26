<?php
include "db.php";

// APPROVE SKILL
if (isset($_GET["approve"])) {
    $id = intval($_GET["approve"]);
    $pdo->prepare("UPDATE shared_skills SET status='approved' WHERE user_id=?")->execute([$id]);
    header("Location: Admin-skills.php");
    exit();
}

// DECLINE SKILL
if (isset($_GET["decline"])) {
    $id = intval($_GET["decline"]);
    $pdo->prepare("UPDATE shared_skills SET status='declined' WHERE user_id=?")->execute([$id]);
    header("Location: Admin-skills.php");
    exit();
}

// DELETE SKILL
if (isset($_GET["delete"])) {
    $id = intval($_GET["delete"]);
    $pdo->prepare("DELETE FROM shared_skills WHERE user_id=?")->execute([$id]);
    header("Location: Admin-skills.php");
    exit();
}

$skills = $pdo->query("SELECT * FROM shared_skills ORDER BY user_id DESC")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>

<head>
    <title>Manage Skills</title>
    <link rel="stylesheet" href="com.css">
</head>

<body>

    <?php include "admin-dashboard.php"; ?>

    <div class="content">
        <h1>Manage Shared Skills</h1>

        <table border="1" cellpadding="10" width="100%">
            <thead>
                <tr>
                    <th>Trainer</th>
                    <th>Phone</th>
                    <th>Skill</th>
                    <th>Platform</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($skills as $s): ?>
                    <tr>
                        <td><?= htmlspecialchars($s["trainer_name"]) ?></td>
                        <td><?= htmlspecialchars($s["trainer_phone"]) ?></td>
                        <td><?= htmlspecialchars($s["skill_name"]) ?></td>
                        <td><?= htmlspecialchars($s["platform"]) ?></td>
                        <td><strong><?= $s["status"] ?></strong></td>

                        <td>
                            <a href="edit_skill.php?id=<?= $s['user_id'] ?>">✏ Edit</a> |
                            <a href="?delete=<?= $s['user_id'] ?>" onclick="return confirm('Delete this skill?')">🗑
                                Delete</a> |

                            <?php if ($s["status"] !== "approved"): ?>
                                <a href="?approve=<?= $s['user_id'] ?>">✔ Approve</a>
                            <?php endif; ?>

                            <?php if ($s["status"] !== "declined"): ?>
                                | <a href="?decline=<?= $s['user_id'] ?>">❌ Decline</a>
                            <?php endif; ?>
                        </td>

                    </tr>
                <?php endforeach; ?>
            </tbody>

        </table>
    </div>

</body>

</html>