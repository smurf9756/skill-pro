// Authentication System Files

// db.php
<?php
$pdo = new PDO("mysql:host=localhost;dbname=community", "root", "", [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
]);
?>

// session_check.php
<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}
?>

// register.php
<?php
session_start();
require 'db.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    $fullname = trim($_POST['fullname']);
    $email = strtolower(trim($_POST['email']));
    $password = $_POST['password'];

    if (!$fullname || !$email || !$password) {
        echo json_encode(['success' => false, 'message' => 'All fields required']);
        exit;
    }

    $stmt = $pdo->prepare("SELECT id FROM users WHERE email=?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        echo json_encode(['success' => false, 'message' => 'Email already exists']);
        exit;
    }

    $hashed = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO users(fullname,email,password,role) VALUES (?,?,?,?)");
    $stmt->execute([$fullname, $email, $hashed, 'user']);

    echo json_encode(['success' => true]);
    exit;
}
?>

// login.php
<?php
session_start();
require 'db.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    $email = strtolower(trim($_POST['email']));
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email=?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if (!$user || !password_verify($password, $user['password'])) {
        echo json_encode(['success' => false, 'message' => 'Invalid credentials']);
        exit;
    }

    $_SESSION['user'] = [
        'id' => $user['id'],
        'fullname' => $user['fullname'],
        'email' => $user['email'],
        'role' => $user['role']
    ];

    echo json_encode(['success' => true]);
    exit;
}
?>

// logout.php
<?php
session_start();
session_destroy();
header('Location: login.php');
exit;
?>