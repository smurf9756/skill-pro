<?php

include "db.php";

$id = $_GET["id"];
$stmt = $pdo->prepare("SELECT fullname, email, role FROM users WHERE id=?");
$stmt->execute([$id]);
$user = $stmt->fetch();

if (!$user) die("User not found.");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $fullname = trim($_POST["fullname"]);
    $email = trim($_POST["email"]);
    $role = trim($_POST["role"]);

    $update = $pdo->prepare("UPDATE users SET fullname=?, email=?, role=? WHERE id=?");
    $update->execute([$fullname, $email, $role, $id]);

    header("Location: admin-users.php");
    exit();
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Edit User</title>
</head>

<body>


    <div class="content">
        <h2>Edit User</h2>

        <form method="POST">
            <label>Full Name</label>
            <input type="text" name="fullname" value="<?= $user['fullname'] ?>" required>

            <label>Email</label>
            <input type="email" name="email" value="<?= $user['email'] ?>" required>

            <label>Role</label>
            <select name="role">
                <option value="user" <?= $user["role"] === "user" ? "selected" : "" ?>>User</option>
                <option value="admin" <?= $user["role"] === "admin" ? "selected" : "" ?>>Admin</option>
            </select>

            <button type="submit">Save Changes</button>
        </form>
    </div>
</body>

</html>