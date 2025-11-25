<?php
session_start();
include 'db.php';

if (!isset($_GET['skill_id'])) {
    die("Invalid Skill ID");
}

$skill_id = intval($_GET['skill_id']);

$stmt = $pdo->prepare("
    SELECT user_id AS skill_id, trainer_id, trainer_name, skill_name, platform, description
    FROM shared_skills
    WHERE user_id = ?
");
$stmt->execute([$skill_id]);
$skill = $stmt->fetch();

if (!$skill) {
    die("Skill not found");
}
?>
<!DOCTYPE html>


<head>
    <title>Book Session</title>
    <style>
        body {
            margin: 0;
            font-family: Poppins, sans-serif;
            background: linear-gradient(135deg, #fff, #f9fffaff);
            height: 100vh;
            display: grid;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .booking-card {
            width: 100%;
            max-width: 520px;
            background: #cf0d0dff;
            border-radius: 18px;
            padding: 30px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
            text-align: center;
            animation: fadeIn 0.8s ease;
        }

        h2 {
            color: #15088aff;
            margin-bottom: 5px;
            font-size: 26px;
            font-weight: 700;
            text-align: left;
        }

        p {
            color: #1013ccff;
            font-size: 15px;
            margin-bottom: 20px;
        }

        label {
            font-weight: 600;
            color: #097a9cff;
            display: block;
            text-align: left;
            margin-top: 12px;
            margin-bottom: 5px;
        }

        input[type="date"],
        input[type="time"],
        textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #d0d7de;
            border-radius: 8px;
            background: #f9fffaff;
            font-size: 14px;
            outline: none;
            transition: 0.25s;
        }

        input:focus,
        textarea:focus {
            border-color: #004aad;
            box-shadow: 0 0 0 3px rgba(0, 74, 173, 0.18);
        }

        textarea {
            height: 90px;
            resize: vertical;
        }

        button {
            width: 100%;
            padding: 14px;
            margin-top: 20px;
            border: none;
            background: #ad0096ff;
            color: #fff;
            font-size: 16px;
            font-weight: 600;
            border-radius: 10px;
            cursor: pointer;
            transition: 0.3s;
        }

        button:hover {
            background: #19c722ff;
            transform: translateY(-2px);
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(12px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>

<body>
    <div class="booking-container">


        <h2>Book: <?= htmlspecialchars($skill['skill_name']) ?></h2>
        <p>Trainer: <?= htmlspecialchars($skill['trainer_name']) ?></p>
    </div>
    <div class="booking-container">

        <div class="form-row">
            <form action="submit_booking.php" method="POST">

                <form action="submit_booking.php" method="POST">
                    <input type="hidden" name="skill_id" value="<?= $skill['skill_id'] ?>">
                    <input type="hidden" name="trainer_id" value="<?= $skill['trainer_id'] ?>">

                    <label>Select Date:</label>
                    <input type="date" name="session_date" required><br>

                    <label>Select Time:</label>
                    <input type="time" name="session_time" required><br>

                    <label>Message (optional):</label><br>
                    <textarea name="message"></textarea><br>

                    <button type="submit">Confirm Booking</button>
                </form>
        </div>
    </div>
</body>

</html>