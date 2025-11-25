<?php
session_start();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Dashboard - Community Skills Sharing</title>
    <link rel="stylesheet" href="css/com.css" />
    <style>
        body {
            font-family: "Poppins", sans-serif;
            margin: 0;
            background: #200bdb;
        }

        /* Sidebar */
        .sidebar {
            width: 220px;
            position: fixed;
            left: 0;
            top: 0;
            bottom: 0;
            background: linear-gradient(180deg, #2e7d32, #1b5e20);
            color: #fff;
            padding: 18px;
        }

        .sidebar h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .sidebar li {
            list-style: none;
        }

        .sidebar a {
            display: block;
            color: #fff;
            padding: 8px 12px;
            border-radius: 8px;
            text-decoration: none;
            margin-bottom: 6px;
            transition: background 0.3s;
        }

        .sidebar a:hover,
        .sidebar a.active {
            background: rgba(255, 255, 255, 0.2);
        }

        /* Main Area */
        .main {
            margin-left: 240px;
            padding: 20px;
        }

        .hero {
            background: url("images/skills-banner.jpg") center/cover no-repeat;
            color: #fff;
            border-radius: 12px;
            padding: 60px 40px;
            text-align: center;
            margin-bottom: 30px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .hero h2 {
            font-size: 28px;
            margin-bottom: 10px;
        }

        .hero p {
            font-size: 16px;
        }

        .skills-section {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 20px;
        }

        .skill-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: transform 0.3s;
        }

        .skill-card:hover {
            transform: translateY(-6px);
        }

        .skill-card img {
            width: 100%;
            height: 150px;
            object-fit: cover;
        }

        .skill-card .content {
            padding: 15px;
        }

        .skill-card h3 {
            margin: 0 0 8px 0;
            color: #2e7d32;
        }

        .skill-card p {
            font-size: 14px;
            color: #555;
        }

        /* Other elements */
        .chat-box {
            height: 320px;
            overflow: auto;
            border: 1px solid #eee;
            padding: 8px;
            border-radius: 8px;
            background: #fff;
        }

        .btn {
            padding: 10px 16px;
            border-radius: 8px;
            border: none;
            background: #2e7d32;
            color: hsl(0, 82%, 40%) b1b;
            cursor: pointer;
        }

        .btn:hover {
            background: #256428;
        }
    </style>
</head>

<body>
    <!-- ✅ Sidebar Navigation -->
    <aside class="sidebar">
        <h2>Community Skills</h2>
        <ul>

            <li><a href="skills.php">Skills</a></li>
            <li><a href="share_skill.php">Share Skills</a></li>




            <a href="index.php" class="nav-link">Logout</a>
        </ul>
    </aside>

    <div class="main">
        <div id="views">
            <!-- ✅ Enhanced Overview Section -->
            <section id="view-overview">
                <div class="hero">
                    <h2>Empower Your Community with Shared Skills</h2>
                    <p>
                        Learn, teach, and grow together. Join hundreds of skilled members
                        uplifting one another through knowledge exchange.
                    </p>
                </div>

                <h3>Popular Skills Around You</h3>
                <div class="skills-section">
                    <div class="skill-card">
                        <img src="webdev.jpg" alt="Web Development" />
                        <div class="content">
                            <h3>Web Development</h3>
                            <p>
                                Learn to build stunning, responsive websites using HTML, CSS,
                                and JavaScript.
                            </p>
                        </div>
                    </div>

                    <div class="skill-card">
                        <img src="coocking.jpg" alt="Cooking" />
                        <div class="content">
                            <h3>Cooking</h3>
                            <p>
                                Discover how to prepare delicious local and international
                                dishes step by step.
                            </p>
                        </div>
                    </div>

                    <div class="skill-card">
                        <img src="graph.jpg" alt="Graphic Design" />
                        <div class="content">
                            <h3>Graphic Design</h3>
                            <p>
                                Master design tools like Photoshop and Canva to create
                                eye-catching visuals.
                            </p>
                        </div>
                    </div>

                    <div class="skill-card">
                        <img src="hairdressing.jpg" alt="Hairdressing" />
                        <div class="content">
                            <h3>Hairdressing</h3>
                            <p>
                                Learn professional hairstyling, braiding, and salon management
                                techniques.
                            </p>
                        </div>
                    </div>

                    <div class="skill-card">
                        <img src="carpentry.jpg" alt="Carpentry" />
                        <div class="content">
                            <h3>Carpentry</h3>
                            <p>
                                Gain practical skills in furniture making, wood finishing, and
                                creative design.
                            </p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- The rest of your sections remain unchanged -->
            <section id="view-skills" style="display: none"></section>
            <section id="view-book" style="display: none"></section>
            <section id="view-bookings" style="display: none"></section>
            <section id="view-messages" style="display: none"></section>
            <section id="view-profile" style="display: none"></section>
        </div>
    </div>

    <script>
        const $ = (sel) => document.querySelector(sel);
        const $$ = (sel) => Array.from(document.querySelectorAll(sel));

        // View navigation
        function showView(name) {
            const sections = [
                "overview",
                "skills",
                "book",
                "bookings",
                "messages",
                "profile",
            ];
            sections.forEach((v) => {
                const el = document.getElementById("view-" + v);
                if (el) el.style.display = v === name ? "" : "none";
            });
        }
        showView("overview");
    </script>
</body>

</html>