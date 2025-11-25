<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Actions - Community Skills Sharing</title>
    <link rel="stylesheet" href="com.css" />
    <style>
    body {
        font-family: "Poppins", sans-serif;
        background-color: #f5f7fa;
        margin: 0;
        padding: 0;
    }

    header {
        background-color: #1e293b;
        color: white;
        padding: 15px 0;
    }

    nav ul {
        display: flex;
        justify-content: center;
        list-style: none;
        gap: 20px;
        padding: 0;
        margin: 0;
    }

    nav a {
        color: white;
        text-decoration: none;
        font-weight: 500;
    }

    nav a:hover {
        text-decoration: underline;
    }

    section {
        padding: 30px;
        max-width: 1200px;
        margin: auto;
    }

    h2 {
        text-align: center;
        color: #1e293b;
        margin-bottom: 20px;
    }

    .tab-buttons {
        text-align: center;
        margin-bottom: 20px;
    }

    .tab-buttons button {
        background-color: #2563eb;
        color: white;
        border: none;
        padding: 10px 18px;
        border-radius: 6px;
        margin: 5px;
        cursor: pointer;
    }

    .tab-buttons button:hover {
        background-color: #1d4ed8;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        background: white;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
        overflow: hidden;
    }

    th,
    td {
        padding: 12px;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }

    th {
        background-color: #1e293b;
        color: white;
    }

    tr:hover {
        background-color: #f1f5f9;
    }

    .btn-delete {
        background-color: #dc2626;
        color: white;
        border: none;
        padding: 6px 10px;
        border-radius: 5px;
        cursor: pointer;
    }

    .btn-delete:hover {
        background-color: #b91c1c;
    }

    footer {
        text-align: center;
        padding: 15px;
        background-color: #1e293b;
        color: white;
        margin-top: 40px;
    }
    </style>
</head>

<body>
    <!-- Navbar -->
    <header>
        <nav>
            <h1 style="text-align: center;">Community Skills Sharing - Admin</h1>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="admin-dashboard.php">Dashboard</a></li>
                <li><a href="#" id="logoutLink">Logout</a></li>
            </ul>
        </nav>
    </header>

    <!-- Main Section -->
    <section>
        <h2>Admin Management Panel</h2>

        <div class="tab-buttons">
            <button onclick="showTab('users')">Manage Users</button>
            <button onclick="showTab('skills')">Manage Skills</button>
        </div>

        <!-- Users Table -->
        <div id="usersTab">
            <h3>Registered Users</h3>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Full Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Role</th>
                        <th>Created</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="usersTableBody"></tbody>
            </table>
        </div>

        <!-- Skills Table -->
        <div id="skillsTab" style="display: none;">
            <h3>Available Skills</h3>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Skill</th>
                        <th>Trainer</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="skillsTableBody"></tbody>
            </table>
        </div>
    </section>

    <footer>
        <p>&copy; 2025 Community Skills Sharing | Admin Panel by Samuel Nyaga</p>
    </footer>

    <script>
    // ✅ Access Control
    document.addEventListener("DOMContentLoaded", () => {
        const role = localStorage.getItem("userRole");
        const isLoggedIn = localStorage.getItem("isLoggedIn");

        if (!isLoggedIn || role !== "admin") {
            alert("Access denied! Admins only.");
            window.location.href = "admin-login.php";
        } else {
            loadUsers();
        }

        document.getElementById("logoutLink").addEventListener("click", (e) => {
            e.preventDefault();
            localStorage.clear();
            window.location.href = "index.php";
        });
    });

    // ✅ Tab Switching
    function showTab(tab) {
        document.getElementById("usersTab").style.display =
            tab === "users" ? "block" : "none";
        document.getElementById("skillsTab").style.display =
            tab === "skills" ? "block" : "none";

        if (tab === "users") loadUsers();
        else loadSkills();
    }

    // ✅ Load Users
    async function loadUsers() {
        const res = await fetch("admin-actions.php?action=listUsers");
        const data = await res.json();

        const table = document.getElementById("usersTableBody");
        if (data.success) {
            table.innerHTML = data.users
                .map(
                    (u) => `
            <tr>
              <td>${u.id}</td>
              <td>${u.fullname}</td>
              <td>${u.email}</td>
              <td>${u.phone}</td>
              <td>${u.role}</td>
              <td>${u.created_at}</td>
              <td><button class="btn-delete" onclick="deleteUser(${u.id})">Delete</button></td>
            </tr>`
                )
                .join("");
        } else {
            table.innerHTML = `<tr><td colspan="7">${data.message}</td></tr>`;
        }
    }

    // ✅ Delete User
    async function deleteUser(id) {
        if (!confirm("Are you sure you want to delete this user?")) return;

        const formData = new FormData();
        formData.append("id", id);

        const res = await fetch("admin-actions.php?action=deleteUser", {
            method: "POST",
            body: formData,
        });
        const data = await res.json();

        alert(data.message);
        if (data.success) loadUsers();
    }

    // ✅ Load Skills
    async function loadSkills() {
        const res = await fetch("admin-actions.php?action=listSkills");
        const data = await res.json();

        const table = document.getElementById("skillsTableBody");
        if (data.success) {
            table.innerHTML = data.skills
                .map(
                    (s) => `
            <tr>
              <td>${s.skill_id}</td>
              <td>${s.skill_name}</td>
              <td>${s.trainer}</td>
              <td>${s.category}</td>
              <td>${s.price}</td>
              <td><button class="btn-delete" onclick="deleteSkill(${s.skill_id})">Delete</button></td>
            </tr>`
                )
                .join("");
        } else {
            table.innerHTML = `<tr><td colspan="6">${data.message}</td></tr>`;
        }
    }

    // ✅ Delete Skill
    async function deleteSkill(id) {
        if (!confirm("Are you sure you want to delete this skill?")) return;

        const formData = new FormData();
        formData.append("id", id);

        const res = await fetch("admin_actions.php?action=deleteSkill", {
            method: "POST",
            body: formData,
        });
        const data = await res.json();

        alert(data.message);
        if (data.success) loadSkills();
    }
    </script>
</body>

</html>