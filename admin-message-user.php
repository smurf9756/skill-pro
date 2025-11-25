<?php

include "db.php";

$id = $_GET["id"];

$stmt = $pdo->prepare("SELECT fullname FROM users WHERE id=?");
$stmt->execute([$id]);
$user = $stmt->fetch();
if (!$user) die("User not found.");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $msg = trim($_POST["message"]);
    $admin_id = $_SESSION["admin_id"];

    $send = $pdo->prepare("INSERT INTO messages (sender_id, receiver_id, message) VALUES (?,?,?)");
    $send->execute([$admin_id, $id, $msg]);

    echo "<script>alert('Message sent!'); window.location='admin-users.php';</script>";
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Send Message</title>
</head>

<body>
    <?php include 'admin_nav.php'; ?>

    <div class="content">
        <h2>Message to <?= $user['fullname'] ?></h2>

        <form method="POST">
            <textarea name="message" rows="5" placeholder="Enter message..." required></textarea>
            <button type="submit">Send</button>
        </form>
    </div>
</body>

</html>