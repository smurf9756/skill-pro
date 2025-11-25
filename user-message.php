<?php
session_start();
include "db.php";

$userID = $_SESSION["user_id"];

$stmt = $pdo->prepare("SELECT message, sent_at FROM messages WHERE receiver_id=? ORDER BY id DESC");
$stmt->execute([$userID]);
$messages = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>

<head>
    <title>Your Messages</title>
</head>

<body>

    <h2>Your Messages</h2>

    <?php foreach ($messages as $m): ?>
        <div class="message-box">
            <p><?= htmlspecialchars($m["message"]) ?></p>
            <small>Sent: <?= $m["sent_at"] ?></small>
        </div>
    <?php endforeach; ?>

</body>

</html>