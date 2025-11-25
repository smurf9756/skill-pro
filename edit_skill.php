<?php
include "db.php";

if (!isset($_GET["id"])) {
    die("Invalid request.");
}

$id = intval($_GET["id"]);

// Fetch skill data
$stmt = $pdo->prepare("SELECT * FROM shared_skills WHERE user_id=? LIMIT 1");
$stmt->execute([$id]);
$skill = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$skill) {
    die("Skill not found.");
}

// UPDATE skill
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $trainer_name = $_POST["trainer_name"];
    $skill_name = $_POST["skill_name"];
    $platform = $_POST["platform"];
    $whatsapp = $_POST["WhatsApp_number"];
    $description = $_POST["description"];

    $image_path = $skill["image_path"]; // keep old image

    // Handle image upload
    if (!empty($_FILES["image"]["name"])) {
        $targetDir = "uploads/";
        $fileName = time() . "_" . basename($_FILES["image"]["name"]);
        $targetFile = $targetDir . $fileName;

        if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
            $image_path = $targetFile; // update image path
        }
    }

    // Update DB
    $update = $pdo->prepare("
        UPDATE shared_skills 
        SET trainer_name=?, skill_name=?, platform=?, WhatsApp_number=?, description=?, image_path=?
        WHERE user_id=?
    ");

    $update->execute([
        $trainer_name,
        $skill_name,
        $platform,
        $whatsapp,
        $description,
        $image_path,
        $id
    ]);

    header("Location: Admin-skills.php?updated=1");
    exit();
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Edit Skill</title>
    <link rel="stylesheet" href="com.css">
    <style>
        .container {
            max-width: 700px;
            margin: 30px auto;
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        }

        input,
        textarea,
        select {
            width: 100%;
            padding: 12px;
            margin-top: 8px;
            margin-bottom: 15px;
            border: 1px solid #aaa;
            border-radius: 6px;
        }

        label {
            font-weight: bold;
        }

        button {
            padding: 12px 20px;
            background: #004aad;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }

        button:hover {
            background: #003580;
        }

        img {
            width: 100%;
            max-height: 200px;
            object-fit: cover;
            border-radius: 8px;
            margin-bottom: 15px;
        }
    </style>
</head>

<body>

    <div class="container">
        <h2>Edit Skill</h2>

        <form method="POST" enctype="multipart/form-data">

            <label>Trainer Name</label>
            <input type="text" name="trainer_name" value="<?= htmlspecialchars($skill['trainer_name']) ?>" required>

            <label>Skill Name</label>
            <input type="text" name="skill_name" value="<?= htmlspecialchars($skill['skill_name']) ?>" required>

            <label>Platform</label>
            <input type="text" name="platform" value="<?= htmlspecialchars($skill['platform']) ?>" required>

            <label>WhatsApp Number</label>
            <input type="text" name="WhatsApp_number" value="<?= htmlspecialchars($skill['WhatsApp_number']) ?>" required>

            <label>Description</label>
            <textarea name="description" rows="5" required><?= htmlspecialchars($skill['description']) ?></textarea>

            <label>Current Image</label><br>
            <img src="<?= $skill['image_path'] ?>" alt="Skill Image">

            <label>Upload New Image (optional)</label>
            <input type="file" name="image" accept="image/*">

            <button type="submit">Save Changes</button>
        </form>

        <br>
        <a href="Admin-skills.php" style="color:#004aad;">← Back to Skills</a>
    </div>

</body>

</html>
-