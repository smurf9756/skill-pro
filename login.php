<?php
session_start();
require_once "db.php";  // Make sure db.php defines $pdo as a PDO connection

$login_error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = strtolower(trim($_POST['email'] ?? ''));
    $password = $_POST['password'] ?? '';

    if ($email && $password) {
        // Fetch user by email
        $stmt = $pdo->prepare("SELECT id, fullname, email, password, role FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verify password
        if ($user && password_verify($password, $user['password'])) {
            // Set session variables
            $_SESSION['user_id']   = $user['id'];
            $_SESSION['fullname']  = $user['fullname'];
            $_SESSION['role']      = $user['role'];
            $_SESSION['email']     = $user['email'];

            // Redirect to dashboard
            header("Location: dashboard.php");
            exit;
        } else {
            $login_error = "Invalid email or password.";
        }
    } else {
        $login_error = "Please enter both email and password.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Community Skills Sharing</title>
    <link rel="stylesheet" href="com.css">
    <style>
        body {
            font-family: "Poppins", sans-serif;
            background-color: #f8f9fb;
            margin: 0;
        }

        nav {
            background-color: #004aad;
            color: white;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        nav h1 {
            margin: 0;
            font-size: 22px;
        }

        nav ul {
            list-style: none;
            display: flex;
            gap: 20px;
            margin: 0;
        }

        nav ul li a {
            color: white;
            text-decoration: none;
            font-weight: 500;
        }

        nav ul li a:hover,
        nav ul li a.active {
            color: #ffcc00;
        }

        .form-container {
            max-width: 400px;
            background: white;
            padding: 30px;
            margin: 60px auto;
            border-radius: 10px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #004aad;
        }

        label {
            display: block;
            margin-top: 15px;
            font-weight: 500;
        }

        input {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border-radius: 6px;
            border: 1px solid #ccc;
        }

        button {
            width: 100%;
            background-color: #004aad;
            color: white;
            padding: 12px;
            margin-top: 20px;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
        }

        button:hover {
            background-color: #003080;
        }

        .error {
            color: red;
            text-align: center;
            margin-top: 10px;
            font-weight: 600;
        }
    </style>
</head>

<body>
    <!-- Navigation -->
    <header>
        <nav>
            <h1>Community Skills Sharing</h1>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="skills.php">Skills</a></li>
                <li><a href="register.php">Register</a></li>
                <li><a class="active" href="login.php">Login</a></li>
            </ul>
        </nav>
    </header>

    <!-- Login Form -->
    <section class="form-container">
        <h2>Login</h2>
        <?php if (!empty($login_error)): ?>
            <p class="error"><?= htmlspecialchars($login_error) ?></p>
        <?php endif; ?>

        <form action="login.php" method="POST">
            <label>Email</label>
            <input type="email" name="email" placeholder="Enter email" required>

            <label>Password</label>
            <input type="password" name="password" placeholder="Enter password" required>

            <button type="submit">Login</button>
        </form>
        <p style="text-align:center; margin-top:10px;">
            Don't have an account? <a href="register.php">Register here</a>
        </p>

        <p style="text-align:center; margin-top:5px;">
            <a href="forgot_password.php">Forgot your password?</a>
        </p>

    </section>
</body>

</html>