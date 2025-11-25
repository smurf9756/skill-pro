<?php
session_start();
include 'db.php';

// Fetch all approved shared skills
$skills = $pdo->query("
    SELECT user_id AS skill_id, trainer_id, trainer_name, trainer_phone, skill_name, platform, description, image_path, created_at
    FROM shared_skills
    WHERE status = 'approved'
    ORDER BY user_id DESC
")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>

<head>
    <title>Skills</title>

    <link rel="stylesheet" href="com.css">
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

        nav h1 {
            font-size: 22px;
            margin: 0;
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

        .skills-section {
            text-align: center;
            padding: 40px 20px;
        }

        .skills-section h2 {
            font-size: 28px;
            color: #004aad;
            margin-bottom: 20px;
        }

        .share-btn {
            background-color: #ffcc00;
            color: #004aad;
            font-weight: 600;
            padding: 12px 25px;
            border-radius: 8px;
            text-decoration: none;
            margin-bottom: 30px;
            display: inline-block;
        }

        .skills-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 25px;
            max-width: 1300px;
            margin: 0 auto;
        }

        .skill-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: .3s;
        }

        .skill-card:hover {
            transform: translateY(-8px);
        }

        .skill-img {
            height: 180px;
            background-size: cover;
            background-position: center;
        }

        .skill-content {
            padding: 20px;
            text-align: center;
        }

        .skill-content h3 {
            color: #004aad;
        }

        .btn {
            background-color: #25D366;
            /* WhatsApp green */
            color: #fff;
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            margin-top: 15px;
            display: inline-block;
            font-weight: 600;
        }

        .btn:hover {
            opacity: 0.9;
        }

        footer {
            text-align: center;
            padding: 20px;
            background: #004aad;
            color: white;
        }
    </style>
</head>

<body>
    <header>
        <nav>
            <h1>Community Skills Sharing</h1>
            <ul>
                <li><a href="dashboard.php">dashboard</a></li>
                <li><a href="index.php">Home</a></li>
            </ul>
        </nav>
    </header>

    <section class="skills-section">
        <h2>Available Skills</h2>
        <a href="share_skill.php" class="share-btn">Are you a Trainer? Share a Skill</a>

        <div class="skills-grid">
            <?php foreach ($skills as $s): ?>
                <div class="skill-card">

                    <div class="skill-img"
                        style="background-image:url('<?= htmlspecialchars("./" . ($s['image_path'] ?: "default.jpg")) ?>')">
                    </div>

                    <div class="skill-content">
                        <h3><?= htmlspecialchars($s["skill_name"]) ?></h3>
                        <p><strong>Trainer:</strong> <?= htmlspecialchars($s["trainer_name"]) ?></p>
                        <p><strong>Platform:</strong> <?= htmlspecialchars($s["platform"]) ?></p>
                        <p><?= nl2br(htmlspecialchars($s["description"])) ?></p>

                        <?php
                        // ---------- ADDED: Create WhatsApp booking link ----------

                        $phone = preg_replace('/\D/', '', $s["trainer_phone"]);
                        $msg = urlencode("Hello, are you still offering the skill you posted on the community skills sharing platform?? if yes I would like to book your skill!! awaiting your response.thank you THE SKIll: " . $s["skill_name"]);
                        $whatsappLink = "https://wa.me/$phone?text=$msg";
                        ?>

                        <a href="<?= $whatsappLink ?>" target="_blank" class="btn" style="background:#25D366;">
                            WhatsApp Trainer
                        </a>


                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- ===================== REVIEW SYSTEM INSIDE EACH SKILL ===================== -->
    <!-- REVIEW SYSTEM -->
    <div class="review-section">
        <h4>Rate & Review</h4> <input type="text" class="review-name" placeholder="Your Name"> <select
            class="review-rating">
            <option value="">Rating</option>
            <option value="1">⭐</option>
            <option value="2">⭐⭐</option>
            <option value="3">⭐⭐⭐</option>
            <option value="4">⭐⭐⭐⭐</option>
            <option value="5">⭐⭐⭐⭐⭐</option>
        </select> <textarea class="review-text" placeholder="Your review..."></textarea> <button
            class="btn submitReview" data-skill="<?= $s['skill_id'] ?>">Submit</button>
        <div class="reviews-list" id="reviews-<?= $s['skill_id'] ?>"></div>
    </div>
    </section>
    <foote </footer>
        <script>
            function getReviews(skillID) {
                const saved = localStorage.getItem("reviews_" + skillID);
                return saved ? JSON.parse(saved) : [];
            }

            function saveReviews(skillID, arr) {
                localStorage.setItem("reviews_" + skillID, JSON.stringify(arr));
            }

            function loadReviews(skillID) {
                const list = document.getElementById("reviews-" + skillID);
                const reviews = getReviews(skillID);
                list.innerHTML = "";
                reviews.forEach(r => {
                    list.innerHTML += `
            <div class="review">
                <strong>${r.name}</strong> — ${"⭐".repeat(r.rating)}
                <p>${r.text}</p>
                <small>${r.time}</small>
            </div>
        `;
                });
            }

            document.addEventListener("click", function(e) {
                if (e.target.classList.contains("submitReview")) {
                    const skillID = e.target.getAttribute("data-skill");
                    const card = e.target.closest(".review-section");
                    const name = card.querySelector(".review-name").value.trim();
                    const rating = card.querySelector(".review-rating").value;
                    const text = card.querySelector(".review-text").value.trim();

                    if (!name || !rating || !text) {
                        alert("Please fill all fields to submit a review.");
                        return;
                    }

                    const reviews = getReviews(skillID);
                    reviews.push({
                        name: name,
                        rating: rating,
                        text: text,
                        time: new Date().toLocaleString()
                    });
                    saveReviews(skillID, reviews);
                    loadReviews(skillID);

                    card.querySelector(".review-name").value = "";
                    card.querySelector(".review-rating").value = "";
                    card.querySelector(".review-text").value = "";
                }
            });

            document.addEventListener("DOMContentLoaded", () => {
                document.querySelectorAll(".submitReview").forEach(btn => {
                    loadReviews(btn.dataset.skill);
                });
            });
        </script>

        <footer>
            <p>&copy; 2025 Community Skills Sharing Platform | Powered by Samuel Nyaga</p>
        </footer>

</body>

</html>