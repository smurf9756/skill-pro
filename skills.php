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
            font-family: "Poppins";
            background: #f5f7fa;
            margin: 0;
        }

        nav {
            background: #004aad;
            padding: 15px 30px;
            color: #fff;
            display: flex;
            justify-content: space-between;
        }

        nav h1 {
            margin: 0;
            font-size: 22px;
        }

        nav ul {
            display: flex;
            gap: 20px;
            list-style: none;
            margin: 0;
        }

        nav a {
            color: #fff;
            text-decoration: none;
        }

        .skills-section {
            padding: 40px 20px;
            text-align: center;
        }

        .skills-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 25px;
            max-width: 1300px;
            margin: auto;
        }

        .skill-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            padding-bottom: 20px;
        }

        .skill-img {
            height: 180px;
            background-size: cover;
            background-position: center;
        }

        .skill-content {
            padding: 20px;
        }

        .btn {
            background: #25D366;
            color: #fff;
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
        }

        .review-section {
            background: #eef3ff;
            padding: 15px;
            border-radius: 10px;
            margin-top: 15px;
        }

        .review-section input,
        .review-section select,
        .review-section textarea {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border-radius: 6px;
            border: 1px solid #ccc;
        }

        .reviews-list {
            margin-top: 12px;
            text-align: left;
        }

        footer {
            background: #004aad;
            padding: 20px;
            text-align: center;
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
                <li><a href="dashboard.php">dashboard</a></li>
                <li><a href="index.php">Home</a></li>
            </ul>
        </nav>
    </header>

    <section class="skills-section">
        <h2>Available Skills</h2>
        <a href="share_skill.php" class="btn" style="background:#ffcc00; color:#004aad;">Are you a Trainer? Share a
            Skill</a>

        <div class="skills-grid">

            <?php foreach ($skills as $s): ?>
                <div class="skill-card">

                    <!-- IMAGE -->
                    <div class="skill-img"
                        style="background-image:url('<?= htmlspecialchars("./" . ($s['image_path'] ?: "default.jpg")) ?>')">
                    </div>

                    <div class="skill-content">
                        <h3><?= htmlspecialchars($s["skill_name"]) ?></h3>
                        <p><strong>Trainer:</strong> <?= htmlspecialchars($s["trainer_name"]) ?></p>
                        <p><strong>Platform:</strong> <?= htmlspecialchars($s["platform"]) ?></p>
                        <p><?= nl2br(htmlspecialchars($s["description"])) ?></p>

                        <!-- WHATSAPP BUTTON -->
                        <?php
                        $phone = preg_replace('/\D/', '', $s["trainer_phone"]);
                        $msg = urlencode("Hello, I found your skill '" . $s["skill_name"] . "' on the Community Skills Sharing Platform. I'd like to book a session.");
                        ?>
                        <a class="btn" href="https://wa.me/<?= $phone ?>?text=<?= $msg ?>" target="_blank">WhatsApp
                            Trainer</a>

                        <!-- AVERAGE RATING DISPLAY -->
                        <div id="avg-rating-<?= $s['skill_name'] ?>"
                            style="margin-top:10px; font-weight:bold; color:#004aad;">
                            Loading rating...
                        </div>

                        <!-- REVIEW SECTION -->
                        <!-- REVIEW SECTION -->
                        <div class="review-section">
                            <h4>Rate & Review</h4>

                            <input type="text" class="review-name" placeholder="Your Name">

                            <select class="review-rating">
                                <option value="">Rating</option>
                                <option value="1">⭐</option>
                                <option value="2">⭐⭐</option>
                                <option value="3">⭐⭐⭐</option>
                                <option value="4">⭐⭐⭐⭐</option>
                                <option value="5">⭐⭐⭐⭐⭐</option>
                            </select>

                            <textarea class="review-text" placeholder="Write your review..."></textarea>

                            <button class="btn submitReviews" data-skillname="<?= htmlspecialchars($s['skill_name']) ?>">
                                Submit Review
                            </button>
                        </div>

                        <!-- REVIEWS LIST (MOVED OUTSIDE) -->
                        <div class="reviews-list" id="reviews-<?= htmlspecialchars($s['skill_name']) ?>"></div>

                    </div>
                </div> <!-- END .skill-content -->
        </div> <!-- END .skill-card -->
    <?php endforeach; ?>


    </div>
    </section>

    <footer>
        <p>&copy; 2025 Community Skills Sharing |for more info contact: +254740767140 or email:
            nyagasamuel342@gmail.com
            together we rise fo a better tommorrow</p>
    </footer>

    <script>
        // ========== LOAD REVIEWS ==========
        function loadReviews(skillName) {
            const list = document.getElementById("reviews-" + skillName);

            fetch("fetch_reviews.php?skill_name=" + encodeURIComponent(skillName))
                .then(res => res.json())
                .then(data => {
                    list.innerHTML = "";
                    if (!data.length) {
                        list.innerHTML = "<em>No reviews yet</em>";
                        return;
                    }

                    data.forEach(r => {
                        list.innerHTML += `
                <div style="background:#fff; padding:10px; border-radius:8px; margin-bottom:8px;">
                    <strong>${r.username}</strong> — ${"⭐".repeat(r.rating)}
                    <p>${r.review}</p>
                    <small>${r.timestamp}</small>
                </div>
            `;
                    });
                });
        }

        // ========== SUBMIT REVIEW ==========
        document.addEventListener("click", function(e) {
            if (e.target.classList.contains("submitReviews")) {

                const skillName = e.target.dataset.skillname;
                const card = e.target.closest(".review-section");

                const username = card.querySelector(".review-name").value.trim();
                const rating = card.querySelector(".review-rating").value;
                const review = card.querySelector(".review-text").value.trim();

                if (!username || !rating || !review) {
                    alert("Please fill all fields.");
                    return;
                }

                fetch("submit_reviews.php", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/x-www-form-urlencoded"
                        },
                        body: "skill_name=" + encodeURIComponent(skillName) +
                            "&username=" + encodeURIComponent(username) +
                            "&rating=" + rating +
                            "&review=" + encodeURIComponent(review)
                    })
                    .then(res => res.json())
                    .then(d => {
                        if (d.success) {
                            loadReviews(skillName);
                            loadAverageRating(skillName);

                            card.querySelector(".review-name").value = "";
                            card.querySelector(".review-rating").value = "";
                            card.querySelector(".review-text").value = "";
                        }
                    });
            }
        });

        // ========== AVERAGE RATING ==========
        function loadAverageRating(skillName) {
            fetch("get_average_rating.php?skill_name=" + encodeURIComponent(skillName))
                .then(res => res.json())
                .then(d => {
                    const box = document.getElementById("avg-rating-" + skillName);

                    if (d.total_reviews == 0) {
                        box.innerHTML = "⭐ No reviews yet";
                        return;
                    }

                    let stars = "";
                    for (let i = 1; i <= 5; i++) {
                        stars += i <= d.avg_rating ? "⭐" : "☆";
                    }

                    box.innerHTML = `${stars} (${d.avg_rating}) — ${d.total_reviews} review(s)`;
                });
        }

        // ========== INITIAL LOAD ==========
        document.addEventListener("DOMContentLoaded", () => {
            document.querySelectorAll(".submitReviews").forEach(btn => {
                const skillName = btn.dataset.skillname;
                loadReviews(skillName);
                loadAverageRating(skillName);
            });
        });
    </script>

</body>

</html>