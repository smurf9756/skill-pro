<?php
session_start();
require_once __DIR__ . "/db.php";




// Fetch totals from database
$totalUsers = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
$totalSkills = $pdo->query("SELECT COUNT(*) FROM skills")->fetchColumn();
$totalBookings = $pdo->query("SELECT COUNT(*) FROM bookings")->fetchColumn();
$activeTrainers = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'trainer'")->fetchColumn();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Dashboard - Community Skills Sharing</title>
    <link rel="stylesheet" href="com.css" />
    <style>
        body {
            display: flex;
            font-family: "Poppins", sans-serif;
            margin: 0;
        }

        .sidebar {
            width: 220px;
            background: #007bff;
            color: #fff;
            height: 100vh;
            padding: 20px;
        }

        .sidebar h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .sidebar a {
            display: block;
            color: #fff;
            padding: 10px;
            text-decoration: none;
            border-radius: 8px;
            margin-bottom: 10px;
        }

        .sidebar a:hover {
            background: #0056b3;
        }

        .content {
            flex: 1;
            padding: 30px;
            background: #f8f9fb;
        }

        .card {
            background: #fff;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin: 10px;
            text-align: center;
            width: 180px;
        }

        .dashboard {
            display: flex;
            flex-wrap: wrap;
        }
    </style>
</head>

<body>
    <div class="sidebar">
        <h2>Admin Panel</h2>
        <a href="admin-dashboard.php">Dashboard</a>
        <a href="Admin-users.php">Manage Users</a>
        <a href="Admin-skills.php">Manage Skills</a>

        <a href="dashboard.php">Users Panel</a>
        <a href="Admin-login.php">Logout</a>
    </div>

    <div class="content">
        <h1>Welcome, Admin</h1>
        <div class="dashboard">
            <div class="card">
                <h3>Total Users</h3>
                <p><?= $totalUsers; ?></p>
            </div>
            <div class="card">
                <h3>Total Skills</h3>
                <p><?= $totalSkills; ?></p>
            </div>

            <div class="card">
                <h3>Active Trainers</h3>
                <p><?= $activeTrainers; ?></p>
            </div>
        </div>
    </div>
</body>

</html>