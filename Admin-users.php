<?php
include "db.php";

// DELETE USER
if (isset($_GET["delete"])) {
  $id = intval($_GET["delete"]);
  $pdo->prepare("DELETE FROM users WHERE id=?")->execute([$id]);
  header("Location: admin-users.php");
  exit();
}

// RESET PASSWORD
if (isset($_POST["reset_password"])) {
  $id = intval($_POST["user_id"]);
  $newPass = password_hash("123456", PASSWORD_DEFAULT);

  $stmt = $pdo->prepare("UPDATE users SET password=? WHERE id=?");
  $stmt->execute([$newPass, $id]);

  header("Location: admin-users.php?reset=success");
  exit();
}

// UPDATE USER
if (isset($_POST["update_user"])) {
  $id = intval($_POST["user_id"]);
  $name = $_POST["fullname"];
  $email = $_POST["email"];
  $phone = $_POST["phone"];
  $role = $_POST["role"];

  $stmt = $pdo->prepare("UPDATE users SET fullname=?, email=?, phone=?, role=? WHERE id=?");
  $stmt->execute([$name, $email, $phone, $role, $id]);

  header("Location: admin-users.php?updated=1");
  exit();
}

$users = $pdo->query("SELECT * FROM users ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>

<head>
    <title>Manage Users</title>
    <link rel="stylesheet" href="com.css">
</head>

<body>
    <?php include "admin-dashboard.php"; ?>

    <div class="content">
        <h1>Manage Users</h1>

        <table border="1" cellpadding="10" width="100%">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Role</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($users as $u): ?>
                <tr>
                    <td><?= htmlspecialchars($u["fullname"]) ?></td>
                    <td><?= htmlspecialchars($u["email"]) ?></td>
                    <td><?= htmlspecialchars($u["phone"]) ?></td>
                    <td><?= htmlspecialchars($u["role"]) ?></td>

                    <td>
                        <!-- Edit -->
                        <form action="" method="POST" style="display:inline-block;">
                            <input type="hidden" name="user_id" value="<?= $u["id"] ?>">
                            <input type="text" name="fullname" value="<?= htmlspecialchars($u["fullname"]) ?>" required>
                            <input type="email" name="email" value="<?= htmlspecialchars($u["email"]) ?>" required>
                            <input type="text" name="phone" value="<?= htmlspecialchars($u["phone"]) ?>">
                            <select name="role">
                                <option value="user" <?= $u["role"] == "user" ? "selected" : "" ?>>User</option>
                                <option value="trainer" <?= $u["role"] == "trainer" ? "selected" : "" ?>>Trainer
                                </option>
                                <option value="admin" <?= $u["role"] == "admin" ? "selected" : "" ?>>Admin</option>
                            </select>
                            <button type="submit" name="update_user">Save</button>
                        </form>

                        <!-- Reset password -->
                        <form action="" method="POST" style="display:inline-block;">
                            <input type="hidden" name="user_id" value="<?= $u["id"] ?>">
                            < </form>

                                <!-- Delete -->
                                <a href="?delete=<?= $u["id"] ?>" onclick="return confirm('Delete user?')">❌ Delete</a>

                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>

        </table>

    </div>

</body>

</html>