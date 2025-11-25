<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Community Skills Sharing Platform</title>
    <link rel="stylesheet" href="com.css" />
    <style>
    /* Page Base */
    body {
        font-family: "Poppins", sans-serif;
        margin: 0;
        background-color: #f8f9fb;
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
        transition: color 0.3s;
    }

    nav ul li a:hover,
    nav ul li a.active {
        color: #ffcc00;
    }

    /* Hero Section */
    .hero {
        background: linear-gradient(to right, #004aad, #0078d4);
        color: white;
        text-align: center;
        padding: 80px 20px;
    }

    .hero h2 {
        font-size: 36px;
        margin-bottom: 15px;
    }

    .hero p {
        font-size: 18px;
        margin-bottom: 30px;
        max-width: 800px;
        margin-left: auto;
        margin-right: auto;
        line-height: 1.6;
    }

    .hero-buttons {
        display: flex;
        justify-content: center;
        flex-wrap: wrap;
        gap: 20px;
    }

    .btn {
        background-color: #ffcc00;
        color: #004aad;
        padding: 12px 30px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: bold;
        transition: background-color 0.3s, transform 0.3s;
    }

    .btn:hover {
        background-color: #fff;
        transform: scale(1.05);
    }

    .btn-secondary {
        background-color: white;
        color: #004aad;
        border: 2px solid #ffcc00;
    }

    .btn-secondary:hover {
        background-color: #ffcc00;
        color: #004aad;
    }

    /* Skills Showcase Section */
    .skills-showcase {
        text-align: center;
        padding: 50px 20px;
    }

    .skills-showcase h2 {
        color: #004aad;
        font-size: 28px;
        margin-bottom: 30px;
    }

    .skills-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 25px;
        max-width: 1200px;
        margin: 0 auto;
    }

    .skill-card {
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .skill-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
    }

    .skill-card img {
        width: 100%;
        height: 200px;
        object-fit: cover;
    }

    .skill-info {
        padding: 15px;
    }

    .skill-info h3 {
        color: #004aad;
        font-size: 20px;
        margin-bottom: 8px;
    }

    .skill-info p {
        color: #555;
        font-size: 15px;
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
                <li><a href="index.php" class="active">Home</a></li>

                <li><a href="register.php">Register</a></li>
                <li><a href="login.php">Login</a></li>

                <li><a href="admin-login.php" class="admin-link">Admin Login</a></li>
            </ul>
        </nav>
    </header>

    <!-- Hero Section -->
    <section class="hero">
        <h2>Welcome to the Community Skills Sharing Platform</h2>
        <p>
            Empower yourself and others through shared learning! This platform
            brings together individuals eager to teach, learn, and collaborate.
            Whether you want to enhance your abilities or inspire others with your
            expertise — we help you connect, grow, and make an impact.
        </p>
        <div class="hero-buttons">
            <a href="login.php" class="btn" title="Explore available skills">
                Explore Skills
            </a>
            <a href="login.php" class="btn btn-secondary" title="Register as a trainer">
                Become a Trainer
            </a>
        </div>
    </section>

    <!-- Skills Showcase Section -->
    <section class="skills-showcase">
        <h2>Popular Skills Offered</h2>
        <div class="skills-grid">
            <div class="skill-card">
                <img src="webdev.jpg" alt="Web Development" />
                <div class="skill-info">
                    <h3>Web Development</h3>
                    <p>Learn to build modern, responsive websites.</p>
                </div>
            </div>

            <div class="skill-card">
                <img src="Graph.jpg" alt="Graphic Design" />
                <div class="skill-info">
                    <h3>Graphic Design</h3>
                    <p>Master creative tools to bring ideas to life visually.</p>
                </div>
            </div>

            <div class="skill-card">
                <img src="coocking.jpg" alt="Cooking" />
                <div class="skill-info">
                    <h3>Cooking</h3>
                    <p>Learn delicious recipes and kitchen techniques.</p>
                </div>
            </div>

            <div class="skill-card">
                <img src="photography.jpg" alt="Photography" />
                <div class="skill-info">
                    <h3>Photography</h3>
                    <p>Capture stunning photos like a professional.</p>
                </div>
            </div>

            <div class="skill-card">
                <img src="carpentry.jpg" alt="Carpentry" />
                <div class="skill-info">
                    <h3>Carpentry</h3>
                    <p>Build wooden furniture and craft amazing projects.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <p>&copy; 2025 Community Skills Sharing | Powered by Samuel Nyaga</p>
    </footer>
</body>

</html>