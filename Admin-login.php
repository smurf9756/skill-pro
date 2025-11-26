<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Login - Community Skills Sharing</title>
    <link rel="stylesheet" href="com.css" />
    <style>
    body {
        font-family: "Poppins", sans-serif;
        background-color: #f8f9fb;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
    }

    .admin-login {
        background: #fff;
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        width: 320px;
        text-align: center;
    }

    input {
        width: 90%;
        padding: 10px;
        margin: 10px 0;
        border: 1px solid #ccc;
        border-radius: 8px;
    }

    button {
        width: 100%;
        padding: 10px;
        background: #007bff;
        border: none;
        color: #fff;
        border-radius: 8px;
        cursor: pointer;
    }

    button:hover {
        background: #0056b3;
    }
    </style>
</head>

<body>
    <header>
        <nav>
            <h1>Community Skills Sharing</h1>
            <ul>
                <li><a href="index.php">Home</a></li>
            </ul>
        </nav>
        <div class="admin-login">
            <h2>Admin Login</h2>
            <form id="adminLoginForm">
                <input type="email" id="adminEmail" placeholder="Admin Email" required />
                <input type="password" id="adminPassword" placeholder="Password" required />
                <button type="submit">Login</button>
            </form>
            <p id="loginMsg"></p>
            <p>© 2025 Community Skills Sharing</p>
        </div>

        <script>
        document.getElementById("adminLoginForm").addEventListener("submit", function(e) {
            e.preventDefault();
            const email = document.getElementById("adminEmail").value.trim();
            const password = document.getElementById("adminPassword").value.trim();
            const msg = document.getElementById("loginMsg");


            if (email === "admin@community.com" && password === "admin123") {
                localStorage.setItem("isAdminLoggedIn", "true");
                msg.textContent = "Login successful!";
                msg.style.color = "green";
                setTimeout(() => {
                    window.location.href = "admin-dashboard.php";
                }, 1000);
            } else {
                msg.textContent = "Invalid credentials!";
                msg.style.color = "red";
            }
        });
        </script>
</body>

</html>