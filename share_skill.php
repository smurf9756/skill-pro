<?php
session_start();

// --- DATABASE CONNECTION ---
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "community_skills";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// --- HANDLE FORM SUBMISSION ---
$successMessage = "";
$errorMessage = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $trainerName = htmlspecialchars(trim($_POST["trainerName"]));
    $skillName = htmlspecialchars(trim($_POST["skillName"]));
    $platform = htmlspecialchars(trim($_POST["platform"]));
    $description = htmlspecialchars(trim($_POST["description"]));

    // FIXED: Correct phone field
    $tel = htmlspecialchars(trim($_POST["trainer_phone"]));

    // File upload
    $targetDir = "uploads/";
    if (!file_exists($targetDir)) {
        mkdir($targetDir, 0777, true);
    }

    $fileName = basename($_FILES["image"]["name"]);
    $newFileName = time() . "_" . $fileName;
    $targetFilePath = $targetDir . $newFileName;

    $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));
    $allowedTypes = ["jpg", "jpeg", "png", "gif"];

    if (!in_array($fileType, $allowedTypes)) {
        $errorMessage = "Only JPG, JPEG, PNG, and GIF files are allowed.";
    } else {
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFilePath)) {

            // UPDATED: Insert trainer_phone
            $stmt = $conn->prepare(
                "INSERT INTO shared_skills (trainer_name, trainer_phone, skill_name, platform, description, image_path, status) 
         VALUES (?, ?, ?, ?, ?, ?, 'pending')"
            );
            $stmt->bind_param("ssssss", $trainerName, $tel, $skillName, $platform, $description, $targetFilePath);

            if ($stmt->execute()) {
                $successMessage = "Your skill has been submitted successfully and is awaiting admin approval.";
            } else {
                $errorMessage = "Database error — could not save your skill.";
            }

            $stmt->close();
        } else {
            $errorMessage = "File upload failed. Please try again.";
        }
    }

    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Share a Skill - Community Skills Sharing</title>
    <link rel="stylesheet" href="com.css" />

    <style>
        body {
            font-family: "Poppins", sans-serif;
            margin: 0;
            background-color: #f5f7fa;
        }

        nav {
            background-color: #004aad;
            color: white;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        nav ul {
            list-style: none;
            display: flex;
            gap: 20px;
        }

        nav ul li a {
            color: white;
            text-decoration: none;
        }

        nav ul li a.active,
        nav ul li a:hover {
            color: #ffcc00;
        }

        .form-section {
            max-width: 700px;
            background: #fff;
            margin: 60px auto;
            padding: 30px 40px;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .form-section h2 {
            text-align: center;
            color: #004aad;
            margin-bottom: 25px;
        }

        .success {
            color: green;
            font-weight: bold;
            text-align: center;
            margin-bottom: 20px;
        }

        .error {
            color: red;
            font-weight: bold;
            text-align: center;
            margin-bottom: 20px;
        }

        input,
        select,
        textarea {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border-radius: 8px;
            border: 1px solid #ccc;
        }

        .btn-submit {
            width: 100%;
            padding: 12px;
            background-color: #004aad;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
        }

        .btn-submit:hover {
            background-color: #003480;
        }

        .preview img {
            max-width: 200px;
            border-radius: 10px;
            display: block;
            margin: auto;
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

    <header>
        <nav>
            <h1>Community Skills Sharing</h1>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="skills.php">Skills</a></li>
                <li><a href="share_skill.php" class="active">Share a Skill</a></li>
                <li><a href="dashboard.php">Dashboard</a></li>
            </ul>
        </nav>
    </header>

    <section class="form-section">
        <h2>Share Your Skill</h2>

        <?php if ($successMessage): ?>
            <p class="success"><?= $successMessage ?></p>
            <script>
                setTimeout(() => {
                    window.location.href = "dashboard.php";
                }, 2500);
            </script>
        <?php endif; ?>

        <?php if ($errorMessage): ?>
            <p class="error"><?= $errorMessage ?></p>
        <?php endif; ?>
        <form action="share_skill.php" method="POST" enctype="multipart/form-data">

            <label>Trainer Full Name</label>
            <input type="text" name="trainerName" required>

            <label>Skill Name</label>
            <input type="text" name="skillName" required>

            <label>Preferred Teaching Platform</label>
            <select name="platform" required>
                <option value="">-- Select platform --</option>
                <option value="Zoom">Zoom</option>
                <option value="Google Meet">Google Meet</option>
                <option value="Physically">Physically</option>
                <option value="Other">Other</option>
            </select>

            <label>Trainer Phone Number (used for WhatsApp)</label>
            <input type="text" name="trainer_phone" placeholder="254700000000" required>

            <label>Skill Description</label>
            <textarea name="description" rows="4" required></textarea>

            <label>Upload Skill Image</label>
            <input type="file" name="image" accept="image/*" required>

            <button type="submit" class="btn-submit">Submit Skill</button>
        </form>

    </section>

    <footer>
        <p>&copy; 2025 Community Skills Sharing |for more info contact: +254740767140 or email:
            nyagasamuel342@gmail.com
            together we rise fo a better tommorrow</p>
    </footer>

</body>

</html>