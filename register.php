<?php
include 'db.php';

$register_error = "";
$register_success = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $fullname = trim($_POST['fullname'] ?? '');
    $email = strtolower(trim($_POST['email'] ?? ''));
    $password = $_POST['password'] ?? '';
    $phone = trim($_POST['phone'] ?? '');
    $skill = trim($_POST['skill'] ?? '');

    if (!$fullname || !$email || !$password || !$phone) {
        $register_error = "All fields except 'skill' are required.";
    } else {
        // Check if user already exists
        $check = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $check->execute([$email]);

        if ($check->rowCount() > 0) {
            $register_error = "An account with this email already exists.";
        } else {
            // Hash the password
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Insert user
            $stmt = $pdo->prepare("
                INSERT INTO users (fullname, email, password, phone, skill, role) 
                VALUES (?, ?, ?, ?, ?, 'user')
            ");

            if ($stmt->execute([$fullname, $email, $hashedPassword, $phone, $skill])) {
                $register_success = "Registration successful! Redirecting to login...";
                header("refresh:2; url=login.php");
            } else {
                $register_error = "Registration failed. Please try again.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Register - Community Skills Sharing</title>
    <link rel="stylesheet" href="com.css" />

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
        margin: 50px auto;
        border-radius: 10px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
    }

    .form-container h2 {
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
        font-weight: 600;
        margin-top: 10px;
    }

    .success {
        color: green;
        text-align: center;
        font-weight: 600;
        margin-top: 10px;
    }

    footer {
        text-align: center;
        padding: 20px;
        background: #004aad;
        color: white;
        margin-top: 40px;
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
                <li><a href="register.php" class="active">Register</a></li>
                <li><a href="login.php">Login</a></li>
            </ul>
        </nav>
    </header>

    <!-- Registration Form -->
    <section class="form-container">
        <h2>Create an Account</h2>

        <?php if (!empty($register_error)): ?>
        <p class="error"><?= htmlspecialchars($register_error) ?></p>
        <?php endif; ?>

        <?php if (!empty($register_success)): ?>
        <p class="success"><?= htmlspecialchars($register_success) ?></p>
        <?php endif; ?>

        <form action="register.php" method="POST">

            <label>Full Name</label>
            <input type="text" name="fullname" placeholder="Enter full name" required />

            <label>Email</label>
            <input type="email" name="email" placeholder="Enter email" required />

            <label>Password</label>
            <input type="password" name="password" placeholder="Enter password" required />

            <label>Phone</label>
            <input type="tel" name="phone" placeholder="Enter phone number" required />

            <label>Skill (Optional)</label>
            <input type="text" name="skill" placeholder="Enter your skill" />

            <button type="submit">Register</button>
        </form>

        <p style="text-align:center; margin-top:15px;">
            Already have an account? <a href="login.php">Login here</a>
        </p>
    </section>

    <footer>
        <p>&copy; 2025 Community Skills Sharing | Powered by Samuel Nyaga</p>
    </footer>

</body>

</html>