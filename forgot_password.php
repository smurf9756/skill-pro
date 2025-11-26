<?php
session_start();
require_once "db.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = strtolower(trim($_POST["email"]));

    // Check if email exists
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user) {
        // Generate reset code
        $code = rand(100000, 999999);

        // Save code to DB
        $pdo->prepare("UPDATE users SET reset_code = ? WHERE email = ?")
            ->execute([$code, $email]);

        // For localhost: show code on screen (since no email server)
        $message = "A password reset code has been generated. Use this code: <strong>$code</strong><br>
                    <a href='reset_password.php?email=$email'>Click here to reset password</a>";
    } else {
        $message = "Email not found in the system.";
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Forgot Password</title>
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
        <h2>Reset Password</h2>

        <?php if ($message): ?>
            <p><?= $message ?></p>
        <?php endif; ?>

        <form method="POST">
            <label>Enter your email</label>
            <input type="email" name="email" required style="width:100%; padding:10px; margin-top:5px;">
            <button style="width:100%; padding:10px; margin-top:15px; background:#004aad; color:white;">
                Send Reset Code
            </button>
        </form>
    </div>

</body>

</html>