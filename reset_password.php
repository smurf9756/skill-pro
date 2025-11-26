<?php
session_start();
require_once "db.php";

$email = $_GET["email"] ?? "";
$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST["email"];
    $code = $_POST["code"];
    $pass = $_POST["password"];

    // Validate code
    $stmt = $pdo->prepare("SELECT reset_code FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $row = $stmt->fetch();

    if ($row && $row["reset_code"] == $code) {
        // Update password
        $hash = password_hash($pass, PASSWORD_DEFAULT);

        $pdo->prepare("UPDATE users SET password = ?, reset_code = NULL WHERE email = ?")
            ->execute([$hash, $email]);

        $success = "Password successfully reset. <a href='login.php'>Login now</a>";
    } else {
        $error = "Invalid reset code.";
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Reset Password</title>
    <style>
        body {
            font-family: Poppins;
            background: #f2f4f8;
        }

        .box {
            max-width: 400px;
            margin: 60px auto;
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, .1);
        }
    </style>
</head>

<body>

    <div class="box">

        <h2>Create New Password</h2>

        <?php if ($error): ?><p style="color:red;"><?= $error ?></p><?php endif; ?>
        <?php if ($success): ?><p style="color:green;"><?= $success ?></p><?php endif; ?>

        <form method="POST">

            <input type="hidden" name="email" value="<?= htmlspecialchars($email) ?>">

            <label>Reset Code</label>
            <input type="text" name="code" required style="width:100%; padding:10px; margin-top:5px;">

            <label>New Password</label>
            <input type="password" name="password" required style="width:100%; padding:10px; margin-top:5px;">

            <button style="width:100%; padding:10px; margin-top:15px; background:#004aad; color:white;">
                Reset Password
            </button>
        </form>

    </div>

</body>

</html>